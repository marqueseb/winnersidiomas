<?php
session_start();

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Substitua pela validação no banco de dados
    if ($username == 'admin' && $password == 'senha') {
        $_SESSION['admin'] = true;  // Criação da sessão
        header('Location: admin_dashboard.php');  // Redirecionamento
        exit();
    } else {
        echo 'Usuário ou senha inválidos!';
    }
}
?>

<form method="POST">
    <label for="username">Usuário:</label>
    <input type="text" name="username" required><br>
    <label for="password">Senha:</label>
    <input type="password" name="password" required><br>
    <button type="submit">Entrar</button>
</form>
