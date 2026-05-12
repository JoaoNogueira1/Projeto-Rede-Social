<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Dashboard - Rede Social</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; }
        .navbar { box-shadow: 0 2px 4px rgba(0,0,0,.1); }
        .post-card {
            border: none;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,.1);
        }
        .user-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #ccc;
            display: inline-block;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/feed">🚀 NomeDaRede</a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link" href="/feed">Página Inicial</a>
            <a class="nav-link" href="/perfil">Meu Perfil</a>
            <a class="nav-link active" href="/dashboard">Dashboard</a>
            <a class="nav-link" href="/auth/logout">Sair</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="mb-4">Minhas Postagens</h2>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (!empty($posts) && is_array($posts)): ?>
                <?php foreach ($posts as $post): ?>
                    <div class="card post-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h6 class="mb-0 fw-bold"><?= esc($post['nome']) ?></h6>
                                    <small class="text-muted">
                                        <?= date('d/m/Y H:i', strtotime($post['created_at'])) ?>
                                    </small>
                                </div>
                                <div class="btn-group" role="group">
                                    <a href="/post/edit/<?= $post['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                                    <form action="/post/delete/<?= $post['id'] ?>" method="POST" style="display:inline;">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">Deletar</button>
                                    </form>
                                </div>
                            </div>
                            <p class="card-text"><?= esc($post['texto']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-info text-center">
                    Você ainda não tem postagens. <a href="/feed">Volte para o feed e comece a postar!</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
