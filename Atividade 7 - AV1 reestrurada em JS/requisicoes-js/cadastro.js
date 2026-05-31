document.querySelector('form').addEventListener('submit', function(event) {
    event.preventDefault(); // Para o comportamento padrão do HTML de recarregar a página inteira

    // Pega os valores da tela mapeando pelos atributos "name" do HTML
    const nome = document.querySelector('input[name="nome"]').value;
    const email = document.querySelector('input[name="email"]').value;
    const telefone = document.querySelector('input[name="telefone"]').value;

    const dadosFormulario = {
        nome: nome,
        email: email,
        telefone: telefone
    };

    // fetch enviando o objeto transformado em string JSON para o PHP
    fetch('cadastro.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(dadosFormulario)
    })
    .then(resposta => {
        if (!resposta.ok) throw new Error('Erro na resposta do servidor');
        return resposta.json();
    })
    .then(dados => {
        if(dados.status === 'sucesso') {
            alert(dados.mensagem);
            document.querySelector('form').reset(); // Limpa a tela
        } else {
            alert('Erro ao salvar os dados.');
        }
    })
    .catch(erro => {
        console.error('Erro:', erro);
        alert('Erro na comunicação com o servidor.');
    });
});