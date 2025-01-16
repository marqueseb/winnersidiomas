// Função para validar CPF
function validarCPF(campo) {
  const cpf = campo.value.replace(/[^\d]+/g, ''); // Remove caracteres não numéricos
  const erro = document.getElementById("cpfErro");

  // Verifica se o CPF tem 11 dígitos
  if (cpf.length !== 11 || /^[0-9]+$/.test(cpf) === false || /^(\d)\1{10}$/.test(cpf)) {
    erro.style.display = "block"; // Exibe a mensagem de erro
    campo.setCustomValidity("CPF inválido.");
    campo.classList.remove("is-valid");
    campo.classList.add("is-invalid");
    return false;
  }

  // Validação do CPF (cálculo dos dígitos verificadores)
  let soma = 0;
  let resto;
  for (let i = 1; i <= 9; i++) {
    soma += parseInt(cpf.substring(i - 1, i)) * (11 - i);
  }
  resto = (soma * 10) % 11;
  if (resto === 10 || resto === 11) {
    resto = 0;
  }
  if (resto !== parseInt(cpf.substring(9, 10))) {
    erro.style.display = "block";
    campo.setCustomValidity("CPF inválido.");
    campo.classList.remove("is-valid");
    campo.classList.add("is-invalid");
    return false;
  }

  soma = 0;
  for (let i = 1; i <= 10; i++) {
    soma += parseInt(cpf.substring(i - 1, i)) * (12 - i);
  }
  resto = (soma * 10) % 11;
  if (resto === 10 || resto === 11) {
    resto = 0;
  }
  if (resto !== parseInt(cpf.substring(10, 11))) {
    erro.style.display = "block";
    campo.setCustomValidity("CPF inválido.");
    campo.classList.remove("is-valid");
    campo.classList.add("is-invalid");
    return false;
  }

  erro.style.display = "none"; // Esconde a mensagem de erro se CPF for válido
  campo.setCustomValidity(""); // Limpa o estado de erro personalizado
  campo.classList.remove("is-invalid");
  campo.classList.add("is-valid");
  return true;
}

// Função para validar o campo em tempo real
function validarCampo(input) {
  if (input.name === "cpfResponsavel") {
    // Valida CPF especificamente
    validarCPF(input);
  }

  if (input.checkValidity()) {
    input.classList.remove('is-invalid');
    input.classList.add('is-valid');
  } else {
    input.classList.remove('is-valid');
    input.classList.add('is-invalid');
  }
}

// Função para validar o formulário antes de enviar
function validarFormulario(event) {
  const campos = document.querySelectorAll('input, select');
  let formularioValido = true;

  campos.forEach((campo) => {
    // Verifica se o campo está vazio ou se contém a classe is-invalid
    if (campo.value === "" || campo.classList.contains("is-invalid")) {
      formularioValido = false;
      campo.classList.add("is-invalid"); // Marca o campo como inválido visualmente
    } else {
      campo.classList.remove("is-invalid"); // Remove a marcação de inválido se o campo for preenchido corretamente
    }
  });

  if (!formularioValido) {
    event.preventDefault(); // Impede o envio do formulário
    alert('Preencha todos os campos corretamente!');
  }
}

// Adiciona a validação em tempo real para cada campo
const campos = document.querySelectorAll('input, select');
campos.forEach((campo) => {
  campo.addEventListener('input', () => validarCampo(campo)); // Validação em tempo real
});

// Adiciona evento de submit
document.getElementById('formulario').addEventListener('submit', validarFormulario);

// Função para habilitar/desabilitar o botão de envio
function validarFormularioBotao() {
  const formulario = document.getElementById('formulario');
  const botao = document.querySelector('.btn');
  
  if (formulario.checkValidity()) {
    botao.disabled = false; // Habilita o botão se o formulário for válido
  } else {
    botao.disabled = true; // Desabilita o botão se o formulário for inválido
  }
}

// Valida o formulário sempre que houver um input
campos.forEach((campo) => {
  campo.addEventListener('input', validarFormularioBotao);
});
