document.addEventListener('DOMContentLoaded', function() {
    
    // Descobre se o usuário está dentro da pasta 'paginas' ou solto na raiz (index.html)
    const estaNaPastaPaginas = window.location.pathname.includes('/paginas/');

    // Ajusta o caminho do PHP
    // Se está em 'paginas', volta duas pastas. Se está no index, entra direto na pasta backend.
    const caminhoBackend = estaNaPastaPaginas ? '../../backend/' : 'backend/'; 

    // Pergunta ao PHP se tem alguém logado
    fetch(caminhoBackend + 'verifica_sessao.php')
        .then(resposta => resposta.json())
        .then(dados => {
            
            const btnLogin = document.getElementById('link-login');

            if (dados.logado && btnLogin) {
                // Muda o texto
                btnLogin.textContent = 'Menu do Usuário'; 
                
                // Ajusta o caminho do link do menu
                // Se está no index, precisa entrar em frontend/paginas/. Se já está, é só chamar o arquivo.
                const caminhoMenu = estaNaPastaPaginas ? 'menu-do-usuario.html' : 'frontend/paginas/menu-do-usuario.html';
                btnLogin.href = caminhoMenu;
                
                btnLogin.style.color = '#FFFFFF'; 
                btnLogin.style.fontWeight = 'bold';
            }
        })
        .catch(erro => console.error("Erro ao verificar sessão:", erro));
});