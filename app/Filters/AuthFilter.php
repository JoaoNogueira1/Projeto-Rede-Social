<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Executado antes do controller
     * Verifica se o usuário está logado
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Se o usuário não está logado, redireciona para login
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Faça login primeiro.');
        }
    }

    /**
     * Executado após o controller
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Não necessário aqui
    }
}
