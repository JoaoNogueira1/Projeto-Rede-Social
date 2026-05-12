# API Routes - Rede Social

Este documento descreve as rotas da API para o app mobile da rede social.

## Autenticação

- **POST /api/auth/login** - Realiza login do usuário (exemplo: envia email e senha, retorna token ou dados do usuário)
- **POST /api/auth/register** - Registra novo usuário no sistema

## Usuários

- **GET /api/users/profile** - Obtém dados do perfil do usuário logado
- **PUT /api/users/profile** - Atualiza informações do perfil

## Posts

- **GET /api/posts** - Lista todos os posts disponíveis
- **POST /api/posts** - Cria um novo post
- **GET /api/posts/{id}** - Obtém detalhes de um post específico
- **PUT /api/posts/{id}** - Atualiza um post existente
- **DELETE /api/posts/{id}** - Exclui um post

## Likes

- **POST /api/posts/{id}/like** - Adiciona um like a um post
- **DELETE /api/posts/{id}/like** - Remove o like de um post

## Categorias

- **GET /api/categories** - Lista todas as categorias disponíveis