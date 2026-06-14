CREATE DATABASE IF NOT EXISTS `sistema_perguntas` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE `sistema_perguntas`;

-- Tabela de Usuários (arquivo de cadastro)
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT, -- O próprio banco gera o número do ID sozinho
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de Perguntas Objetivas
CREATE TABLE IF NOT EXISTS `perguntas_objetivas` (
  `id_pergunta` varchar(10) NOT NULL, -- Usando VARCHAR caso você decida usar letras nos IDs no futuro (ex: 01A)
  `enunciado` text NOT NULL, -- TEXT permite textos longos sem cortar a pergunta no meio
  `correta` varchar(255) NOT NULL,
  `incorreta1` varchar(255) DEFAULT NULL,
  `incorreta2` varchar(255) DEFAULT NULL,
  `incorreta3` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_pergunta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de Perguntas Subjetivas
CREATE TABLE IF NOT EXISTS `perguntas_subjetivas` (
  `id_pergunta` varchar(10) NOT NULL,
  `enunciado` text NOT NULL,
  `resposta_modelo` text DEFAULT NULL,
  PRIMARY KEY (`id_pergunta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;