document.addEventListener('DOMContentLoaded', () => {
    const containerFuncionarios = document.getElementById('lista-funcionarios');

    Promise.all([
        // res é a resposta da requisição, e res.json() transforma em objeto JS
        fetch('../dados/funcionarios.json').then(res => {
            if (!res.ok) throw new Error('Erro ao carregar o arquivo JSON');
            return res.json();
        }),
        fetch('../../backend/buscar_favoritos.php').then(res => {
            if (!res.ok) return []; // Se não estiver logado ou der erro, assume lista vazia
            return res.json();
        }).catch(() => [])
    ])
    .then(([funcionarios, idsFavoritos]) => {
        // Limpa o card de exemplo do HTML
        containerFuncionarios.innerHTML = '';

        // Spawna um card para cada funcionário
        funcionarios.forEach(profissional => {

            const ehFavorito = idsFavoritos.includes(Number(profissional.id)) || idsFavoritos.includes(String(profissional.id));
            
            // Escolhe a imagem da estrela certa com base na resposta do banco
            const imagemEstrela = ehFavorito ? '../assets/imagens/estrela-cheia.png' : '../assets/imagens/estrela-vazia.png';

            const card = document.createElement('div');
            card.className = 'cartao-funcionario';

            // Preenche o HTML interno injetando a estrela certa dinamicamente
            card.innerHTML = `
                <img src="${imagemEstrela}" alt="Favoritar" class="icone-favorito" data-id="${profissional.id}">
                
                <img src="${profissional.foto}" alt="${profissional.nome}" class="foto-funcionario">
                
                <div class="info-funcionario">
                    <div class="cabecalho-info">
                        <h3 class="nome-funcionario">${profissional.nome}</h3>
                        <span class="especialidade">${profissional.especialidade}</span>
                    </div>
                    
                    <p class="descricao">${profissional.descricao}</p>
                    
                    <button class="btn-reservar">Reservar com ${profissional.nome}</button>
                </div>
            `;

            containerFuncionarios.appendChild(card);
        });

        // Executa a função de cliques após renderizar todos os elementos na tela
        ativarEstrelasFavoritos();
    })
    .catch(erro => {
        console.error("Erro ao inicializar página:", erro);
        containerFuncionarios.innerHTML = '<p>Erro ao carregar a lista de profissionais. Tente novamente mais tarde.</p>';
    });
});

function ativarEstrelasFavoritos() {
    const estrelas = document.querySelectorAll('.icone-favorito');

    estrelas.forEach(estrela => {
        estrela.addEventListener('click', function() {
            const idFuncionario = this.getAttribute('data-id');
            // Busca o nome do funcionário que está no h3 do mesmo card
            const nomeFuncionario = this.closest('.cartao-funcionario').querySelector('.nome-funcionario').innerText;
            const estavaVazia = this.src.includes('estrela-vazia.png');
            
            if (estavaVazia) {
                this.src = '../assets/imagens/estrela-cheia.png'; 
            } else {
                this.src = '../assets/imagens/estrela-vazia.png';
            }

            fetch('../../backend/favoritar.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id_funcionario: idFuncionario }) 
            })
            .then(resposta => resposta.json())
            .then(dados => {
                if (!dados.sucesso) {
                    this.src = estavaVazia ? '../assets/imagens/estrela-vazia.png' : '../assets/imagens/estrela-cheia.png';
                    alert(dados.mensagem);
                    if (dados.mensagem.includes('logado')) {
                        window.location.href = 'login.html';
                    }
                } else {
                    console.log(`Sucesso no banco! Ação realizada: ${dados.acao}`);
                    
                    if (dados.acao === 'adicionado') {

                        const jaViuAviso = sessionStorage.getItem('aviso_favorito_visto');

                        if (!jaViuAviso) {
                            // Mensagem completa e explicativa aparece uma vez só por sessão
                            mostrarToast(`Você favoritou ${nomeFuncionario}! Confira seus profissionais favoritos na aba "Menu do Usuário".`, 5000);
                            sessionStorage.setItem('aviso_favorito_visto', 'true');
                        } else {
                            // Mensagem minimalista e rápida para as próximas vezes
                            mostrarToast(`⭐ ${nomeFuncionario} adicionado aos favoritos!`, 2500);
                        }
                    }
                }
            })
            .catch(erro => {
                console.error("Erro na comunicação com o servidor:", erro);
                this.src = estavaVazia ? '../assets/imagens/estrela-vazia.png' : '../assets/imagens/estrela-cheia.png';
            });
        });
    });
}

function mostrarToast(mensagem, tempo) {
    const toast = document.createElement('div');
    toast.className = 'toast-notificacao';
    toast.innerText = mensagem;
    
    document.body.appendChild(toast);

    // Remove a caixa depois do tempo acabar
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 500); // Remove do HTML após o efeito de sumir
    }, tempo);
}