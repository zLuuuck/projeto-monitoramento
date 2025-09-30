-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS monitoramento_db;
USE monitoramento_db;

-- Tabela de usuários (mantendo estrutura similar ao SQLite)
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    birthdate DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela de produtos (mantendo estrutura similar)
CREATE TABLE IF NOT EXISTS produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    modelo VARCHAR(100) UNIQUE NOT NULL,
    cor VARCHAR(50) NOT NULL,
    quantidade INT NOT NULL,
    imagem VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insere usuário admin (senha: "senha123")
INSERT IGNORE INTO usuarios (username, nome, email, senha, birthdate) VALUES 
('admin', 'Admin', 'admin@ed2.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '1990-01-01');

-- Insere produtos de exemplo
INSERT IGNORE INTO produtos (nome, modelo, cor, quantidade, imagem) VALUES 
('Notebook Dell', 'DLLXPS13', 'Prata', 5, 'produto1.jpg'),
('Mouse Logitech', 'LOGIMX500', 'Preto', 15, 'produto2.jpg');