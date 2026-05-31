document.querySelector('form').addEventListener('submit', function(event) {
    event.preventDefault();

    // Pega o ID que está bloqueado no campo de texto
    const id = document.querySelector('input[name="id"]').value;

    fetch('excluirPerguntasSub.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: id }) // Manda só o ID para o PHP excluir a pergunta.
    })
    .then(resposta => resposta.json())
    .then(dados => {
        if(dados.status === 'sucesso') {
            alert(dados.mensagem);
            // Redireciona de volta para a tabela
            window.location.href = 'listarPerguntasSub.php'; 
        } else {
            alert('Erro ao excluir a pergunta.');
        }
    })
    .catch(erro => {
        console.error('Erro:', erro);
        alert('Erro na comunicação com o servidor.');
    });
});