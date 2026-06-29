document.addEventListener('DOMContentLoaded', () => {
    const dataReserva = localStorage.getItem('pagamento_data') || localStorage.getItem('reserva_sucesso_data');
    const horaReserva = localStorage.getItem('pagamento_hora') || localStorage.getItem('reserva_sucesso_hora');
    const metodo = localStorage.getItem('pagamento_metodo') || localStorage.getItem('reserva_sucesso_metodo');
    const carrinhoCru = localStorage.getItem('carrinho_ativo');

    // Se não tiver dados de sucesso mínimos, bloqueia
    if (!dataReserva || !horaReserva) {
        window.location.href = 'reservar.html';
        return;
    }

    // Se veio do Pix ou Cartão, salva no banco agora antes de limpar o cache
    if (carrinhoCru && (metodo === 'pix' || metodo === 'cartao')) {
        const payload = {
            carrinho: JSON.parse(carrinhoCru),
            data: dataReserva,
            hora: horaReserva,
            metodo: metodo
        };

        fetch('../../backend/concluir_reserva_geral.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload),
            credentials: 'include'
        })
        .then(res => res.json())
        .then(res => {
            if (!res.sucesso) console.error("Erro na gravação automática do banco:", res.erro);
        });
    }

    // Renderiza as linhas na tabela com os serviços e o horário unificado
    const corpoTabela = document.getElementById('corpo-tabela-resumo');
    corpoTabela.innerHTML = '';

    // Tenta ler os itens da compra para preencher o recibo
    let itensParaExibir = [{ nome: "Serviço Adquirido" }];
    if (carrinhoCru) {
        itensParaExibir = JSON.parse(carrinhoCru);
    } else if (localStorage.getItem('carrinho_pendente')) {
        itensParaExibir = JSON.parse(localStorage.getItem('carrinho_pendente'));
    }

    itensParaExibir.forEach(item => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${item.nome}</td>
            <td>${horaReserva}</td>
        `;
        corpoTabela.appendChild(tr);
    });

    // LIMPEZA TOTAL DA BAGAGEM
    localStorage.removeItem('carrinho_ativo');
    localStorage.removeItem('pagamento_metodo');
    localStorage.removeItem('pagamento_data');
    localStorage.removeItem('pagamento_hora');
});