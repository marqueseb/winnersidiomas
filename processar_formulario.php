<?php
echo "O formulário foi enviado!";

// Configuração de conexão com o banco de dados PostgreSQL
$host = 'localhost';
$dbname = 'sistema_contratos';
$username = 'winners'; // O nome do usuário PostgreSQL
$password = '123456789'; // A senha do usuário 'winners'

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}

// Função de validação do CPF
function validarCpf($cpf) {
    // Verifica se o CPF tem 11 dígitos e não é uma sequência repetitiva
    if (preg_match('/^\d{11}$/', $cpf) && !preg_match('/(\d)\1{10}/', $cpf)) {
        $cpf = array_map('intval', str_split($cpf));

        // Validação dos dois dígitos verificadores
        for ($i = 0, $soma = 0; $i < 9; $i++) {
            $soma += $cpf[$i] * (10 - $i);
        }
        $resto = $soma % 11;
        if ($resto < 2) {
            $digito1 = 0;
        } else {
            $digito1 = 11 - $resto;
        }

        for ($i = 0, $soma = 0; $i < 10; $i++) {
            $soma += $cpf[$i] * (11 - $i);
        }
        $resto = $soma % 11;
        if ($resto < 2) {
            $digito2 = 0;
        } else {
            $digito2 = 11 - $resto;
        }

        return $cpf[9] == $digito1 && $cpf[10] == $digito2;
    }
    return false;
}

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Limpeza e sanitização dos dados recebidos
    $nomeAluno = filter_input(INPUT_POST, 'nomeAluno', FILTER_SANITIZE_STRING);
    $dataNascimentoAluno = filter_input(INPUT_POST, 'dataNascimentoAluno', FILTER_SANITIZE_STRING);
    $idadeAluno = filter_input(INPUT_POST, 'idadeAluno', FILTER_SANITIZE_NUMBER_INT);
    $nomeResponsavel = filter_input(INPUT_POST, 'nomeResponsavel', FILTER_SANITIZE_STRING);
    $grauParentesco = filter_input(INPUT_POST, 'grauParentesco', FILTER_SANITIZE_STRING);
    $profissaoResponsavel = filter_input(INPUT_POST, 'profissaoResponsavel', FILTER_SANITIZE_STRING);
    $cepResponsavel = filter_input(INPUT_POST, 'cepResponsavel', FILTER_SANITIZE_STRING);
    $enderecoResponsavel = filter_input(INPUT_POST, 'enderecoResponsavel', FILTER_SANITIZE_STRING);
    $cpfResponsavel = filter_input(INPUT_POST, 'cpfResponsavel', FILTER_SANITIZE_STRING);
    $dataNascimentoResponsavel = filter_input(INPUT_POST, 'dataNascimentoResponsavel', FILTER_SANITIZE_STRING);
    $telefoneResponsavel = filter_input(INPUT_POST, 'telefoneResponsavel', FILTER_SANITIZE_STRING);
    $telefoneAluno = filter_input(INPUT_POST, 'telefoneAluno', FILTER_SANITIZE_STRING);
    $vencimentoPadrao = filter_input(INPUT_POST, 'vencimentoPadrao', FILTER_SANITIZE_STRING);
    $alterarVencimento = isset($_POST['alterarVencimento']) ? $_POST['alterarVencimento'] : 'nao';
    $novoVencimento = isset($_POST['novoVencimento']) ? filter_input(INPUT_POST, 'novoVencimento', FILTER_SANITIZE_STRING) : null;
    $diasCurso = implode(",", array_filter([$_POST['segunda'], $_POST['terca'], $_POST['quarta'], $_POST['quinta'], $_POST['sexta'], $_POST['sabado'], $_POST['domingo']]));
    $turno = filter_input(INPUT_POST, 'turno', FILTER_SANITIZE_STRING);
    $idioma = filter_input(INPUT_POST, 'idioma', FILTER_SANITIZE_STRING);
    $vipClass = filter_input(INPUT_POST, 'vipClass', FILTER_SANITIZE_STRING);
    $modalidadeEscolhida = filter_input(INPUT_POST, 'modalidadeEscolhida', FILTER_SANITIZE_STRING);

    // Verificar se os campos obrigatórios estão preenchidos
    $camposObrigatorios = [
        'nomeAluno', 'dataNascimentoAluno', 'idadeAluno', 'nomeResponsavel', 'grauParentesco', 
        'profissaoResponsavel', 'cepResponsavel', 'enderecoResponsavel', 'cpfResponsavel', 
        'dataNascimentoResponsavel', 'telefoneResponsavel', 'telefoneAluno', 'vencimentoPadrao', 
        'diasCurso', 'turno', 'idioma', 'vipClass', 'modalidadeEscolhida'
    ];

    foreach ($camposObrigatorios as $campo) {
        if (empty($_POST[$campo])) {
            die("Erro: O campo $campo é obrigatório.");
        }
    }

    // Validar CPF
    if (!validarCpf($cpfResponsavel)) {
        die("Erro: CPF inválido.");
    }

    try {
        $pdo->beginTransaction();

        // Inserir dados na tabela 'clientes'
        $sql = "INSERT INTO clientes (nome, data_nascimento, idade) VALUES (:nome, :data_nascimento, :idade)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nome' => $nomeAluno,
            'data_nascimento' => $dataNascimentoAluno,
            'idade' => $idadeAluno
        ]);
        
        // Obter o id do cliente inserido
        $clienteId = $pdo->lastInsertId();

        // Inserir dados na tabela 'contratos'
        $sql = "INSERT INTO contratos (cliente_id, nome_responsavel, grau_parentesco, profissao_responsavel, cep_responsavel, endereco_responsavel, cpf_responsavel, data_nascimento_responsavel, telefone_responsavel, telefone_aluno, vencimento_padrao, alterar_vencimento, novo_vencimento, dias_curso, turno, idioma, vip_class, modalidade_escolhida) 
                VALUES (:cliente_id, :nome_responsavel, :grau_parentesco, :profissao_responsavel, :cep_responsavel, :endereco_responsavel, :cpf_responsavel, :data_nascimento_responsavel, :telefone_responsavel, :telefone_aluno, :vencimento_padrao, :alterar_vencimento, :novo_vencimento, :dias_curso, :turno, :idioma, :vip_class, :modalidade_escolhida)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'cliente_id' => $clienteId,
            'nome_responsavel' => $nomeResponsavel,
            'grau_parentesco' => $grauParentesco,
            'profissao_responsavel' => $profissaoResponsavel,
            'cep_responsavel' => $cepResponsavel,
            'endereco_responsavel' => $enderecoResponsavel,
            'cpf_responsavel' => $cpfResponsavel,
            'data_nascimento_responsavel' => $dataNascimentoResponsavel,
            'telefone_responsavel' => $telefoneResponsavel,
            'telefone_aluno' => $telefoneAluno,
            'vencimento_padrao' => $vencimentoPadrao,
            'alterar_vencimento' => $alterarVencimento,
            'novo_vencimento' => $novoVencimento,
            'dias_curso' => $diasCurso,
            'turno' => $turno,
            'idioma' => $idioma,
            'vip_class' => $vipClass,
            'modalidade_escolhida' => $modalidadeEscolhida
        ]);

        $pdo->commit();

        echo "Formulário enviado com sucesso!";
    } catch (PDOException $e) {
        $pdo->rollBack();
        die("Erro ao enviar o formulário: " . $e->getMessage());
    }
}
?>
