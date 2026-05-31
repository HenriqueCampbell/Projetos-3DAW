// Ao terminar de carregar o HTML, dispara a função para buscar as perguntas
document.addEventListener('DOMContentLoaded', function() {
    
    // Faz o fetch passando o ?api=true
    fetch('listarPerguntasObj.php?api=true')
    .then(resposta => resposta.json())
    .then(perguntas => {
        const corpoTabela = document.getElementById('corpo-tabela');
        const statusMsg = document.getElementById('status-msg');

        // Se o banco estiver vazio, avisa o usuário
        if (perguntas.length === 0) {
            statusMsg.innerHTML = "<strong>Status:</strong> Nenhuma pergunta cadastrada.";
            return; 
        }

        // Para cada pergunta que o PHP mandou, cria uma linha de tabela
        perguntas.forEach(pergunta => {
            const linha = document.createElement('tr');
            
            linha.innerHTML = `
                <td>${pergunta.id}</td>
                <td>${pergunta.pergunta}</td>
                <td>${pergunta.r1}</td>
                <td>${pergunta.r2}</td>
                <td>${pergunta.r3}</td>
                <td>${pergunta.r4}</td>
                <td><a href='editarPerguntasObj.php?id=${pergunta.id}'>📝 Editar</a></td>
                <td><a href='excluirPerguntasObj.php?id=${pergunta.id}'>❌ Excluir</a></td>
            `;
            
            // Adiciona a linha pronta dentro do <tbody>
            corpoTabela.appendChild(linha);
        });

        statusMsg.innerHTML = "<strong>Status:</strong> Leitura realizada com sucesso.";
    })
    .catch(erro => {
        console.error('Erro:', erro);
        document.getElementById('status-msg').innerHTML = "<strong>Status:</strong> Erro ao carregar perguntas.";
    });
});