<?php
// processar_contrato_dono.php

// Captura o contrato_id da URL
if (isset($_GET['contrato_id'])) {
    $contrato_id = $_GET['contrato_id'];
} else {
    die("Erro: contrato_id não fornecido.");
}

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capturar os dados enviados pelo formulário
    $inicioAulas = $_POST['inicioAulas'];
    $duracaoAula = $_POST['duracaoAula'];
    $modalidade = $_POST['modalidade'];
    $totalHorasAno = $_POST['totalHorasAno'];
    $formaPagamento = $_POST['formaPagamento'];
    $diaVencimento = $_POST['diaVencimento'];
    $kitMaterial = $_POST['kitMaterial'];
    $periodoKit = $_POST['periodoKit'];
    $taxaAdesao = $_POST['taxaAdesao'];
    $valorKit = $_POST['valorKit'];
    $valorParcelas = $_POST['valorParcelas'];
    $dataMatricula = $_POST['dataMatricula'];
    $quantParcelas = $_POST['quantParcelas'];
    $periodoContrato = $_POST['periodoContrato'];
    $nomeAtendente = $_POST['nomeAtendente'];

    // Conectar ao banco de dados (ajuste os parâmetros conforme necessário)
    $conn = new mysqli("localhost", "usuario", "senha", "banco");

    // Verificar conexão
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Atualizar as informações do contrato no banco
    $sql = "UPDATE contratos SET
        inicioAulas = '$inicioAulas',
        duracaoAula = '$duracaoAula',
        modalidade = '$modalidade',
        totalHorasAno = '$totalHorasAno',
        formaPagamento = '$formaPagamento',
        diaVencimento = '$diaVencimento',
        kitMaterial = '$kitMaterial',
        periodoKit = '$periodoKit',
        taxaAdesao = '$taxaAdesao',
        valorKit = '$valorKit',
        valorParcelas = '$valorParcelas',
        dataMatricula = '$dataMatricula',
        quantParcelas = '$quantParcelas',
        periodoContrato = '$periodoContrato',
        nomeAtendente = '$nomeAtendente'
        WHERE contrato_id = '$contrato_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Contrato atualizado com sucesso!";
    } else {
        echo "Erro ao atualizar o contrato: " . $conn->error;
    }

    // Fechar a conexão com o banco de dados
    $conn->close();
}
?>
