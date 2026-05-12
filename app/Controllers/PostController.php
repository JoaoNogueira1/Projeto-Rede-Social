<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class PostController extends BaseController
{
    public function index()
{
    $postModel = new \App\Models\PostModel();

    // Lógica do Query Builder:
    $data['posts'] = $postModel->select('postagens.*, usuarios.nome')
                               ->join('usuarios', 'usuarios.id = postagens.usuario_id')
                               ->orderBy('postagens.created_at', 'DESC')
                               ->findAll();

    return view('welcome_message', $data);
}

    // API: Listar posts - GET /api/posts
    public function apiIndex()
    {
        $postModel = new \App\Models\PostModel();

        $posts = $postModel->select('postagens.*, usuarios.nome')
                           ->join('usuarios', 'usuarios.id = postagens.usuario_id')
                           ->orderBy('postagens.created_at', 'DESC')
                           ->findAll();

        return $this->response->setJSON($posts);
    }

    // API: Criar post - POST /api/posts
    public function apiCreate()
    {
        $data = $this->request->getJSON(true);

        if (!$data || !isset($data['titulo']) || !isset($data['conteudo']) || !isset($data['categoria_id'])) {
            return $this->response->setJSON(['error' => 'Título, conteúdo e categoria são obrigatórios'])->setStatusCode(400);
        }

        $data['usuario_id'] = 1; // Substitua por usuário autenticado
        $data['created_at'] = date('Y-m-d H:i:s');

        $postModel = new \App\Models\PostModel();

        if ($postModel->insert($data)) {
            return $this->response->setJSON(['message' => 'Post criado com sucesso'])->setStatusCode(201);
        }

        return $this->response->setJSON(['error' => 'Erro ao criar post'])->setStatusCode(500);
    }

    // API: Mostrar post - GET /api/posts/{id}
    public function apiShow($id)
    {
        $postModel = new \App\Models\PostModel();

        $post = $postModel->select('postagens.*, usuarios.nome')
                          ->join('usuarios', 'usuarios.id = postagens.usuario_id')
                          ->find($id);

        if (!$post) {
            return $this->response->setJSON(['error' => 'Post não encontrado'])->setStatusCode(404);
        }

        return $this->response->setJSON($post);
    }

    // API: Atualizar post - PUT /api/posts/{id}
    public function apiUpdate($id)
    {
        $data = $this->request->getJSON(true);

        $postModel = new \App\Models\PostModel();

        if (!$postModel->find($id)) {
            return $this->response->setJSON(['error' => 'Post não encontrado'])->setStatusCode(404);
        }

        if ($postModel->update($id, $data)) {
            return $this->response->setJSON(['message' => 'Post atualizado com sucesso']);
        }

        return $this->response->setJSON(['error' => 'Erro ao atualizar post'])->setStatusCode(500);
    }

    // API: Deletar post - DELETE /api/posts/{id}
    public function apiDelete($id)
    {
        $postModel = new \App\Models\PostModel();

        if (!$postModel->find($id)) {
            return $this->response->setJSON(['error' => 'Post não encontrado'])->setStatusCode(404);
        }

        if ($postModel->delete($id)) {
            return $this->response->setJSON(['message' => 'Post deletado com sucesso']);
        }

        return $this->response->setJSON(['error' => 'Erro ao deletar post'])->setStatusCode(500);
    }

    // API: Dar like - POST /api/posts/{id}/like
    public function apiLike($id)
    {
        $likeModel = new \App\Models\LikeModel();

        $usuarioId = 1; // Substitua por usuário autenticado

        if ($likeModel->where('usuario_id', $usuarioId)->where('postagem_id', $id)->first()) {
            return $this->response->setJSON(['error' => 'Já deu like neste post'])->setStatusCode(409);
        }

        $data = [
            'usuario_id' => $usuarioId,
            'postagem_id' => $id,
            'created_at' => date('Y-m-d H:i:s')
        ];

        if ($likeModel->insert($data)) {
            return $this->response->setJSON(['message' => 'Like adicionado']);
        }

        return $this->response->setJSON(['error' => 'Erro ao adicionar like'])->setStatusCode(500);
    }

    // API: Remover like - DELETE /api/posts/{id}/like
    public function apiUnlike($id)
    {
        $likeModel = new \App\Models\LikeModel();

        $usuarioId = 1; // Substitua por usuário autenticado

        $like = $likeModel->where('usuario_id', $usuarioId)->where('postagem_id', $id)->first();

        if (!$like) {
            return $this->response->setJSON(['error' => 'Like não encontrado'])->setStatusCode(404);
        }

        if ($likeModel->delete($like['id'])) {
            return $this->response->setJSON(['message' => 'Like removido']);
        }

        return $this->response->setJSON(['error' => 'Erro ao remover like'])->setStatusCode(500);
    }

    // Web: Criar postagem
    public function create()
    {
        $texto = $this->request->getPost('texto');
        $usuarioId = session()->get('usuario_id');

        if (empty($texto)) {
            return redirect()->back()->with('error', 'A postagem não pode estar vazia.');
        }

        $postModel = new \App\Models\PostModel();

        $data = [
            'usuario_id' => $usuarioId,
            'texto' => $texto,
        ];

        if ($postModel->insert($data)) {
            return redirect()->to('/feed')->with('success', 'Postagem publicada com sucesso!');
        }

        return redirect()->back()->with('error', 'Erro ao publicar postagem.');
    }

    // Web: Editar postagem
    public function edit($id)
    {
        $postModel = new \App\Models\PostModel();
        $post = $postModel->find($id);

        if (!$post) {
            return redirect()->to('/feed')->with('error', 'Postagem não encontrada.');
        }

        // Verifica se o usuário é o dono da postagem
        if ($post['usuario_id'] !== session()->get('usuario_id')) {
            return redirect()->to('/feed')->with('error', 'Você não pode editar esta postagem.');
        }

        return view('post/edit', ['post' => $post]);
    }

    // Web: Atualizar postagem
    public function update($id)
    {
        $texto = $this->request->getPost('texto');
        $postModel = new \App\Models\PostModel();

        $post = $postModel->find($id);

        if (!$post) {
            return redirect()->to('/feed')->with('error', 'Postagem não encontrada.');
        }

        // Verifica se o usuário é o dono da postagem
        if ($post['usuario_id'] !== session()->get('usuario_id')) {
            return redirect()->to('/feed')->with('error', 'Você não pode editar esta postagem.');
        }

        if (empty($texto)) {
            return redirect()->back()->with('error', 'A postagem não pode estar vazia.');
        }

        if ($postModel->update($id, ['texto' => $texto])) {
            return redirect()->to('/feed')->with('success', 'Postagem atualizada com sucesso!');
        }

        return redirect()->back()->with('error', 'Erro ao atualizar postagem.');
    }

    // Web: Deletar postagem
    public function delete($id)
    {
        $postModel = new \App\Models\PostModel();
        $post = $postModel->find($id);

        if (!$post) {
            return redirect()->to('/feed')->with('error', 'Postagem não encontrada.');
        }

        // Verifica se o usuário é o dono da postagem
        if ($post['usuario_id'] !== session()->get('usuario_id')) {
            return redirect()->to('/feed')->with('error', 'Você não pode deletar esta postagem.');
        }

        if ($postModel->delete($id)) {
            return redirect()->to('/feed')->with('success', 'Postagem deletada com sucesso!');
        }

        return redirect()->back()->with('error', 'Erro ao deletar postagem.');
    }

    // Web: Dashboard do usuário
    public function dashboard()
    {
        $usuarioId = session()->get('usuario_id');
        $postModel = new \App\Models\PostModel();

        // Busca apenas as postagens do usuário logado
        $data['posts'] = $postModel->select('postagens.*, usuarios.nome')
                                   ->join('usuarios', 'usuarios.id = postagens.usuario_id')
                                   ->where('postagens.usuario_id', $usuarioId)
                                   ->orderBy('postagens.created_at', 'DESC')
                                   ->findAll();

        return view('dashboard', $data);
    }
}
