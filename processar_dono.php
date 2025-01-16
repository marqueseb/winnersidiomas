<?php
// processar_dono.php

// Captura o contrato_id da URL
if (isset($_GET['contrato_id'])) {
    $contrato_id = $_GET['contrato_id'];
} else {
    die("Erro: contrato_id não fornecido.");
}

// Simulando a verificação do preenchimento da parte do cliente no banco de dados
// Aqui você deve fazer uma consulta real ao banco de dados para verificar se o cliente preencheu os dados
$clientePreenchido = true; // Defina isso com base no banco de dados

// Se o cliente não preencheu a parte dele, exibe uma mensagem de erro.
if (!$clientePreenchido) {
    die("Erro: A parte do cliente não foi preenchida. O dono precisa aguardar o preenchimento completo.");
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
      <form method="POST" action="processar_contrato_dono.php?contrato_id=<?php echo $contrato_id; ?>">
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
            </div>
            <div>
              <input type="radio" id="kitNao" name="kitMaterial" value="nao" class="form-check-input" required>
              <label for="kitNao" class="form-check-label">Não</label>
            </div>
          </div>
          <div class="mb-3">
            <label for="periodoKit" class="form-label">Período do Kit Material Didático</label>
            <input type="text" class="form-control" id="periodoKit" name="periodoKit" placeholder="Exemplo: Jan/25 a Dez/25" required>
          </div>
        </div>
  
        <!-- Taxa de Adesão -->
        <div class="form-section">
          <h4>Taxa de Adesão</h4>
          <div class="mb-3">
            <input type="text" class="form-control" id="taxaAdesao" name="taxaAdesao" placeholder="Exemplo: R$ 200,00" required>
          </div>
        </div>
  
        <!-- Valores do Kit e Parcelas -->
        <div class="form-section">
          <h4>Valores</h4>
          <div class="mb-3">
            <label for="valorKit" class="form-label">Valor Total do Kit Anual de Material Didático</label>
            <input type="text" class="form-control" id="valorKit" name="valorKit" placeholder="Exemplo: R$ 800,00" required>
          </div>
          <div class="mb-3">
            <label for="valorParcelas" class="form-label">Valor de Cada Parcela do Curso (Sem o Kit)</label>
            <input type="text" class="form-control" id="valorParcelas" name="valorParcelas" placeholder="Exemplo: R$ 300,00" required>
          </div>
        </div>
  
        <!-- Data da Matrícula -->
        <div class="form-section">
          <h4>Data da Matrícula</h4>
          <div class="mb-3">
            <input type="date" class="form-control" id="dataMatricula" name="dataMatricula" required>
          </div>
        </div>
  
        <!-- Parcelamento -->
        <div class="form-section">
          <h4>Parcelamento</h4>
          <div class="mb-3">
            <label for="quantParcelas" class="form-label">Quantidade de Parcelas</label>
            <input type="number" class="form-control" id="quantParcelas" name="quantParcelas" placeholder="Exemplo: 12" required>
          </div>
        </div>
  
        <!-- Vigência do Contrato -->
        <div class="form-section">
          <h4>Vigência do Contrato</h4>
          <div class="mb-3">
            <label for="periodoContrato" class="form-label">Período</label>
            <input type="text" class="form-control" id="periodoContrato" name="periodoContrato" placeholder="Exemplo: Jan/25 a Dez/26" required>
          </div>
        </div>
  
        <!-- Nome do Atendente -->
        <div class="form-section">
          <h4>Nome do Atendente</h4>
          <div class="mb-3">
            <input type="text" class="form-control" id="nomeAtendente" name="nomeAtendente" required>
          </div>
        </div>

        <div class="d-flex justify-content-center">
          <button type="submit" class="btn btn-primary">Enviar</button>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
