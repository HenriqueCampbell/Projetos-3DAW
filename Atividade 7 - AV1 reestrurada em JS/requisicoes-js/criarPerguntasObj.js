document.querySelector('form').addEventListener('submit', function(event) {
    event.preventDefault();

    // Captura todos os campos mapeando pelos atributos "name" do HTML
    const id = document.querySelector('input[name="id"]').value;
    const pergunta = document.querySelector('textarea[name="pergunta"]').value;
    const r1 = document.querySelector('input[name="r1"]').value;
    const r2 = document.querySelector('input[name="r2"]').value;
    const r3 = document.querySelector('input[name="r3"]').value;
    const r4 = document.querySelector('input[name="r4"]').value;

    // Monta o objeto
    const dadosFormulario = { id, pergunta, r1, r2, r3, r4 };

    // Dispara para o PHP
    fetch('criarPerguntasObj.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(dadosFormulario)
    })
    .then(resposta => resposta.json())
    .then(dados => {
        if(dados.status === 'sucesso') {
            alert(dados.mensagem); // "Pergunta Criada com Sucesso"
            document.querySelector('form').reset(); // Limpa os campos
        } else {
            alert('Erro ao salvar a pergunta.');
        }
    })
    .catch(erro => {
        console.error('Erro:', erro);
        alert('Erro na comunicação com o servidor.');
    });
});