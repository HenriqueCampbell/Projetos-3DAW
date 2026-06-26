CREATE TABLE favoritos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    funcionario_id INT NOT NULL,

    UNIQUE KEY unico_favorito (usuario_id, funcionario_id),

    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);