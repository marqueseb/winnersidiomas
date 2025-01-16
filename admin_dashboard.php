<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Aqui você pode consultar o banco de dados para listar os contratos e ver os formulários preenchidos pelos alunos
?>

<h1>Painel de Administração</h1>
<p>Bem-vindo, Admin!</p>

<!-- Exemplo de lista de contratos para visualização -->
<?php
// Aqui você pode buscar no banco os contratos para exibir
echo '<a href="processar_dono.php?contrato_id=123">Ver Formulário do Contrato 123</a>';
?>
