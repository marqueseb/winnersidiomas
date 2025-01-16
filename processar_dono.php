<?php
session_start();

// Verifica se o usuário está logado como admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: login.php');
    exit();
}

// Captura o contrato_id da URL
if (isset($_GET['contrato_id'])) {
    $contrato_id = $_GET['contrato_id'];
} else {
    die("Erro: contrato_id não fornecido.");
}

// Conexão com o banco de dados
include('conexao.php');

// Consulta para verificar o status do cliente
$query = "SELECT * FROM contratos WHERE contrato_id = $1";
$result = pg_query_params($conn, $query, array($contrato_id));
$contrato = pg_fetch_assoc($result);

if ($contrato['status'] !== 'completo') {
    die("Erro: A parte do cliente não foi preenchida. O dono precisa aguardar o preenchimento completo.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Processa os dados preenchidos pelo dono
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

    // Valida os dados obrigatórios
    if (empty($inicioAulas) || empty($duracaoAula) || empty($modalidade) || empty($totalHorasAno) || empty($formaPagamento) || empty($diaVencimento)) {
        echo "<div class='alert alert-danger'>Erro: Todos os campos obrigatórios devem ser preenchidos!</div>";
        exit;
    }

    // Atualiza o contrato com as informações do dono
    $updateQuery = "UPDATE contratos 
                    SET inicioAulas = $1, 
                        duracaoAula = $2, 
                        modalidade = $3, 
                        totalHorasAno = $4, 
                        formaPagamento = $5, 
                        diaVencimento = $6, 
                        kitMaterial = $7, 
                        periodoKit = $8, 
                        taxaAdesao = $9, 
                        valorKit = $10, 
                        valorParcelas = $11, 
                        dataMatricula = $12, 
                        quantParcelas = $13, 
                        periodoContrato = $14, 
                        nomeAtendente = $15, 
                        status = 'finalizado' 
                    WHERE contrato_id = $16";

    // Execução da consulta com parâmetros
    $result = pg_query_params($conn, $updateQuery, array(
        $inicioAulas, $duracaoAula, $modalidade, $totalHorasAno, 
        $formaPagamento, $diaVencimento, $kitMaterial, $periodoKit, 
        $taxaAdesao, $valorKit, $valorParcelas, $dataMatricula, 
        $quantParcelas, $periodoContrato, $nomeAtendente, $contrato_id));

    if ($result) {
        echo "<div class='alert alert-success'>Contrato preenchido com sucesso!</div>";
        header("Location: sucesso.php?contrato_id=$contrato_id");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Erro ao atualizar o contrato: " . pg_last_error($conn) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formulário de Informações do Curso</title>
  <link rel="icon" type="image/png" href="imagens/logo-winners.webp">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .form-container {
      max-width: 800px;
      margin: 0 auto;
      background-color: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
      border: 1px solid #ddd;
    }
    .form-container h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    .form-section {
      margin-bottom: 20px;
      padding-bottom: 20px;
      border-bottom: 2px solid #007bff;
    }
    .form-section:last-child {
      border-bottom: none;
    }
    .logo-container {
      text-align: center;
      margin-bottom: 20px;
      padding-top: 20px;
    }
    .logo-container img {
      width: 100px;
      height: auto;
    }

    .btn {
      cursor: pointer;
      font-size: 16px;
      padding: 12px 24px;
      background-color: #007bff;
      position: relative;
      overflow: hidden;
    }

    .btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background-color: rgba(255, 255, 255, 0.3);
      transform: skewX(-45deg);
      transition: all 0.5s ease;
    }

    .btn:hover::before {
      left: 100%;
    }
  </style>
</head>
<body>

  <div class="container">
    <div class="logo-container">
      <img src="imagens/logo-winners.webp" alt="Logo">
    </div>

    <div class="form-container">
      <h2>Informações Sobre o Curso Contratado</h2>
      <form method="POST" action="processar_dono.php?contrato_id=<?php echo $contrato_id; ?>">
        <!-- Início das Aulas -->
        <div class="form-section">
          <div class="mb-3">
            <label for="inicioAulas" class="form-label">Data de Início</label>
            <input type="date" class="form-control" id="inicioAulas" name="inicioAulas" required>
          </div>
        </div>

        <!-- Duração da Aula -->
        <div class="form-section">
          <h4>Duração de Cada Aula</h4>
          <div class="mb-3">
            <input type="number" class="form-control" id="duracaoAula" name="duracaoAula" placeholder="Duração" required>
          </div>
        </div>

        <!-- Modalidade -->
        <div class="form-section">
          <h4>Modalidade do Curso</h4>
          <div class="mb-3">
            <select class="form-control" id="modalidade" name="modalidade" required>
              <option value="presencial">Turma Acadêmica</option>
              <option value="online">Vip Class</option>
            </select>
          </div>
        </div>

        <!-- Total de Horas por Ano -->
        <div class="form-section">
          <h4>Total de Horas de Curso por Ano</h4>
          <div class="mb-3">
            <input type="number" class="form-control" id="totalHorasAno" name="totalHorasAno" placeholder="Total de horas por ano" required>
          </div>
        </div>

        <h2>Acordo Financeiro e Observações Contratuais</h2>
        <div class="form-section">
          <h4>Forma de Pagamento</h4>
          <div class="mb-3">
            <select class="form-control" id="formaPagamento" name="formaPagamento" required>
              <option value="boleto">Boleto</option>
              <option value="cartao">Cartão</option>
              <option value="pix">PIX</option>
              <option value="cheque">Cheque a Prazo</option>
            </select>
          </div>
        </div>
  
        <!-- Dia do Vencimento -->
        <div class="form-section">
          <h4>Dia do Vencimento</h4>
          <div class="mb-3">
            <input type="number" class="form-control" id="diaVencimento" name="diaVencimento" placeholder="Exemplo: 15" required>
          </div>
        </div>
  
        <!-- Kit Material -->
        <div class="form-section">
          <h4>Kit Material Didático</h4>
          <div class="mb-3">
            <label>Incluso no Contrato?</label>
            <div>
              <input type="radio" id="kitSim" name="kitMaterial" value="sim" class="form-check-input" required>
              <label for="kitSim" class="form-check-label">Sim</label>
              <input type="radio" id="kitNao" name="kitMaterial" value="nao" class="form-check-input">
              <label for="kitNao" class="form-check-label">Não</label>
            </div>
          </div>
        </div>
  
        <div class="form-section">
          <h4>Observações do Contrato</h4>
          <div class="mb-3">
            <textarea class="form-control" id="periodoKit" name="periodoKit" rows="3"></textarea>
          </div>
        </div>

        <!-- Botão Enviar -->
        <div class="mb-3 text-center">
          <button type="submit" class="btn">Enviar</button>
        </div>
      </form>
    </div>
  </div>

</body>
</html>
