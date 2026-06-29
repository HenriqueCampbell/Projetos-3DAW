document.addEventListener('DOMContentLoaded', () => {
    let carrinho = [];
    let primeiroCliqueFeito = false;

    const containerCardapio = document.getElementById('container-cardapio-servicos');
    const overlapCarrinho = document.getElementById('overlap-carrinho');
    const btnCarrinhoFlutuante = document.getElementById('btn-carrinho-flutuante');
    const btnFecharCarrinho = document.getElementById('btn-fechar-carrinho');
    const btnCheckout = document.getElementById('btn-prosseguir-checkout');

    fetch('../dados/servicos.json')
        .then(res => res.json())
        .then(categorias => {
            renderizarCardapio(categorias);
            verificarScrollURL();
        })
        .catch(err => console.error("Erro ao carregar o cardápio:", err));

    function renderizarCardapio(categorias) {
        containerCardapio.innerHTML = '';
        
        categorias.forEach((cat, index) => {
            const bloco = document.createElement('div');
            bloco.className = `bloco-categoria ${index % 2 !== 0 ? 'par' : ''}`;
            bloco.id = cat.categoria.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");

            let itensHTML = '';
            cat.itens.forEach(item => {
                const precoFormatado = (item.preco_centavos / 100).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                
                // Se o item estiver em um pacote, injeta o seu asset de imagem ao invés do emoji
                const tagPacote = item.em_pacote 
                    ? `<img src="../assets/imagens/icone-pacote.png" alt="Disponível em Pacote" class="icone-inline-pacote">` 
                    : '';

                itensHTML += `
                    <label class="item-servico-check">
                        <input type="checkbox" value="${item.id}" data-nome="${item.nome}" data-preco="${item.preco_centavos}">
                        ${item.nome} - ${precoFormatado} ${tagPacote}
                    </label>
                `;
            });

            bloco.innerHTML = `
                <div class="banner-categoria">
                    <img src="${cat.imagem}" alt="${cat.categoria}">
                    <h2>${cat.categoria}</h2>
                </div>
                <div class="lista-itens-categoria">
                    ${itensHTML}
                </div>
            `;

            containerCardapio.appendChild(bloco);
        });

        containerCardapio.addEventListener('change', (e) => {
            if (e.target.type === 'checkbox') {
                gerenciarSelecaoItem(e.target);
            }
        });
    }

    function gerenciarSelecaoItem(input) {
        const id = Number(input.value);
        const nome = input.getAttribute('data-nome');
        const preco = Number(input.getAttribute('data-preco'));

        if (input.checked) {
            carrinho.push({ id, nome, preco });
            if (!primeiroCliqueFeito) {
                mostrarToast(`${nome} adicionado! Role até o final da página para fazer o seu checkout.`);
                primeiroCliqueFeito = true;
            } else {
                mostrarToast(`${nome} adicionado à lista.`);
            }
        } else {
            carrinho = carrinho.filter(item => item.id !== id);
            mostrarToast(`${nome} removido.`);
        }

        atualizarInterfaceCarrinho();
    }

    function atualizarInterfaceCarrinho() {
        document.getElementById('contador-carrinho-badge').innerText = carrinho.length;
        btnCheckout.innerText = `Reservar (${carrinho.length}) Serviços Selecionados`;

        const listaContainer = document.getElementById('lista-itens-carrinho');
        const totalContainer = document.getElementById('valor-total-carrinho');

        if (carrinho.length === 0) {
            listaContainer.innerHTML = '<p class="carrinho-vazio">Nenhum serviço selecionado ainda.</p>';
            totalContainer.innerText = 'R$ 0,00';
            return;
        }

        let listaHTML = '';
        let totalCentavos = 0;

        carrinho.forEach(item => {
            totalCentavos += item.preco;
            const precoFormatado = (item.preco / 100).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
            listaHTML += `
                <div class="item-no-carrinho">
                    <span>${item.nome}</span>
                    <strong>${precoFormatado}</strong>
                </div>
            `;
        });

        listaContainer.innerHTML = listaHTML;
        totalContainer.innerText = (totalCentavos / 100).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    }

    function verificarScrollURL() {
        const parametros = new URLSearchParams(window.location.search);
        const categoriaAlvo = parametros.get('categoria');

        if (categoriaAlvo) {
            const elemento = document.getElementById(categoriaAlvo.toLowerCase().trim());
            if (elemento) {
                setTimeout(() => {
                    elemento.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 300);
            }
        }
    }

    btnCheckout.addEventListener('click', () => {
        if (carrinho.length === 0) {
            mostrarToast("Por favor, selecione pelo menos um serviço.");
            return;
        }

        fetch('../../backend/obter_dados_usuario.php', { credentials: 'include' })
            .then(res => res.json())
            .then(dados => {
                if (!dados || dados.erro || !dados.nome) {
                    localStorage.setItem('carrinho_pendente', JSON.stringify(carrinho));
                    mostrarToast("Identificamos que você não está logado. Redirecionando...");
                    setTimeout(() => { window.location.href = 'login.html'; }, 1500);
                } else {
                    localStorage.setItem('carrinho_ativo', JSON.stringify(carrinho));
                    window.location.href = 'agendamento.html';
                }
            })
            .catch(() => mostrarToast("Erro de comunicação com o servidor."));
    });

    btnCarrinhoFlutuante.addEventListener('click', () => overlapCarrinho.classList.add('aberto'));
    btnFecharCarrinho.addEventListener('click', () => overlapCarrinho.classList.remove('aberto'));

    function mostrarToast(mensagem) {
        const container = document.getElementById('container-toasts');
        const toast = document.createElement('div');
        toast.className = 'toast-notificacao';
        toast.innerText = mensagem;
        
        container.appendChild(toast);
        setTimeout(() => { toast.remove(); }, 3000);
    }
});