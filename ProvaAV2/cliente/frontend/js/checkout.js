document.addEventListener('DOMContentLoaded', () => {
    const carrinhoAtivo = localStorage.getItem('carrinho_ativo');
    const parametros = new URLSearchParams(window.location.search);
    const dataReserva = parametros.get('data');
    const horaReserva = parametros.get('hora');

    console.log("Rastreador 1 - Dados recebidos no Checkout:", {
    carrinho: carrinhoAtivo ? JSON.parse(carrinhoAtivo) : "Vazio",
    data: dataReserva,
    hora: horaReserva
});

    if (!carrinhoAtivo || !dataReserva || !horaReserva) {
        alert("Acesso inválido! Complete as etapas de agendamento primeiro.");
        window.location.href = 'reservar.html';
        return;
    }

    const carrinho = JSON.parse(carrinhoAtivo);
    let totalCentavos = carrinho.reduce((soma, item) => soma + item.preco, 0);
    
    // R$ 1,00 = 20 créditos (Preço em reais * 20)
    let totalCreditosNecessarios = (totalCentavos / 100) * 20; 

    renderizarReciboEsquerdo(dataReserva, horaReserva);
    buscarDadosSessaoEValidarCreditos();

    function renderizarReciboEsquerdo(dataStr, horaStr) {

        console.log("Rastreador 2 - Entrou na renderização do recibo com:", dataStr, horaStr);

        const partes = dataStr.split('-');
        const dataObjeto = new Date(partes[0], partes[1] - 1, partes[2]);
        
        const opcoes = { weekday: 'long', day: 'numeric', month: 'long' };
        let porExtenso = dataObjeto.toLocaleDateString('pt-BR', opcoes);
        porExtenso = porExtenso.charAt(0).toUpperCase() + porExtenso.slice(1);

        document.getElementById('recibo-data-hora').innerText = `${porExtenso} - ${horaStr}`;

        const containerItens = document.getElementById('lista-itens-recibo');
        containerItens.innerHTML = '';

        carrinho.forEach(item => {
            const precoReais = (item.preco / 100).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
            const p = document.createElement('p');
            p.className = 'item-recibo-linha';
            p.innerHTML = `<span>✓</span> ${item.nome} - ${precoReais}`;
            containerItens.appendChild(p);
        });

        const totalReaisStr = (totalCentavos / 100).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
        document.getElementById('texto-total-valores').innerText = `${totalCreditosNecessarios} créditos ou ${totalReaisStr}`;
    }

    function buscarDadosSessaoEValidarCreditos() {

        console.log("Rastreador 3 - Tentando buscar dados da sessão no PHP...");

        fetch('../../backend/obter_dados_usuario.php', { credentials: 'include' })
            .then(res => res.json())
            .then(dados => {
                if (!dados || dados.erro) {
                    window.location.href = 'login.html';
                    return;
                }

                const primeiroNome = dados.nome.split(' ')[0];
                document.getElementById('nome-topo-usuario').innerText = primeiroNome;
                
                const saldoCreditos = Number(dados.creditos || 0);
                document.getElementById('saldo-topo-usuario').innerText = saldoCreditos;
                document.getElementById('saldo-disponivel-info').innerText = saldoCreditos;
                document.getElementById('custo-credito-info').innerText = totalCreditosNecessarios;

                const cardCredito = document.getElementById('card-pagamento-credito');
                const infoStatusCredito = document.getElementById('info-status-credito');

                if (saldoCreditos >= totalCreditosNecessarios) {
                    cardCredito.classList.remove('desativado');
                    cardCredito.classList.add('ativo-rosa');
                    infoStatusCredito.innerHTML = `<span class="txt-insuficiente" style="color: #4CAF50;">Saldo Disponível!</span>
                                                   <span class="txt-valores-sub">Custo: <span class="cor-rosa">${totalCreditosNecessarios}</span></span>`;
                    
                    cardCredito.onclick = abrirModalCreditos;
                }
            });
    }

    const modal = document.getElementById('modal-confirmacao-credito');
    
    function abrirModalCreditos() {
        document.getElementById('txt-pergunta-modal').innerText = `Tem certeza que deseja gastar seus ${totalCreditosNecessarios} créditos nessa compra?`;
        modal.classList.remove('oculto');
    }

    document.getElementById('btn-modal-cancelar').onclick = () => modal.classList.add('oculto');
    
    document.getElementById('btn-modal-confirmar').onclick = () => {
        modal.classList.add('oculto');
        
        const payload = {
            carrinho: carrinho,
            data: dataReserva,
            hora: horaReserva
        };

        fetch('../../backend/reservar_por_creditos.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload),
            credentials: 'include'
        })
        .then(res => res.json())
        .then(resposta => {
            if (resposta.sucesso) {
                localStorage.removeItem('carrinho_ativo');
                localStorage.setItem('reserva_sucesso_data', dataReserva);
                localStorage.setItem('reserva_sucesso_hora', horaReserva);
                localStorage.setItem('reserva_sucesso_metodo', 'Créditos');
                window.location.href = 'confirmado.html';
            } else {
                alert("Não foi possível concluir o agendamento: " + resposta.erro);
            }
        })
        .catch(erro => {
            console.error("Erro no checkout por créditos:", erro);
            alert("Erro de comunicação com o servidor.");
        });
    };

    document.getElementById('card-pagamento-pix').onclick = () => avancarFluxoPagamento('pix');
    document.getElementById('card-pagamento-cartao').onclick = () => avancarFluxoPagamento('cartao');

    function avancarFluxoPagamento(metodo) {
        localStorage.setItem('pagamento_metodo', metodo);
        localStorage.setItem('pagamento_data', dataReserva);
        localStorage.setItem('pagamento_hora', horaReserva);

        if (metodo === 'pix') {
            window.location.href = 'pagamento_pix.html';
        } else {
            window.location.href = 'pagamento_cartao.html';
        }
    }
});