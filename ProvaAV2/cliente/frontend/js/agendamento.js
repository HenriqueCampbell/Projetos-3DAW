document.addEventListener('DOMContentLoaded', () => {
    // 1. Barreira de segurança contra acesso direto pela URL
    const carrinhoAtivo = localStorage.getItem('carrinho_ativo');
    if (!carrinhoAtivo) {
        alert("Nenhum serviço selecionado! Selecione os serviços primeiro.");
        window.location.href = 'reservar.html';
        return;
    }

    const carrinho = JSON.parse(carrinhoAtivo);
    
    // Configurações de Data Inicial (Junho de 2026 baseado no seu Figma)
    let anoAtual = 2026;
    let mesAtual = 5; // Junho (Índice 0-indexed: 0=Jan, 5=Jun)
    const nomesMeses = ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"];

    // Executa as cargas de interface
    buscarDadosUsuarioSessao();
    calcularDuraçãoCarrinho();
    renderizarListaServicosEsquerda();
    inicializarCalendario();

    // 2. Resgata o primeiro nome do usuário real logado no banco
    function buscarDadosUsuarioSessao() {
        fetch('../../backend/obter_dados_usuario.php', { credentials: 'include' })
            .then(res => res.json())
            .then(dados => {
                if (dados && dados.nome) {
                    const primeiroNome = dados.nome.split(' ')[0];
                    document.getElementById('nome-cliente-resumo').innerText = primeiroNome;
                } else {
                    document.getElementById('nome-cliente-resumo').innerText = "Cliente";
                }
            })
            .catch(() => {
                document.getElementById('nome-cliente-resumo').innerText = "Cliente";
            });
    }

    // 3. Busca no servicos.json os minutos de cada item para somar e formatar
    function calcularDuraçãoCarrinho() {
        fetch('../dados/servicos.json')
            .then(res => res.json())
            .then(categorias => {
                let totalMinutos = 0;
                
                // Mapeia os IDs do carrinho com as durações reais do JSON
                carrinho.forEach(itemCarrinho => {
                    categorias.forEach(cat => {
                        const correspondente = cat.itens.find(i => i.id === itemCarrinho.id);
                        if (correspondente) {
                            totalMinutos += correspondente.duracao_minutos;
                        }
                    });
                });

                // Formata matematicamente para o padrão XhXX
                const horas = Math.floor(totalMinutos / 60);
                const minutos = totalMinutos % 60;
                const minutosFormatados = minutos < 10 ? `0${minutos}` : minutos;
                
                document.getElementById('tempo-total-estimado').innerText = `${horas}h${minutosFormatados}`;
            });
    }

    // 4. Desenha a listagem com o ✔
    function renderizarListaServicosEsquerda() {
        const container = document.getElementById('lista-servicos-confirmados');
        container.innerHTML = '';
        
        carrinho.forEach(item => {
            const div = document.createElement('div');
            div.className = 'item-check-confirmado';
            div.innerHTML = `<span>✓</span> ${item.nome}`;
            container.appendChild(div);
        });
    }

    // 5. Montagem do Calendário Dinâmico
    function inicializarCalendario() {
        atualizarLabelMesAno();
        renderizarDiasMes();

        document.getElementById('btn-mes-anterior').onclick = () => { mesAtual--; if(mesAtual < 0){ mesAtual=11; anoAtual--; } atualizarCalendario(); };
        document.getElementById('btn-mes-proximo').onclick = () => { mesAtual++; if(mesAtual > 11){ mesAtual=0; anoAtual++; } atualizarCalendario(); };
    }

    function atualizarCalendario() {
        atualizarLabelMesAno();
        renderizarDiasMes();
    }

    function atualizarLabelMesAno() {
        document.getElementById('nome-mes-ano-atual').innerText = `${nomesMeses[mesAtual]} ${anoAtual}`;
    }

    function renderizarDiasMes() {
        const containerDias = document.getElementById('dias-numericos-container');
        containerDias.innerHTML = '';

        const primeiroDiaSemana = new Date(anoAtual, mesAtual, 1).getDay();
        const totalDiasNoMes = new Date(anoAtual, mesAtual + 1, 0).getDate();

        // Injeta os blocos vazios antes do dia 1 do mês iniciar
        for (let i = 0; i < primeiroDiaSemana; i++) {
            const vazio = document.createElement('div');
            containerDias.appendChild(vazio);
        }

        // Desenha os dias reais
        for (let dia = 1; dia <= totalDiasNoMes; dia++) {
            const divDia = document.createElement('div');
            divDia.className = 'dia-numero';
            divDia.innerText = dia;

            const dataObjeto = new Date(anoAtual, mesAtual, dia);
            
            // Regra: Domingo é marcado com classe especial de cor
            if (dataObjeto.getDay() === 0) {
                divDia.classList.add('domingo');
                divDia.onclick = () => mostrarToast("Não abrimos aos domingos.");
            } else {
                divDia.onclick = () => abrirSelecaoHorarios(dia, dataObjeto);
            }

            containerDias.appendChild(divDia);
        }
    }

    // 6. Efeito transição e renderização da Parte 2 (Horários)
    function abrirSelecaoHorarios(diaNum, dataObjeto) {
        // Marca o dia selecionado visualmente no calendário
        document.querySelectorAll('.dia-numero').forEach(d => d.classList.remove('selecionado'));
        event.target.classList.add('selecionado');

        const opcoesFormatacao = { weekday: 'long', day: 'numeric', month: 'long' };
        let dataString = dataObjeto.toLocaleDateString('pt-BR', opcoesFormatacao);
        dataString = dataString.charAt(0).toUpperCase() + dataString.slice(1); // Capitaliza

        document.getElementById('label-data-selecionada').innerText = dataString;

        // Horários Comerciais Sortidos
        const horariosDisponiveis = ["09:00", "10:30", "12:00", "14:00", "16:30", "18:00"];
        const containerHorarios = document.getElementById('container-lista-horarios');
        containerHorarios.innerHTML = '';

        horariosDisponiveis.forEach(hora => {
            const btn = document.createElement('button');
            btn.className = 'btn-horario-opcao';
            btn.innerText = hora;

            // Regra dos horários concorridos (Injeta a exclamação com o seu asset)
            if (hora === "12:00" || hora === "14:00") {
                btn.innerHTML = `${hora} <img src="../assets/imagens/icone-exclamacao.png" class="badge-concorrido" alt="Concorrido">`;
            }

            btn.onclick = () => realizarAgendamentoFinal(dataObjeto, hora);
            containerHorarios.appendChild(btn);
        });

        // Alterna dinamicamente a visualização da coluna direita para a etapa 2
        document.getElementById('etapa-calendario').classList.add('oculto');
        document.getElementById('etapa-horarios').classList.remove('oculto');
    }

    function realizarAgendamentoFinal(dataObjeto, hora) {
        mostrarToast(`Horário de ${hora} selecionado! Processando reserva...`);
        // Próximo passo lógico: Enviar pacotaço para a tela de meio de pagamento!
    }

    function mostrarToast(mensagem) {
        const container = document.getElementById('container-toasts');
        const toast = document.createElement('div');
        toast.className = 'toast-notificacao';
        toast.innerText = mensagem;
        container.appendChild(toast);
        setTimeout(() => { toast.remove(); }, 3000);
    }
});