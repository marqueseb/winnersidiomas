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
    // Capturar os dados enviados pelo formulário e sanitizar
    $inicioAulas = htmlspecialchars($_POST['inicioAulas']);
    $duracaoAula = htmlspecialchars($_POST['duracaoAula']);
    $modalidade = htmlspecialchars($_POST['modalidade']);
    $totalHorasAno = htmlspecialchars($_POST['totalHorasAno']);
    $formaPagamento = htmlspecialchars($_POST['formaPagamento']);
    $diaVencimento = htmlspecialchars($_POST['diaVencimento']);
    $kitMaterial = htmlspecialchars($_POST['kitMaterial']);
    $periodoKit = htmlspecialchars($_POST['periodoKit']);
    $taxaAdesao = htmlspecialchars($_POST['taxaAdesao']);
    $valorKit = htmlspecialchars($_POST['valorKit']);
    $valorParcelas = htmlspecialchars($_POST['valorParcelas']);
    $dataMatricula = htmlspecialchars($_POST['dataMatricula']);
    $quantParcelas = htmlspecialchars($_POST['quantParcelas']);
    $periodoContrato = htmlspecialchars($_POST['periodoContrato']);
    $nomeAtendente = htmlspecialchars($_POST['nomeAtendente']);

    // Validar dados (exemplo simples de validação, você pode adicionar mais)
    if (empty($inicioAulas) || empty($duracaoAula) || empty($modalidade)) {
        die("Erro: Campos obrigatórios não foram preenchidos.");
    }

    // Conectar ao banco de dados (ajuste os parâmetros conforme necessário)
    $conn = new mysqli("localhost", "usuario", "senha", "banco");

    // Verificar conexão
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Preparar a consulta SQL com prepared statements
    $sql = "UPDATE contratos SET
        inicioAulas = ?, 
        duracaoAula = ?, 
        modalidade = ?, 
        totalHorasAno = ?, 
        formaPagamento = ?, 
        diaVencimento = ?, 
        kitMaterial = ?, 
        periodoKit = ?, 
        taxaAdesao = ?, 
        valorKit = ?, 
        valorParcelas = ?, 
        dataMatricula = ?, 
        quantParcelas = ?, 
        periodoContrato = ?, 
        nomeAtendente = ?
        WHERE contrato_id = ?";

    // Preparar o statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind dos parâmetros
        $stmt->bind_param("ssssssssssssssss", 
            $inicioAulas, 
            $duracaoAula, 
            $modalidade, 
            $totalHorasAno, 
            $formaPagamento, 
            $diaVencimento, 
            $kitMaterial, 
            $periodoKit, 
            $taxaAdesao, 
            $valorKit, 
            $valorParcelas, 
            $dataMatricula, 
            $quantParcelas, 
            $periodoContrato, 
            $nomeAtendente, 
            $contrato_id);

        // Executar a consulta
        if ($stmt->execute()) {
            echo "Contrato atualizado com sucesso!";
        } else {
            echo "Erro ao atualizar o contrato: " . $stmt->error;
        }

        // Fechar o statement
        $stmt->close();
    } else {
        echo "Erro ao preparar a consulta: " . $conn->error;
    }

    // Fechar a conexão com o banco de dados
    $conn->close();
}
?>
