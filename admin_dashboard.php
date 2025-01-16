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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        h1, h2 {
            color: #333;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        a {
            color: #4CAF50;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            text-decoration: none;
            border-radius: 4px;
        }

        .btn:hover {
            background-color: #45a049;
        }

        .logout {
            margin-top: 20px;
            display: inline-block;
            padding: 10px 20px;
            background-color: #f44336;
            color: white;
            border-radius: 4px;
            text-decoration: none;
        }

        .logout:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Bem-vindo ao Painel de Controle do Admin!</h1>

        <h2>Contratos Preenchidos</h2>

        <table>
            <thead>
                <tr>
                    <th>Contrato ID</th>
                    <th>Nome Cliente</th>
                    <th>Status</th>
                    <th>Data de Preenchimento</th>
                    <th>Relatório do Cliente</th>
                    <th>Preencher Contrato</th>
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
                    // Link para visualizar o relatório preenchido pelo cliente
                    echo "<td><a href='relatorio_cliente.php?contrato_id=" . $row['contrato_id'] . "' class='btn'>Ver Relatório</a></td>";
                    // Link para o dono preencher o restante do contrato
                    echo "<td><a href='processar_dono.php?contrato_id=" . $row['contrato_id'] . "' class='btn'>Preencher</a></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <a href="logout.php" class="logout">Sair</a>
    </div>

</body>
</html>
