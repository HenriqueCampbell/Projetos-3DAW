-- 1. Tabela de Usuários
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    sobrenome VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    username VARCHAR(50) NOT NULL UNIQUE,
    telefone VARCHAR(20) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    creditos INT DEFAULT 0,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Tabela de Favoritos (Vínculo de profissionais favoritados)
CREATE TABLE IF NOT EXISTS favoritos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    funcionario_id INT NOT NULL,
    UNIQUE KEY unico_favorito (usuario_id, funcionario_id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- 3. Tabela de Agendamentos (Registro Mestre)
CREATE TABLE IF NOT EXISTS agendamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    data_hora DATETIME NOT NULL,
    duracao_estimada_minutos INT NULL, 
    valor_total_centavos INT NOT NULL,     
    forma_pagamento VARCHAR(50) NOT NULL, -- 'creditos', 'pix' ou 'cartao'
    status_reserva VARCHAR(20) DEFAULT 'concluido',
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- 4. Tabela de Itens do Agendamento (O detalhe de cada serviço)
CREATE TABLE IF NOT EXISTS agendamento_itens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    agendamento_id INT NOT NULL,
    servico_id INT NOT NULL,      
    profissional_id INT NULL, -- Aceita nulo caso o filtro fique desativado
    preco_pago_centavos INT NOT NULL,
    duracao_item_minutos INT NULL,
    FOREIGN KEY (agendamento_id) REFERENCES agendamentos(id) ON DELETE CASCADE
);