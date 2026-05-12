<?php

namespace App\Controllers;

use App\Models\CategoriaModel;

class CategoriaController extends BaseController

{

private $categoriaModel;

public function __construct()

{

$this->categoriaModel = new CategoriaModel();

helper('form'); // Para ajudar nos formulários

}

// Listar todas as categorias

public function index()

{

$data['categorias'] = $this->categoriaModel->findAll();

// Título da página

$data['titulo'] = 'Lista de Categorias';

return view('categorias/index', $data);

}

// Mostrar formulário de nova categoria

public function novo()

{

$data['titulo'] = 'Nova Categoria';

return view('categorias/form', $data);

}

// Salvar nova categoria

public function criar()

{

// Pegar dados do POST

$dados = $this->request->getPost();

// Tentar salvar

if ($this->categoriaModel->save($dados)) {

return redirect()->to('/categorias')->with('sucesso', 'Categoria criada com sucesso!');

} else {

// Se erro, volta com os dados e os erros

return redirect()->back()

->withInput()

->with('erros', $this->categoriaModel->errors());

}

}

// Mostrar formulário de edição

public function editar($id)

{

$data['categoria'] = $this->categoriaModel->find($id);

if (!$data['categoria']) {

return redirect()->to('/categorias')->with('erro', 'Categoria não encontrada');

}

$data['titulo'] = 'Editar Categoria';

return view('categorias/form', $data);

}

// Atualizar categoria

public function atualizar($id)

{

$dados = $this->request->getPost();

$dados['id'] =$id;

if ($this->categoriaModel->update($id, $dados)) {

return redirect()->to('/categorias')->with('sucesso', 'Categoria atualizada com sucesso!');

} else {

return redirect()->back()

->withInput()

->with('erros', $this->categoriaModel->errors());

}

}

// Excluir categoria

public function excluir($id)

{

if ($this->categoriaModel->delete($id)) {

return redirect()->to('/categorias')->with('sucesso', 'Categoria excluída com sucesso!');

} else {

return redirect()->to('/categorias')->with('erro', 'Erro ao excluir categoria');

}

}

// API: Listar categorias - GET /api/categories
public function apiIndex()
{
    $categorias = $this->categoriaModel->findAll();

    return $this->response->setJSON($categorias);
}

}