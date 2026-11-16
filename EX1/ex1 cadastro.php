<?php
$mensagem = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = htmlspecialchars(trim($_POST['nome'] ?? ''));
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $senha = trim($_POST['senha'] ?? '');

    if (!empty($nome) && !empty($email) && !empty($senha)) {
        
        $nome = str_replace(["\r", "\n"], ' ', $nome);
        $email = str_replace(["\r", "\n"], ' ', $email);
        
        $linha = "Nome: $nome | Email: $email | Senha: $senha" . PHP_EOL;

        if (file_put_contents('usuarios.txt', $linha, FILE_APPEND | LOCK_EX)) {
            $mensagem = "<b>Usuário cadastrado com sucesso!</b><br><br>";
        } else {
            $mensagem = "<b>Erro ao tentar salvar no arquivo. Verifique as permissões da pasta.</b><br><br>";
        }
    } else {
        $mensagem = "<b>Por favor, preencha todos os campos.</b><br><br>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuários</title>
</head>
<body>

    <h2>Cadastro</h2>
    
    <?= $mensagem ?>

    <form method="POST" action="">
        <label for="nome">Nome:</label><br>
        <input type="text" id="nome" name="nome" required><br><br>

        <label for="email">E-mail:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="senha">Senha:</label><br>
        <input type="password" id="senha" name="senha" required><br><br>

        <button type="submit">Cadastrar</button>
    </form>

</body>
</html>