<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titulo ?></title>
    <style>
        body { font-family: Arial; margin: 40px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type=text], textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 3px; }
        .btn { padding: 10px 20px; border: none; border-radius: 3px; cursor: pointer; }
        .btn-salvar { background: #4CAF50; color: white; }
        .btn-cancelar { background: #6c757d; color: white; }
        .erro { background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 20px; border-radius: 3px; }
        .erro-campo { color: #dc3545; font-size: 0.9em; margin-top: 5px; }
    </style>
</head>
<body>
    <h1><?= $titulo ?></h1>
    
    <!-- Exibir erros de validação -->
    <?php if(session()->getFlashdata('erros')): ?>
        <div class="erro">
            <?php foreach(session()->getFlashdata('erros') as $erro): ?>
                <p><?= $erro ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <?php 
        // Define ação do formulário baseado se é edição ou novo
        $acao = isset($categoria) ? '/categorias/atualizar/' . $categoria->id : '/categorias/criar';
    ?>
    
    <form action="<?= $acao ?>" method="POST">
        <!-- Campo escondido para o método PUT (necessário em alguns casos) -->
        <?php if(isset($categoria)): ?>
            <input type="hidden" name="_method" value="PUT">
        <?php endif; ?>
        
        <div class="form-group">
            <label for="nome">Nome da Categoria:</label>
            <input type="text" 
                   name="nome" 
                   id="nome" 
                   value="<?= old('nome', isset($categoria) ? $categoria->nome : '') ?>" 
                   required>
        </div>
        
        <div class="form-group">
            <label for="descricao">Descrição:</label>
            <textarea name="descricao" 
                      id="descricao" 
                      rows="5"><?= old('descricao', isset($categoria) ? $categoria->descricao : '') ?></textarea>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-salvar">Salvar</button>
            <a href="/categorias" class="btn btn-cancelar">Cancelar</a>
        </div>
    </form>
</body>
</html>