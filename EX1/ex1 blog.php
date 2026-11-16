<?php

$utilizador_logado = $_POST['usuario_logado'] ?? '';

if (empty($utilizador_logado)) {
    header("Location: login.php");
    exit;
}

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'publicar') {
    $texto = $_POST['texto'] ?? '';
    $imagem = $_FILES['imagem'] ?? null;

    if (!empty($texto)) {
        $caminhoImagem = '';

        if ($imagem && $imagem['error'] === UPLOAD_ERR_OK) {
            $nomeImagem = time() . '_' . basename($imagem['name']);
            $destino = 'uploads/' . $nomeImagem;
            
            if (move_uploaded_file($imagem['tmp_name'], $destino)) {
                $caminhoImagem = $destino;
            }
        }

        $data = date('Y-m-d H:i:s');
        $conteudoPost = "Data: $data | Texto: $texto | Imagem: $caminhoImagem" . PHP_EOL;

        $ficheiroUtilizador = $utilizador_logado . '.txt';
        file_put_contents($ficheiroUtilizador, $conteudoPost, FILE_APPEND);
        
        $mensagem = "Publicação adicionada com sucesso!";
    } else {
        $mensagem = "Por favor, insira o texto.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Blog</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .form-group { margin-bottom: 15px; }
        .mensagem { color: green; font-weight: bold; }
        .cabecalho { display: flex; justify-content: space-between; align-items: center; }
    </style>
</head>
<body>
    <div class="cabecalho">
        <h1>Adicionar Nova Publicação</h1>
        <p>Bem-vindo, <b><?= htmlspecialchars($utilizador_logado) ?></b>! | <a href="login.php">Sair</a></p>
    </div>
    
    <?php if ($mensagem): ?>
        <p class="mensagem"><?= htmlspecialchars($mensagem) ?></p>
    <?php endif; ?>

    <form action="blog.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="usuario_logado" value="<?= htmlspecialchars($utilizador_logado) ?>">
        <input type="hidden" name="acao" value="publicar">

        <div class="form-group">
            <label for="texto">Conteúdo do Blog:</label><br>
            <textarea name="texto" id="texto" rows="5" cols="50" required></textarea>
        </div>

        <div class="form-group">
            <label for="imagem">Anexar Imagem:</label><br>
            <input type="file" name="imagem" id="imagem" accept="image/*">
        </div>

        <button type="submit">Publicar</button>
    </form>
</body>
</html>