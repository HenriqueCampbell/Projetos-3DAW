document.querySelector('form').addEventListener('submit', function(event) {
    event.preventDefault();

    const id = document.querySelector('input[name="id"]').value;
    const pergunta = document.querySelector('textarea[name="pergunta"]').value;
    const resposta = document.querySelector('textarea[name="resposta"]').value;

    const dadosFormulario = { id, pergunta, resposta };

    fetch('criarPerguntasSub.php', {
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
            document.querySelector('form').reset();
        } else {
            alert('Erro ao salvar a pergunta.');
        }
    })
    .catch(erro => {
        console.error('Erro:', erro);
        alert('Erro na comunicação com o servidor.');
    });
});