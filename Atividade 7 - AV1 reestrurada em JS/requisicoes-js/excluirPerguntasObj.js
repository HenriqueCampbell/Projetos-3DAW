document.querySelector('form').addEventListener('submit', function(event) {
    event.preventDefault();

    const id = document.querySelector('input[name="id"]').value;

    fetch('excluirPerguntasObj.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: id })
    })
    .then(resposta => resposta.json())
    .then(dados => {
        if(dados.status === 'sucesso') {
            alert(dados.mensagem);
            // Joga o usuário de volta pra listagem após apagar
            window.location.href = 'listarPerguntasObj.php'; 
        } else {
            alert('Erro ao excluir a pergunta.');
        }
    })
    .catch(erro => {
        console.error('Erro:', erro);
        alert('Erro na comunicação com o servidor.');
    });
});