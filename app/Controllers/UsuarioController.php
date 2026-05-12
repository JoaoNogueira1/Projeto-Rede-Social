<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;
use CodeIgniter\HTTP\ResponseInterface;

class UsuarioController extends BaseController
{
    protected $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
    }

    // Login - POST /api/auth/login
    public function login()
    {
        $data = $this->request->getJSON(true);

        if (!$data || !isset($data['email']) || !isset($data['senha'])) {
            return $this->response->setJSON(['error' => 'Email e senha são obrigatórios'])->setStatusCode(400);
        }

        $usuario = $this->usuarioModel->where('email', $data['email'])->first();

        if (!$usuario || !password_verify($data['senha'], $usuario['senha'])) {
            return $this->response->setJSON(['error' => 'Credenciais inválidas'])->setStatusCode(401);
        }

        // Aqui você pode gerar um token JWT se necessário
        return $this->response->setJSON([
            'message' => 'Login realizado com sucesso',
            'usuario' => [
                'id' => $usuario['id'],
                'nome' => $usuario['nome'],
                'email' => $usuario['email']
            ]
        ]);
    }

    // Registro - POST /api/auth/register
    public function register()
    {
        $data = $this->request->getJSON(true);

        if (!$data || !isset($data['nome']) || !isset($data['email']) || !isset($data['senha'])) {
            return $this->response->setJSON(['error' => 'Nome, email e senha são obrigatórios'])->setStatusCode(400);
        }

        // Verificar se email já existe
        if ($this->usuarioModel->where('email', $data['email'])->first()) {
            return $this->response->setJSON(['error' => 'Email já cadastrado'])->setStatusCode(409);
        }

        $data['senha'] = password_hash($data['senha'], PASSWORD_DEFAULT);
        $data['created_at'] = date('Y-m-d H:i:s');

        if ($this->usuarioModel->insert($data)) {
            return $this->response->setJSON(['message' => 'Usuário registrado com sucesso'])->setStatusCode(201);
        }

        return $this->response->setJSON(['error' => 'Erro ao registrar usuário'])->setStatusCode(500);
    }

    // Perfil - GET /api/users/profile
    public function profile()
    {
        // Assumindo que o usuário está autenticado via token ou sessão
        // Para simplificar, retornar um usuário fixo ou implementar autenticação
        $usuarioId = 1; // Substitua por lógica de autenticação

        $usuario = $this->usuarioModel->find($usuarioId);

        if (!$usuario) {
            return $this->response->setJSON(['error' => 'Usuário não encontrado'])->setStatusCode(404);
        }

        return $this->response->setJSON($usuario);
    }

    // Atualizar perfil - PUT /api/users/profile
    public function updateProfile()
    {
        $data = $this->request->getJSON(true);

        $usuarioId = 1; // Substitua por lógica de autenticação

        if ($this->usuarioModel->update($usuarioId, $data)) {
            return $this->response->setJSON(['message' => 'Perfil atualizado com sucesso']);
        }

        return $this->response->setJSON(['error' => 'Erro ao atualizar perfil'])->setStatusCode(500);
    }

    // Web: Exibir perfil do usuário
    public function profile()
    {
        $usuarioId = session()->get('usuario_id');
        $usuario = $this->usuarioModel->find($usuarioId);

        if (!$usuario) {
            return redirect()->to('/login')->with('error', 'Usuário não encontrado.');
        }

        $data['usuario'] = $usuario;

        return view('perfil', $data);
    }
}