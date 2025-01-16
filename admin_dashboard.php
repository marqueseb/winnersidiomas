<?php
session_start();

// Verifica se o usuário está logado como admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: login.php');
    exit();
}

// Conectar ao banco de dados PostgreSQL
include('conexao.php'); // Conexão com o banco de dados

// Consulta para pegar todos os contratos com status 'pendente' ou 'completo'
$sql = "SELECT * FROM contratos WHERE status IN ('pendente', 'completo')";
$result = pg_query($conn, $sql);

if (!$result) {
    echo "Erro na consulta: " . pg_last_error($conn);
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Controle do Admin</title>
</head>
<body>
    <h1>Bem-vindo ao Painel de Controle do Admin!</h1>

    <h2>Contratos Preenchidos</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Contrato ID</th>
                <th>Nome Cliente</th>
                <th>Status</th>
                <th>Data de Preenchimento</th>
                <th>Detalhes</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Exibir as linhas de contratos
            while ($row = pg_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['contrato_id'] . "</td>";
                echo "<td>" . $row['nome_cliente'] . "</td>";
                echo "<td>" . $row['status'] . "</td>";
                echo "<td>" . $row['data_preenchimento'] . "</td>";
                // Link para o dono completar o formulário
                echo "<td><a href='processar_dono.php?contrato_id=" . $row['contrato_id'] . "'>Preencher</a></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <a href="logout.php">Sair</a>
</body>
</html>
