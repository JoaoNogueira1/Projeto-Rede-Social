<!DOCTYPE html> 
<html lang="pt-br"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Rede Social - Protótipo Dinâmico</title> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> 
    <style> 
        body { background-color: #f0f2f5; } 
        .post-card { border: none; border-radius: 8px; margin-bottom: 20px; } 
        .user-img { width: 40px; height: 40px; border-radius: 50%; background: #ccc; display: inline-block; } 
    </style> 
</head> 
<body> 

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 shadow-sm"> 
    <div class="container"> 
        <a class="navbar-brand fw-bold" href="/feed">🚀 NomeDaRede</a> 
        <div class="navbar-nav ms-auto"> 
            <a class="nav-link active" href="/feed">Página Inicial</a> 
            <a class="nav-link" href="/perfil">Meu Perfil</a> 
            <a class="nav-link" href="/auth/logout">Sair</a> 
        </div> 
    </div> 
</nav> 

<div class="container"> 
    <div class="row justify-content-center"> 
        <div class="col-md-7">

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
             
            <div class="card post-card shadow-sm"> 
                <div class="card-body"> 
                    <div class="d-flex mb-3"> 
                        <div class="user-img me-2"></div> 
                        <h6 class="align-self-center mb-0 text-muted">No que você está pensando agora?</h6> 
                    </div> 
                    <form action="/post/create" method="POST"> 
                        <?= csrf_field() ?>
                        <div class="mb-3"> 
                            <textarea class="form-control" rows="3" name="texto" placeholder="Escreva algo interessante..." required></textarea> 
                        </div> 
                        <div class="text-end"> 
                            <button type="submit" class="btn btn-primary px-4">Publicar</button> 
                        </div> 
                    </form> 
                </div> 
            </div>

            <?php if (!empty($posts) && is_array($posts)): ?>
                <?php foreach ($posts as $post): ?>
                    <div class="card post-card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex mb-3"> 
                                <div class="user-img me-2 bg-info"></div> 
                                <div> 
                                    <h6 class="mb-0 fw-bold"><?= esc($post['nome']) ?></h6> 
                                    <small class="text-muted">Postado em: <?= date('d/m/Y H:i', strtotime($post['created_at'])) ?></small> 
                                </div> 
                            </div> 
                            <p class="card-text"><?= esc($post['texto']) ?></p> 
                            <hr> 
                            <div class="d-flex justify-content-between align-items-center"> 
                                <button class="btn btn-sm btn-outline-primary">👍 Curtir</button> 
                                <span class="badge bg-light text-dark border">0 Curtidas</span> 
                            </div> 
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-info text-center">
                    Nenhuma postagem encontrada no banco.
                </div>
            <?php endif; ?>

        </div> 
    </div> 
</div> 

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> 
</body> 
</html>