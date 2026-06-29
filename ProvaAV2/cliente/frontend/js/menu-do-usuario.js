document.addEventListener('DOMContentLoaded', () => {
    
    // Faz as buscas paralelas injetando as credenciais de cookie necessárias
    Promise.all([
        fetch('../../backend/obter_dados_usuario.php', { credentials: 'include' }).then(res => res.json()),
        fetch('../../backend/buscar_favoritos.php', { credentials: 'include' }).then(res => res.json()).catch(() => []),
        fetch('../dados/funcionarios.json').then(res => res.json())
    ])
    .then(([dadosUsuario, idsFavoritos, todosFuncionarios]) => {
        
        // Validação correta pelo nome que veio das tabelas do banco de dados
        if (!dadosUsuario || dadosUsuario.erro || !dadosUsuario.nome) {
            console.log("Sessão inválida ou erro no banco. Voltando pro Login...", dadosUsuario);
            window.location.href = 'login.html';
            return;
        }

        // 1. Preenche dados básicos e créditos reais do usuário
        const primeiroNome = dadosUsuario.nome.split(' ')[0];
        document.getElementById('nome-perfil').innerText = primeiroNome;
        document.getElementById('qtd-creditos').innerText = dadosUsuario.creditos;
        
        // 2. Atualiza os relógios dinâmicos da trilha de fidelidade
        atualizarTrilhaFidelidade(dadosUsuario.reservas_concluidas);

        // 3. Carrega o histórico de agendamentos do usuário
        carregarHistoricoAgendamentos();

        // 4. Monta o Grid de Favoritos em duas colunas com o efeito Glassmorphism
        const containerFavoritos = document.getElementById('container-favoritos');
        containerFavoritos.innerHTML = ''; 

        const favoritosFiltrados = todosFuncionarios.filter(f => 
            idsFavoritos.includes(Number(f.id)) || idsFavoritos.includes(String(f.id))
        );

        if (favoritosFiltrados.length === 0) {
            containerFavoritos.innerHTML = `
                <div class="estado-vazio">
                    <p style="margin: 0; font-size: 1.1rem; font-weight: 600;">Você não favoritou nenhum funcionário ainda.</p>
                    <a href="profissionais.html" class="btn-link-vazio">Conhecer Profissionais</a>
                </div>
            `;
        } else {
            favoritosFiltrados.forEach(f => {
                const card = document.createElement('div');
                card.className = 'card-favorito-figma';
                card.innerHTML = `
                    <div class="cabecalho-favorito">
                        <h3 class="nome-favorito">${f.nome}</h3>
                        <img src="../assets/imagens/estrela-cheia.png" alt="Remover Favorito" class="estrela-favorito" data-id="${f.id}">
                    </div>
                    <div class="corpo-favorito">
                        <img src="${f.foto}" alt="${f.nome}" class="foto-favorito">
                        <div class="detalhes-favorito">
                            <h4 class="cargo-favorito">${f.especialidade}</h4>
                            <p class="descricao-favorito">${f.descricao}</p>
                        </div>
                    </div>
                    <button class="btn-reservar-profissional" onclick="window.location.href='reservar.html?profissional=${f.id}'">Reservar com ${f.nome}</button>
                `;
                
                const estrela = card.querySelector('.estrela-favorito');
                estrela.addEventListener('click', (evento) => {
                    evento.stopPropagation();
                    console.log("Clique na estrela detectado! Tentando remover o funcionário ID:", f.id);
                    
                    card.classList.add('remover-puff');
                    
                    fetch('../../backend/remover_favorito.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `funcionario_id=${f.id}`,
                        credentials: 'include'
                    })
                    .then(res => res.json())
                    .then(resposta => {
                        if (resposta.sucesso) {
                            setTimeout(() => {
                                card.remove();
                                if (containerFavoritos.children.length === 0) {
                                    containerFavoritos.innerHTML = `
                                        <div class="estado-vazio">
                                            <p style="margin: 0; font-size: 1.1rem; font-weight: 600;">Você não favoritou nenhum funcionário ainda.</p>
                                            <a href="profissionais.html" class="btn-link-vazio">Conhecer Profissionais</a>
                                        </div>
                                    `;
                                }
                            }, 400);
                        } else {
                            card.classList.remove('remover-puff');
                            console.error("Erro ao remover favorito:", resposta.erro);
                        }
                    })
                    .catch(() => card.classList.remove('remover-puff'));
                });

                containerFavoritos.appendChild(card);
            });
        }
    })
    .catch(erro => {
        console.error("Erro geral na sincronização dos dados:", erro);
    });

    // Modal de Logout
    const linkSair = document.querySelector('.btn-sair');
    const modalLogout = document.getElementById('modal-logout');
    const btnConfirmarSair = document.getElementById('btn-confirmar-sair');
    const btnCancelarSair = document.getElementById('btn-cancelar-sair');

    if (linkSair && modalLogout) {
        linkSair.addEventListener('click', (evento) => {
            evento.preventDefault();
            modalLogout.style.display = 'flex';
        });
        btnCancelarSair.addEventListener('click', () => { modalLogout.style.display = 'none'; });
        btnConfirmarSair.addEventListener('click', () => { window.location.href = '../../backend/logout.php'; });
        modalLogout.addEventListener('click', (e) => { if (e.target === modalLogout) modalLogout.style.display = 'none'; });
    }
});

function atualizarTrilhaFidelidade(quantidadeConcluidas) {
    for (let i = 1; i <= 3; i++) {
        const blocoEtapa = document.getElementById(`etapa-${i}`);
        if (!blocoEtapa) continue;
        
        const imagem = blocoEtapa.querySelector('.img-etapa');
        const texto = blocoEtapa.querySelector('.txt-etapa');
        
        if (i <= quantidadeConcluidas) {
            blocoEtapa.classList.add('concluida');
            imagem.src = '../assets/imagens/icone-fidelidade-confirmada.png';
            text.innerText = `${i}ª Reserva Feita!`;
        } else {
            blocoEtapa.classList.remove('concluida');
            imagem.src = '../assets/imagens/icone-fidelidade-incompleta.png';
            texto.innerText = `${i}ª Reserva`;
        }
    }
    
    const etapa4 = document.getElementById('etapa-4');
    if (etapa4) {
        if (quantidadeConcluidas >= 4) {
            etapa4.classList.add('concluida');
        } else {
            etapa4.classList.remove('concluida');
        }
    }
}

function carregarHistoricoAgendamentos() {
    // 1. Carrega o JSON de serviços para traduzir os IDs numéricos em nomes de texto reais
    fetch('../dados/servicos.json')
        .then(res => res.json())
        .then(categorias => {
            const mapaServicos = {};
            categorias.forEach(cat => {
                cat.itens.forEach(item => {
                    mapaServicos[item.id] = item.nome;
                });
            });

            // 2. Busca os agendamentos reais do usuário logado no banco
            return fetch('../../backend/obter_agendamentos.php', { credentials: 'include' })
                .then(res => res.json())
                .then(dados => {
                    const containerReservas = document.getElementById('container-reservas');
                    if (!containerReservas) return;

                    // Se a requisição deu certo e tem registros no banco
                    if (dados.sucesso && dados.agendamentos && dados.agendamentos.length > 0) {
                        containerReservas.innerHTML = '';
                        
                        dados.agendamentos.forEach(reserva => {
                            // Quebra a string TIMESTAMP do banco (YYYY-MM-DD HH:MM:SS) em data e hora limpas
                            const partesDataHora = reserva.data_hora.split(' ');
                            const dataBanco = partesDataHora[0]; // YYYY-MM-DD
                            const horarioBanco = partesDataHora[1].substring(0, 5); // HH:MM

                            // Converte o formato do banco YYYY-MM-DD para o padrão visual DD/MM/YYYY
                            const dataFormatada = dataBanco.split('-').reverse().join('/');

                            // Transforma a lista de IDs de serviços vinda do banco em uma string de nomes reais do seu JSON
                            const nomesServicos = reserva.itens.map(it => mapaServicos[it.servico_id] || "Serviço").join(', ');

                            // Cria o card mantendo EXATAMENTE o seu estilo e tags originais do Figma!
                            const cardReserva = document.createElement('div');
                            cardReserva.style.width = '100%';
                            cardReserva.style.textAlign = 'left';
                            cardReserva.innerHTML = `
                                <span style="background: rgba(216,85,155,0.1); color:#D8559B; padding:2px 8px; border-radius:10px; font-size:0.8rem; font-weight:600;">Agendado</span>
                                <h4 style="color:#6C3A56; margin:8px 0 4px 0;">Forma de Pagamento: ${reserva.forma_pagamento.toUpperCase()}</h4>
                                <p style="color:#9B356F; font-size:0.9rem; margin:0 0 15px 0;">
                                    ${dataFormatada} às ${horarioBanco}<br>
                                    Serviços: ${nomesServicos}
                                </p>
                            `;
                            containerReservas.appendChild(cardReserva);
                        });
                        
                        // Injeta o seu botão original de Novo Agendamento no final de todas as listagens
                        const btnMais = document.createElement('button');
                        btnMais.className = 'btn-reservar-agora';
                        btnMais.innerText = 'Novo Agendamento';
                        btnMais.onclick = () => window.location.href='reservar.html';
                        containerReservas.appendChild(btnMais);
                    } else {
                        // Caso não encontre nenhum agendamento na tabela do banco
                        containerReservas.innerHTML = `
                            <p style="color:#9B356F; font-size:0.9rem; text-align:center; margin: 20px 0;">Você não possui nenhum agendamento ativo.</p>
                            <button class="btn-reservar-agora" onclick="window.location.href='reservar.html'">Reservar Agora</button>
                        `;
                    }
                });
        })
        .catch(err => console.error("Erro catastrófico ao sincronizar o histórico do menu:", err));
}