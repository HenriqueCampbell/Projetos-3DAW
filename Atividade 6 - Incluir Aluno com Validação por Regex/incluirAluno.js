document.getElementById('formIncluir').addEventListener('submit', function(event) {

    const matricula = document.getElementById('matricula').value.trim();
    const nome = document.getElementById('nome').value.trim();
    const email = document.getElementById('email').value.trim();

    // REGEX do e-mail (valida o formato usuario@dominio.com)
    const regexEmail = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    
    // Definindo REGEX da Matrícula (garante que só entrem números no txt)
    const regexMatricula = /^\d+$/;

    // Validação Básica: Campos vazios
    if (matricula === "" || nome === "" || email === "") {
        alert("Por favor, preencha todos os campos antes de enviar!");
        event.preventDefault();
        return;
    }

    // Validação da Matrícula
    if (!regexMatricula.test(matricula)) {
        alert("A matrícula deve conter apenas números!");
        event.preventDefault();
        return;
    }

    // Validação de E-mail com a Expressão Regular
    if (!regexEmail.test(email)) {
        alert("Por favor, insira um e-mail válido!");
        event.preventDefault(); // Barra o envio e mantém o usuário na página
        return;
    }
});