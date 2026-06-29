document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('container-especialidades');

    fetch('../dados/servicos.json')
        .then(res => res.json())
        .then(dados => {
            dados.forEach(esp => {
                const card = document.createElement('div');
                card.className = 'card-especialidade';
                card.innerHTML = `
                    <img src="${esp.imagem}" alt="${esp.categoria}" style="width: 55px; height: 55px; object-fit: contain;">
                    <h2 style="margin: 15px 0 0 0; font-size: 1.4rem;">${esp.categoria}</h2>
                    <div class="conteudo-extra">
                        <p>${esp.descricao}</p>
                        <a href="reservar.html?categoria=${encodeURIComponent(esp.categoria)}" class="btn-explorar" style="text-decoration: none; display: inline-block;">
                            Explorar serviços
                        </a>
                    </div>
                `;
                
                card.addEventListener('click', (e) => {
                    if (e.target.classList.contains('btn-explorar')) return;

                    const jaEstaExpandido = card.classList.contains('expandido');

                    document.querySelectorAll('.card-especialidade').forEach(c => {
                        c.classList.remove('expandido');
                    });

                    if (!jaEstaExpandido) {
                        card.classList.add('expandido');
                    }
                });

                container.appendChild(card);
            });
        });
});