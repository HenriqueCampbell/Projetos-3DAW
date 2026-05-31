document.addEventListener('DOMContentLoaded', function() {
    
    fetch('listarPerguntasSub.php?api=true')
    .then(resposta => resposta.json())
    .then(perguntas => {
        const corpoTabela = document.getElementById('corpo-tabela');
        const statusMsg = document.getElementById('status-msg');

        // Verifica se tem perguntas salvas
        if (perguntas.length === 0) {
            statusMsg.innerHTML = "<strong>Status:</strong> Nenhuma pergunta cadastrada.";
            return; 
        }

        // Passa por cada pergunta do JSON e monta a linha (tr)
        perguntas.forEach(pergunta => {
            const linha = document.createElement('tr');
            
            // Monta as colunas usando os nomes das variáveis do JSON
            linha.innerHTML = `
                <td>${pergunta.id}</td>
                <td>${pergunta.pergunta}</td>
                <td>${pergunta.resposta}</td>
                <td><a href='editarPerguntasSub.php?id=${pergunta.id}'>📝 Editar</a></td>
                <td><a href='excluirPerguntasSub.php?id=${pergunta.id}'>❌ Excluir</a></td>
            `;
            
            corpoTabela.appendChild(linha);
        });
        
        statusMsg.innerHTML = "<strong>Status:</strong> Leitura realizada com sucesso.";
    })
    .catch(erro => {
        console.error('Erro:', erro);
        document.getElementById('status-msg').innerHTML = "<strong>Status:</strong> Erro ao carregar perguntas.";
    });
});