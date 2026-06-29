document.addEventListener('DOMContentLoaded', () => {
    const carrinhoAtivo = localStorage.getItem('carrinho_ativo');
    if (!carrinhoAtivo) {
        alert("Nenhum serviço selecionado! Selecione os serviços primeiro.");
        window.location.href = 'reservar.html';
        return;
    }

    const carrinho = JSON.parse(carrinhoAtivo);
    
    // Data base para o agendamento 
    const hoje = new Date();
    let anoAtual = hoje.getFullYear();
    let mesAtual = hoje.getMonth(); 
    const nomesMeses = ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"];

    buscarDadosUsuarioSessao();
    calcularDuraçãoCarrinho();
    renderizarListaServicosEsquerda();
    inicializarCalendario();

    // Ouvinte para voltar dos horários para o calendário
    document.getElementById('btn-voltar-calendario').onclick = () => {
        document.getElementById('etapa-horarios').classList.add('oculto');
        document.getElementById('etapa-calendario').classList.remove('oculto');
    };

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
            }).catch(() => { document.getElementById('nome-cliente-resumo').innerText = "Cliente"; });
    }

    function calcularDuraçãoCarrinho() {
        fetch('../dados/servicos.json')
            .then(res => res.json())
            .then(categorias => {
                let totalMinutos = 0;
                carrinho.forEach(itemCarrinho => {
                    categorias.forEach(cat => {
                        const correspondente = cat.itens.find(i => i.id === itemCarrinho.id);
                        if (correspondente) totalMinutos += correspondente.duracao_minutos;
                    });
                });
                const horas = Math.floor(totalMinutos / 60);
                const minutos = totalMinutos % 60;
                document.getElementById('tempo-total-estimado').innerText = `${horas}h${minutos < 10 ? '0' : ''}${minutos}`;
            });
    }

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

    function inicializarCalendario() {
        atualizarLabelMesAno();
        renderizarDiasMes();

        document.getElementById('btn-mes-anterior').onclick = () => { 
            mesAtual--; 
            if(mesAtual < 0){ mesAtual=11; anoAtual--; } 
            atualizarCalendario(); 
        };
        document.getElementById('btn-mes-proximo').onclick = () => { 
            mesAtual++; 
            if(mesAtual > 11){ mesAtual=0; anoAtual++; } 
            atualizarCalendario(); 
        };
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

        for (let i = 0; i < primeiroDiaSemana; i++) {
            const vazio = document.createElement('div');
            containerDias.appendChild(vazio);
        }

        // Criamos uma estampa da data de hoje "zerada" para comparar apenas os dias
        const hojeZerado = new Date(hoje.getFullYear(), hoje.getMonth(), hoje.getDate());

        for (let dia = 1; dia <= totalDiasNoMes; dia++) {
            const divDia = document.createElement('div');
            divDia.className = 'dia-numero';
            divDia.innerText = dia;

            const dataObjeto = new Date(anoAtual, mesAtual, dia);

            // Regra 1: Bloquear e deixar cinza dias que já passaram
            if (dataObjeto < hojeZerado) {
                divDia.classList.add('inativo');
            } 
            // Regra 2: Marcar domingos
            else if (dataObjeto.getDay() === 0) {
                divDia.classList.add('domingo');
                divDia.onclick = () => mostrarToast("Não abrimos aos domingos.");
            } 
            // Regra 3: Dia válido futuro ou presente
            else {
                divDia.onclick = (e) => abrirSelecaoHorarios(dia, dataObjeto, e.target);
            }

            containerDias.appendChild(divDia);
        }
    }

    function abrirSelecaoHorarios(diaNum, dataObjeto, elementoClicado) {
        document.querySelectorAll('.dia-numero').forEach(d => d.classList.remove('selecionado'));
        elementoClicado.classList.add('selecionado');

        const opcoesFormatacao = { weekday: 'long', day: 'numeric', month: 'long' };
        let dataString = dataObjeto.toLocaleDateString('pt-BR', opcoesFormatacao);
        dataString = dataString.charAt(0).toUpperCase() + dataString.slice(1);

        document.getElementById('label-data-selecionada').innerText = dataString;

        const horariosDisponiveis = ["09:00", "10:30", "12:00", "14:00", "16:30", "18:00"];
        const containerHorarios = document.getElementById('container-lista-horarios');
        containerHorarios.innerHTML = '';

        horariosDisponiveis.forEach(hora => {
            const btn = document.createElement('button');
            btn.className = 'btn-horario-opcao';
            btn.innerText = hora;

            if (hora === "12:00" || hora === "14:00") {
                btn.innerHTML = `${hora} <img src="../assets/imagens/icone-exclamacao.png" class="badge-concorrido" alt="Concorrido">`;
            }

            btn.onclick = () => realizarAgendamentoFinal(dataObjeto, hora);
            containerHorarios.appendChild(btn);
        });

        document.getElementById('etapa-calendario').classList.add('oculto');
        document.getElementById('etapa-horarios').classList.remove('oculto');
    }

   function realizarAgendamentoFinal(dataObjeto, hora) {
        // Formata a data para o padrão ISO (YYYY-MM-DD)
        const ano = dataObjeto.getFullYear();
        const mes = String(dataObjeto.getMonth() + 1).padStart(2, '0'); // Ajusta mês de 0-11 para 01-12
        const dia = String(dataObjeto.getDate()).padStart(2, '0');
        
        const dataFormatada = `${ano}-${mes}-${dia}`;

        mostrarToast(`Horário de ${hora} selecionado! Redirecionando...`);

        // Redireciona passando a data e a hora na URL para o checkout pescar
        setTimeout(() => {
            window.location.href = `checkout.html?data=${dataFormatada}&hora=${hora}`;
        }, 1000);
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