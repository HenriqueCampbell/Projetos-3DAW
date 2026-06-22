// ==========================================
// MOSTRAR E ESCONDER SENHA
// ==========================================

const inputSenha = document.getElementById('senha');
const btnOlho = document.getElementById('btn-olho');

btnOlho.addEventListener('click', function() {
    if (inputSenha.type === 'password') {
        inputSenha.type = 'text';
        btnOlho.textContent = 'Esconder'; // Muda o texto do botão para indicar que a senha está visível
    } else {
        inputSenha.type = 'password';
        btnOlho.textContent = 'Mostrar'; // Volta ao normal
    }
});

// ==========================================
// VALIDAÇÃO DO FORMULÁRIO DE CADASTRO
// ==========================================

const formCadastro = document.getElementById('form-cadastro');

formCadastro.addEventListener('submit', function(event) {
    
    // Pegando os valores que o usuário digitou
    const nome = document.getElementById('nome').value.trim();
    const sobrenome = document.getElementById('sobrenome').value.trim();
    const email = document.getElementById('email').value.trim();
    const telefone = document.getElementById('telefone').value.trim();

    // 1. REGEX: Definindo as regras
    // Aceita apenas letras (maiúsculas e minúsculas) e acentos
    const regexNome = /^[a-zA-ZÀ-ÿ\s]+$/; 
    
    // Verifica se tem texto, um '@', texto, um ponto, e texto no final
    const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; 
    
    // Aceita formatos: 21999999999, (21) 99999-9999, ou 21 999999999
    const regexTelefone = /^\(?\d{2}\)?\s?\d{4,5}-?\d{4}$/; 

    // 2. EXECUTANDO AS VALIDAÇÕES

    // Validando Nome e Sobrenome
    if (!regexNome.test(nome) || !regexNome.test(sobrenome)) {
        event.preventDefault(); // não deixa o form enviar
        alert("Erro: O nome e sobrenome não podem conter números ou símbolos especiais.");
        return;
    }

    // Validando E-mail
    if (!regexEmail.test(email)) {
        event.preventDefault();
        alert("Erro: Por favor, insira um e-mail válido (ex: seu@email.com).");
        return;
    }

    // Validando Telefone
    if (!regexTelefone.test(telefone)) {
        event.preventDefault();
        alert("Erro: Insira um telefone válido com DDD. Ex: (00) 00000-0000.");
        return;
    }

    // Validando tamanho da Senha
    const senha = document.getElementById('senha').value;
    if (senha.length < 6) {
        event.preventDefault();
        alert("Erro: A senha deve ter pelo menos 6 caracteres.");
        return;
    }

    event.preventDefault(); 

    // 3. PEGANDO E JUNTANDO EM UMA CONST OS DADOS DO FORMULÁRIO
    const dadosFormulario = new FormData(formCadastro);

    // 4. ENVIANDO PARA O PHP
    fetch('../../backend/processa_cadastro.php', {
        method: 'POST',
        body: dadosFormulario
    })
    .then(resposta => resposta.json())
    .then(dados => {
        // 5. RESPOSTA DO PHP
        if (dados.sucesso) {

            alert("Conta criada com sucesso! Bem-vindo(a), " + nome + ".");
            window.location.href = "login.html"; 

        } else {
            // Se o PHP barrar o cadastro por algum motivo, mostra a mensagem de erro que ele retornou
            alert("Atenção: " + dados.mensagem);
        }
    })
    .catch(erro => {
        // Se houver algum erro de conexão com o servidor, mostra no console e alerta o usuário
        console.error("Erro na requisição:", erro);
        alert("Erro fatal ao tentar conectar com o servidor. Tente novamente mais tarde.");
    });
});