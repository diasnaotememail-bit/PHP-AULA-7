<?php
$mensagem = "";
$login_sucesso = false;
$nome_utilizador = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['acao']) && $_POST['acao'] === 'entrar') {
    $email_digitado = trim($_POST['email'] ?? '');
    $senha_digitada = trim($_POST['senha'] ?? '');

    if (file_exists('usuarios.txt')) {
        $linhas = file('usuarios.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($linhas as $linha) {
            $partes = explode(" | ", $linha);
            
            if (count($partes) == 3) {
                $nome_db = str_replace("Nome: ", "", $partes[0]);
                $email_db = str_replace("Email: ", "", $partes[1]);
                $senha_db = str_replace("Senha: ", "", $partes[2]);
                
                if ($email_digitado === $email_db && $senha_digitada === $senha_db) {
                    $login_sucesso = true;
                    $nome_utilizador = $nome_db;
                    break;
                }
            }
        }
    }

    if (!$login_sucesso) {
        $mensagem = "<b>Dados incorretos, tente novamente.</b><br><br>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>Login de Utilizadores</title>
</head>
<body>
    <?php if ($login_sucesso): ?>

        <p>Login efetuado com sucesso. A redirecionar...</p>
        <form id="formRedirect" action="blog.php" method="POST">
            <input type="hidden" name="usuario_logado" value="<?= htmlspecialchars($nome_utilizador) ?>">
            <button type="submit">Clique aqui se não for redirecionado automaticamente</button>
        </form>
        <script>
            document.getElementById('formRedirect').submit();
        </script>
    <?php else: ?>
        <h2>Login</h2>
        
        <?= $mensagem ?>

        <form method="POST" action="">
            <input type="hidden" name="acao" value="entrar">
            
            <label for="email">E-mail:</label><br>
            <input type="email" id="email" name="email" required><br><br>

            <label for="senha">Senha:</label><br>
            <input type="password" id="senha" name="senha" required><br><br>

            <button type="submit">Entrar</button>
        </form>
    <?php endif; ?>
</body>
</html>