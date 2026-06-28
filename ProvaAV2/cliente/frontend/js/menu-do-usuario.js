document.addEventListener('DOMContentLoaded', () => {
    // Inicializa os carregamentos simultâneos do Banco e do JSON
    Promise.all([
        fetch('../../backend/obter_dados_usuario.php').then(res => res.json()),
        fetch('../../backend/buscar_favoritos.php').then(res => res.json()).catch(() => []),
        fetch('../dados/funcionarios.json').then(res => res.json())
    ])
    .then(([dadosUsuario, idsFavoritos, todosFuncionarios]) => {
        
        if (dadosUsuario.logado) {

            const primeiroNome = dadosUsuario.nome.split(' ')[0];
            document.getElementById('nome-perfil').innerText = primeiroNome;
            document.getElementById('qtd-creditos').innerText = dadosUsuario.creditos;
            
            // Atualiza a trilha de fidelidade com base nas reservas CONCLUÍDAS dele
            atualizarTrilhaFidelidade(dadosUsuario.reservas_concluidas);
            
            // Atualiza a seção de reservas ativas
            renderizarReservas(dadosUsuario.reservas_ativas);
        } else {
            window.location.href = 'login.html';
            return;
        }

        // Renderiza os Favoritos
        const containerFavoritos = document.getElementById('container-favoritos');
        containerFavoritos.innerHTML = ''; // Limpa o container

        // Filtra os funcionários que estão na lista de favoritados do banco
        const funcionariosFavoritados = todosFuncionarios.filter(f => 
            idsFavoritos.includes(Number(f.id)) || idsFavoritos.includes(String(f.id))
        );

        if (funcionariosFavoritados.length === 0) {
            // HTML do Estado Vazio se não tiver nenhum favoritado
            containerFavoritos.innerHTML = `
                <div class="estado-vazio">
                    <p style="margin: 0; font-size: 1.1rem; font-weight: 600;">Você não favoritou nenhum funcionário ainda.</p>
                    <a href="profissionais.html" class="btn-link-vazio">Conhecer Profissionais</a>
                </div>
            `;
        } else {
            // Renderiza os cards verticalizados em duas colunas
            funcionariosFavoritados.forEach(f => {
                const card = document.createElement('div');
                card.className = 'card-favorito-compacto';
                card.innerHTML = `
                    <img src="${f.foto}" alt="${f.nome}" class="foto-favorito-compacta">
                    <div class="info-favorito-compacta">
                        <h3 class="nome-favorito-compacto">${f.nome}</h3>
                        <span class="especialidade-favorito-compacto">${f.especialidade}</span>
                    </div>
                `;
                containerFavoritos.appendChild(card);
            });
        }
    })
    .catch(erro => {
        console.error("Erro ao carregar os dados do painel:", erro);
    });
});

function atualizarTrilhaFidelidade(quantidadeConcluidas) {
    const pontos = document.querySelectorAll('.trilha-fidelidade .ponto-trilha');
    
    pontos.forEach((ponto, index) => {
        // Remove as classes padrões do HTML para controlar via JS
        ponto.classList.remove('concluido', 'ativo');
        
        if (index < quantidadeConcluidas) {
            // Etapas passadas que já foram concluídas e usufruídas
            ponto.classList.add('concluido');
        } else if (index === quantidadeConcluidas) {
            // A etapa atual em que o usuário se encontra
            ponto.classList.add('ativo');
        }
    });
}

function renderizarReservas(reservasAtivas) {
    const containerReservas = document.getElementById('container-reservas');
    
    if (reservasAtivas && reservasAtivas.length > 0) {
        containerReservas.innerHTML = '';
        
        reservasAtivas.forEach(reserva => {
            const cardReserva = document.createElement('div');
            cardReserva.className = 'card-horizontal reserva-card';
            cardReserva.innerHTML = `
                <div class="detalhes-reserva">
                    <span class="status-reserva">Agendado</span>
                    <h4 class="profissional-reserva">Com ${reserva.profissional_nome}</h4>
                    <p class="data-hora-reserva">${reserva.data} às ${reserva.horario} - ${reserva.servico_nome}</p>
                </div>
                <button class="btn-reservar-agora" style="background-color: #6C3A56;">Ver Ticket</button>
            `;
            containerReservas.appendChild(cardReserva);
        });
    }
}