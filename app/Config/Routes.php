<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Rotas de Autenticação (públicas)
$routes->get('/login', 'AuthController::loginView');
$routes->post('/auth/login', 'AuthController::login');
$routes->get('/auth/register', 'AuthController::registerView');
$routes->post('/auth/register', 'AuthController::register');
$routes->get('/auth/logout', 'AuthController::logout');

// Página inicial (opcional) - Exibe a página principal com lista de posts
$routes->get('/', 'PostController::index');

// Rotas protegidas por autenticação (requer login)
$routes->group('', ['filter' => 'auth'], function($routes) {
    // GET /feed - Exibe o feed de postagens
    $routes->get('feed', 'PostController::index');
    // GET /perfil - Exibe o perfil do usuário
    $routes->get('perfil', 'UsuarioController::profile');
    // POST /post/create - Cria uma nova postagem
    $routes->post('post/create', 'PostController::create');
    // GET /post/edit/{id} - Exibe o formulário para editar uma postagem
    $routes->get('post/edit/(:num)', 'PostController::edit/$1');
    // PUT /post/update/{id} - Atualiza uma postagem
    $routes->put('post/update/(:num)', 'PostController::update/$1');
    // POST /post/delete/{id} - Deleta uma postagem
    $routes->post('post/delete/(:num)', 'PostController::delete/$1');
    // GET /dashboard - Dashboard do usuário
    $routes->get('dashboard', 'PostController::dashboard');
});

// Rotas para categorias (web) - Gerenciamento de categorias via interface web
$routes->group('categorias', function($routes) {
    // GET /categorias - Lista todas as categorias
    $routes->get('/', 'CategoriaController::index');
    // GET /categorias/novo - Formulário para criar nova categoria
    $routes->get('novo', 'CategoriaController::novo');
    // POST /categorias/criar - Salva nova categoria no banco
    $routes->post('criar', 'CategoriaController::criar');
    // GET /categorias/editar/{id} - Formulário para editar categoria existente
    $routes->get('editar/(:num)', 'CategoriaController::editar/$1');
    // PUT /categorias/atualizar/{id} - Atualiza categoria no banco
    $routes->put('atualizar/(:num)', 'CategoriaController::atualizar/$1');
    // GET /categorias/excluir/{id} - Exclui categoria (método GET para simplicidade)
    $routes->get('excluir/(:num)', 'CategoriaController::excluir/$1');
});

// Rotas da API para a rede social (consumida pelo app mobile)
$routes->group('api', ['namespace' => 'App\Controllers'], function($routes) {
    // Autenticação
    // POST /api/auth/login - Realiza login do usuário, retorna dados do usuário se sucesso
    $routes->post('auth/login', 'UsuarioController::login');
    // POST /api/auth/register - Registra novo usuário no sistema
    $routes->post('auth/register', 'UsuarioController::register');

    // Usuários
    // GET /api/users/profile - Retorna dados do perfil do usuário logado
    $routes->get('users/profile', 'UsuarioController::profile');
    // PUT /api/users/profile - Atualiza dados do perfil do usuário
    $routes->put('users/profile', 'UsuarioController::updateProfile');

    // Posts
    // GET /api/posts - Lista todos os posts com dados do autor
    $routes->get('posts', 'PostController::apiIndex');
    // POST /api/posts - Cria um novo post
    $routes->post('posts', 'PostController::apiCreate');
    // GET /api/posts/{id} - Retorna dados de um post específico
    $routes->get('posts/(:num)', 'PostController::apiShow/$1');
    // PUT /api/posts/{id} - Atualiza um post existente
    $routes->put('posts/(:num)', 'PostController::apiUpdate/$1');
    // DELETE /api/posts/{id} - Exclui um post
    $routes->delete('posts/(:num)', 'PostController::apiDelete/$1');

    // Likes
    // POST /api/posts/{id}/like - Adiciona like a um post
    $routes->post('posts/(:num)/like', 'PostController::apiLike/$1');
    // DELETE /api/posts/{id}/like - Remove like de um post
    $routes->delete('posts/(:num)/like', 'PostController::apiUnlike/$1');

    // Categorias
    // GET /api/categories - Lista todas as categorias disponíveis
    $routes->get('categories', 'CategoriaController::apiIndex');
});