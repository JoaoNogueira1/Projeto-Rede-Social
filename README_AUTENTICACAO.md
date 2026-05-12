# 🚀 Rede Social - Sistema de Autenticação com CodeIgniter 4

## 📋 Descrição

Este projeto implementa um **sistema de autenticação completo** usando **Sessions e Filters** (sem bibliotecas prontas como Myth/Auth) em CodeIgniter 4.

### Funcionalidades Implementadas:

✅ **Cadastro de Usuário**
- Nome, e-mail e senha (mínimo 6 caracteres)
- Confirmação de senha
- Validação de e-mail duplicado
- Hash de senha seguro com `password_hash()`

✅ **Login**
- Validação de credenciais
- Mensagens de erro claras
- Redirecionamento para feed após login
- Sessão do usuário criada

✅ **Logout**
- Destruição da sessão
- Redirecionamento para login com mensagem

✅ **Proteção de Rotas com AuthFilter**
- Rotas protegidas: `/feed`, `/perfil`, `/post/create`, `/post/edit/{id}`, `/dashboard`
- Redireciona usuários não autenticados para `/login`

✅ **Mensagens Flash**
- "Bem-vindo, [nome]" após login
- "Você saiu da sua conta" após logout
- Mensagens de erro e sucesso em formulários

✅ **Sistema de Posts**
- Criar postagem (protegido)
- Editar postagem (apenas dono)
- Deletar postagem (apenas dono)
- Dashboard com posts do usuário

---

## 🛠️ Requisitos

- PHP 7.4+
- MySQL 5.7+
- Composer
- CodeIgniter 4

---

## 📦 Instalação

### 1. Clonar ou baixar o projeto

```bash
git clone <seu-repositorio>
cd ProjetoPHP
```

### 2. Instalar dependências

```bash
composer install
```

### 3. Configurar arquivo .env

```bash
# Copiar o arquivo .env.example para .env
cp .env.example .env
```

Editar o arquivo `.env` com suas configurações de banco de dados:

```env
CI_ENVIRONMENT = development

database.default.hostname = localhost
database.default.database = academico_net
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port = 3306
```

### 4. Criar banco de dados

```sql
CREATE DATABASE academico_net;
```

### 5. Executar migrations

```bash
php spark migrate
```

### 6. Popular dados de teste (seeders)

```bash
php spark db:seed MainSeeder
```

Isso irá criar um usuário de teste com:
- **Nome:** Vinícius Mendes
- **E-mail:** viniciusmendes@teste.com
- **Senha:** 123456

---

## 🚀 Como Testar

### 1. Iniciar o servidor

```bash
php spark serve
```

O projeto estará disponível em `http://localhost:8080`

### 2. Testar Fluxo de Autenticação

#### **Teste 1: Cadastro de Novo Usuário**
1. Acesse `http://localhost:8080/auth/register`
2. Preencha o formulário:
   - Nome: Seu Nome
   - E-mail: seu@email.com
   - Senha: senha123
   - Confirmar Senha: senha123
3. Clique em "Cadastrar"
4. Deverá ser redirecionado para login com mensagem de sucesso

#### **Teste 2: Login com Usuário Existente**
1. Acesse `http://localhost:8080/login`
2. Use as credenciais do seeder ou do usuário recém-criado
3. Clique em "Entrar"
4. Deverá ver a mensagem "Bem-vindo, [seu nome]!"
5. Será redirecionado para `/feed`

#### **Teste 3: Acesso a Rota Protegida**
1. Faça logout
2. Tente acessar `http://localhost:8080/feed` diretamente
3. Deverá ser redirecionado para `/login` com mensagem "Faça login primeiro"

#### **Teste 4: Criar Postagem**
1. Faça login
2. No feed, escreva algo no campo "No que você está pensando agora?"
3. Clique em "Publicar"
4. A postagem deverá aparecer no topo do feed

#### **Teste 5: Acessar Perfil**
1. Faça login
2. Clique em "Meu Perfil" na navbar
3. Deverá ver informações da sua conta

#### **Teste 6: Dashboard**
1. No perfil, clique em "Minhas Postagens"
2. Deverá ver todas as suas postagens
3. Tenha opções de Editar e Deletar

#### **Teste 7: Editar Postagem**
1. No dashboard, clique em "Editar" em uma postagem
2. Altere o texto
3. Clique em "Salvar Alterações"
4. Será redirecionado para feed com mensagem de sucesso

#### **Teste 8: Logout**
1. Clique em "Sair" na navbar
2. Deverá ver a mensagem "Você saiu da sua conta"
3. Será redirecionado para login

---

## 📁 Estrutura de Arquivos

```
app/
├── Controllers/
│   ├── AuthController.php          # Login, logout, cadastro
│   ├── PostController.php          # Posts (protegido)
│   ├── UsuarioController.php       # Perfil do usuário
│   └── ...
├── Filters/
│   └── AuthFilter.php              # Filter de autenticação
├── Models/
│   ├── UsuarioModel.php
│   ├── PostModel.php
│   └── ...
├── Views/
│   ├── auth/
│   │   ├── login.php               # Tela de login
│   │   └── register.php            # Tela de cadastro
│   ├── post/
│   │   └── edit.php                # Editar postagem
│   ├── perfil.php                  # Perfil do usuário
│   ├── dashboard.php               # Dashboard de posts
│   ├── welcome_message.php         # Feed principal
│   └── ...
├── Config/
│   ├── Filters.php                 # Registro do AuthFilter
│   ├── Routes.php                  # Rotas com proteção
│   └── ...
└── Database/
    ├── Migrations/                 # Migrations das tabelas
    └── Seeds/                      # Seeders de teste
```

---

## 🔐 Como Funciona a Autenticação

### AuthController.php
- **`loginView()`**: Exibe o formulário de login
- **`login()`**: Processa o login e cria a sessão
- **`registerView()`**: Exibe o formulário de cadastro
- **`register()`**: Processa o cadastro do novo usuário
- **`logout()`**: Destrui a sessão e redireciona para login

### AuthFilter.php
- Verifica se `session()->get('isLoggedIn')` está definido
- Se não estiver, redireciona para `/login` com mensagem de erro
- Aplicado nas rotas protegidas definidas em `Config/Filters.php`

### Config/Filters.php
```php
public array $aliases = [
    // ...
    'auth' => AuthFilter::class,
];
```

### Config/Routes.php
```php
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('feed', 'PostController::index');
    $routes->get('perfil', 'UsuarioController::profile');
    // ... outras rotas protegidas
});
```

---

## 🛡️ Segurança

- ✅ Senhas criptografadas com `password_hash()` e `password_verify()`
- ✅ Sessions apenas para usuários autenticados
- ✅ Proteção contra acesso a rotas sem login
- ✅ Validação de propriedade de postagem (apenas dono pode editar/deletar)
- ✅ CSRF protection habilitada
- ✅ Mensagens de erro genéricas no login (evita enumeration)

---

## 📝 Endpoints Principais

### Públicos
- `GET /login` - Formulário de login
- `POST /auth/login` - Processa login
- `GET /auth/register` - Formulário de cadastro
- `POST /auth/register` - Processa cadastro

### Protegidos (requer autenticação)
- `GET /feed` - Feed principal
- `GET /perfil` - Perfil do usuário
- `POST /post/create` - Criar postagem
- `GET /post/edit/{id}` - Editar postagem
- `PUT /post/update/{id}` - Salvar alterações
- `GET /dashboard` - Dashboard com posts do usuário
- `GET /auth/logout` - Sair da conta

---

## 🐛 Troubleshooting

### Erro: "Banco de dados não encontrado"
- Verifique se o banco `academico_net` foi criado
- Confira as credenciais em `.env`

### Erro: "Tabelas não encontradas"
- Execute: `php spark migrate`

### Erro ao cadastrar: "E-mail já existe"
- Use um e-mail diferente ou delete o usuário do banco

### Sessão não persiste entre páginas
- Verifique se cookies estão habilitados
- Confirme se a pasta `writable/session/` existe

---

## 📚 Referências

- [CodeIgniter 4 - Sessions](https://codeigniter.com/user_guide/libraries/sessions.html)
- [CodeIgniter 4 - Filters](https://codeigniter.com/user_guide/incoming/filters.html)
- [PHP password_hash](https://www.php.net/manual/en/function.password-hash.php)

---

## 👨‍💻 Autor

Projeto desenvolvido como atividade prática de autenticação em CodeIgniter 4.

---

## 📄 Licença

Este projeto é fornecido como base de aprendizado.
