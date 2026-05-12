<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
    protected $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
    }

    /**
     * Exibe o formulário de login
     */
    public function loginView()
    {
        // Se o usuário já está logado, redireciona para o feed
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/feed');
        }

        return view('auth/login');
    }

    /**
     * Processa o login
     */
    public function login()
    {
        $email = $this->request->getPost('email');
        $senha = $this->request->getPost('senha');

        // Validação básica
        if (empty($email) || empty($senha)) {
            return redirect()->back()
                           ->with('error', 'E-mail e senha são obrigatórios.')
                           ->withInput();
        }

        // Busca o usuário no banco
        $usuario = $this->usuarioModel->where('email', $email)->first();

        // Valida credenciais
        if (!$usuario || !password_verify($senha, $usuario['senha'])) {
            return redirect()->back()
                           ->with('error', 'E-mail ou senha inválidos.')
                           ->withInput();
        }

        // Define a session do usuário
        session()->set([
            'isLoggedIn' => true,
            'usuario_id' => $usuario['id'],
            'nome'       => $usuario['nome'],
            'email'      => $usuario['email'],
        ]);

        // Mensagem de boas-vindas
        return redirect()->to('/feed')
                       ->with('success', 'Bem-vindo, ' . $usuario['nome'] . '!');
    }

    /**
     * Exibe o formulário de cadastro
     */
    public function registerView()
    {
        // Se o usuário já está logado, redireciona para o feed
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/feed');
        }

        return view('auth/register');
    }

    /**
     * Processa o cadastro
     */
    public function register()
    {
        $nome = $this->request->getPost('nome');
        $email = $this->request->getPost('email');
        $senha = $this->request->getPost('senha');
        $confirmaSenha = $this->request->getPost('confirma_senha');

        // Validações
        $erros = [];

        if (empty($nome)) {
            $erros[] = 'Nome é obrigatório.';
        }

        if (empty($email)) {
            $erros[] = 'E-mail é obrigatório.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erros[] = 'E-mail inválido.';
        }

        if (empty($senha)) {
            $erros[] = 'Senha é obrigatória.';
        } elseif (strlen($senha) < 6) {
            $erros[] = 'Senha deve ter no mínimo 6 caracteres.';
        }

        if ($senha !== $confirmaSenha) {
            $erros[] = 'As senhas não coincidem.';
        }

        // Verifica se o e-mail já existe
        if ($this->usuarioModel->where('email', $email)->first()) {
            $erros[] = 'Este e-mail já está cadastrado.';
        }

        // Se há erros, volta com mensagens
        if (!empty($erros)) {
            return redirect()->back()
                           ->with('error', implode('<br>', $erros))
                           ->withInput();
        }

        // Cria o novo usuário
        $dadosUsuario = [
            'nome'  => $nome,
            'email' => $email,
            'senha' => password_hash($senha, PASSWORD_DEFAULT),
        ];

        if ($this->usuarioModel->insert($dadosUsuario)) {
            return redirect()->to('/login')
                           ->with('success', 'Cadastro realizado com sucesso! Faça login agora.');
        } else {
            return redirect()->back()
                           ->with('error', 'Erro ao cadastrar usuário. Tente novamente.')
                           ->withInput();
        }
    }

    /**
     * Faz o logout
     */
    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login')
                       ->with('success', 'Você saiu da sua conta.');
    }
}
