document.addEventListener('DOMContentLoaded', () => {
    const containerFuncionarios = document.getElementById('lista-funcionarios');

    fetch('../dados/funcionarios.json')
        .then(resposta => {
            if (!resposta.ok) {
                throw new Error('Erro ao carregar o arquivo JSON');
            }
            return resposta.json();
        })
        .then(funcionarios => {
            // Limpa o card de exemplo do HTML
            containerFuncionarios.innerHTML = '';

            // Spawna um card para cada funcionário
            funcionarios.forEach(profissional => {
                
                const card = document.createElement('div');
                card.className = 'cartao-funcionario';

                // Preenche o HTML interno do card injetando os dados do JSON
                card.innerHTML = `
                    <img src="../assets/imagens/estrela-vazia.png" alt="Favoritar" class="icone-favorito" data-id="${profissional.id}">
                    
                    <img src="${profissional.foto}" alt="${profissional.nome}" class="foto-funcionario">
                    
                    <div class="info-funcionario">
                        <div class="cabecalho-info">
                            <h3 class="nome-funcionario">${profissional.nome}</h3>
                            <span class="especialidade">${profissional.especialidade}</span>
                        </div>
                        
                        <p class="descricao">${profissional.descricao}</p>
                        
                        <button class="btn-reservar">Reservar Serviços com ${profissional.nome}</button>
                    </div>
                `;

                containerFuncionarios.appendChild(card);
            });

            ativarEstrelasFavoritos();
        })
        .catch(erro => {
            console.error("Erro ao renderizar profissionais:", erro);
            containerFuncionarios.innerHTML = '<p>Erro ao carregar a lista de profissionais. Tente novamente mais tarde.</p>';
        });
});


// Lógica para ativar o clique nas estrelas de favoritos e favoritar/desfavoritar os profissionais

function ativarEstrelasFavoritos() {
    const estrelas = document.querySelectorAll('.icone-favorito');

    estrelas.forEach(estrela => {
        estrela.addEventListener('click', function() {
            const idFuncionario = this.getAttribute('data-id');
            const estavaVazia = this.src.includes('estrela-vazia.png');
            
            // Troca a imagem da estrela no front-end (pra dar feedback pro usuário)
            if (estavaVazia) {
                this.src = '../assets/imagens/estrela-cheia.png'; 
            } else {
                this.src = '../assets/imagens/estrela-vazia.png';
            }

            // fetch leva a informação pro PHP em formato JSON
            fetch('../../backend/favoritar.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                // Empacota o ID que o PHP está esperando lá no $dados['id_funcionario']
                body: JSON.stringify({ id_funcionario: idFuncionario }) 
            })
            .then(resposta => resposta.json())
            .then(dados => {
                // 3. Se o PHP barrar (ex: não tem sessão/não tá logado)
                if (!dados.sucesso) {
                    
                    // Desfaz a troca da imagem porque a ação falhou
                    this.src = estavaVazia ? '../assets/imagens/estrela-vazia.png' : '../assets/imagens/estrela-cheia.png';
                    
                    alert(dados.mensagem);
                    
                    // Se o erro foi falta de login, já joga ele pra página de login
                    if (dados.mensagem.includes('logado')) {
                        window.location.href = 'login.html';
                    }
                } else {
                    // Registro no banco deu certo, então logar no console a ação realizada (favoritar ou desfavoritar)
                    console.log(`Sucesso no banco! Ação realizada: ${dados.acao}`);
                }
            })
            .catch(erro => {
                console.error("Erro na comunicação com o servidor:", erro);
                // Em caso de erro de rede, desfaz a imagem também
                this.src = estavaVazia ? '../assets/imagens/estrela-vazia.png' : '../assets/imagens/estrela-cheia.png';
            });
        });
    });
}