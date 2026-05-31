document.querySelector('form').addEventListener('submit', function(event) {
    event.preventDefault();

    const id = document.querySelector('input[name="id"]').value;
    const pergunta = document.querySelector('textarea[name="pergunta"]').value;
    const resposta = document.querySelector('textarea[name="resposta"]').value;

    const dadosFormulario = { id, pergunta, resposta };

    fetch('editarPerguntasSub.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(dadosFormulario)
    })
    .then(respostaServidor => respostaServidor.json())
    .then(dados => {
        if(dados.status === 'sucesso') {
            alert(dados.mensagem);
            window.location.href = 'listarPerguntasSub.php'; 
        } else {
            alert('Erro ao atualizar a pergunta.');
        }
    })
    .catch(erro => {
        console.error('Erro:', erro);
        alert('Erro na comunicação com o servidor.');
    });
});