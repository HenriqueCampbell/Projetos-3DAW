/* ========================================== */
/* MOSTRAR/ESCONDER SENHA                  */
/* ========================================== */

const inputSenha = document.getElementById('senha');
const btnOlho = document.getElementById('btn-olho');

btnOlho.addEventListener('click', function() {
    if (inputSenha.type === 'password') {
        inputSenha.type = 'text';
        btnOlho.textContent = 'Esconder';
    } else {
        inputSenha.type = 'password';
        btnOlho.textContent = 'Mostrar';
    }
});

/* ========================================== */
/* ENVIO DO FORMULÁRIO DE LOGIN            */
/* ========================================== */

const formLogin = document.getElementById('form-login');

formLogin.addEventListener('submit', function(event) {
    event.preventDefault();

    const dadosLogin = new FormData(formLogin);

    fetch('../../backend/processa_login.php', {
        method: 'POST',
        body: dadosLogin
    })
    .then(resposta => resposta.json())
    .then(dados => {
        if (dados.sucesso) {
            alert("Bem-vindo(a) de volta!");
            window.location.href = "menu-do-usuario.html"; 
        } else {
            alert("Atenção: " + dados.mensagem);
        }
    })
    .catch(erro => {
        console.error("Erro na requisição:", erro);
        alert("Erro ao tentar conectar com o servidor. Tente novamente.");
    });
});