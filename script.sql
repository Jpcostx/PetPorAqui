
CREATE DATABASE IF NOT EXISTS petporaqui;
USE petporaqui;

-- Tabela de Usuários
CREATE TABLE usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(100) NOT NULL,
    Email VARCHAR(100) NOT NULL UNIQUE,
    tipo VARCHAR(50) NOT NULL, -- Opções: 'Administrador', 'Adotante', 'ONG'
    senha VARCHAR(255) NOT NULL DEFAULT '123456'
);

-- Tabela de ONGs
CREATE TABLE ong (
    cnpj VARCHAR(18) PRIMARY KEY,
    nome VARCHAR(100) NOT NULL
);

-- Tabela de Pets
CREATE TABLE pet (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    especie VARCHAR(50) NOT NULL,
    localizacao VARCHAR(100) NOT NULL,
    disponibilidade VARCHAR(20) NOT NULL DEFAULT 'Disponível',
    id_usuario INT NOT NULL,
    cnpj_ong VARCHAR(18) NULL,
    imagem VARCHAR(500) NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id),
    FOREIGN KEY (cnpj_ong) REFERENCES ong(cnpj)
);

-- Inserindo Usuários
INSERT INTO usuario (Nome, Email, tipo, senha) VALUES
('Nathalia', 'nathalia@email.com', 'Adotante', '123456'),
('João Pedro', 'joao.pedro@email.com', 'Administrador', '123456'),
('Guilherme', 'guilherme@email.com', 'Adotante', '123456');

-- Inserindo ONGs 
INSERT INTO ong (cnpj, nome) VALUES
('11.111.111/0001-11', 'ONG Patas Felizes RJ'),
('22.222.222/0001-22', 'Abrigo Vira-Lata');

-- Inserindo Pets 
-- O id_usuario = 2 é o perfil Administrador responsável pelo cadastro
INSERT INTO pet (nome, especie, localizacao, disponibilidade, id_usuario, cnpj_ong, imagem) VALUES
('Mengo', 'Cachorro', 'Tijuca, Rio de Janeiro - RJ', 'Disponível', 2, '11.111.111/0001-11', 'https://images.pexels.com/photos/1108099/pexels-photo-1108099.jpeg?auto=compress&cs=tinysrgb&w=500'),
('Bolinha', 'Cachorro', 'Centro, Rio de Janeiro - RJ', 'Disponível', 2, NULL, 'https://images.pexels.com/photos/1108099/pexels-photo-1108099.jpeg?auto=compress&cs=tinysrgb&w=500'),
('Trovão', 'Gato', 'Quintino Bocaiúva - Rio de Janeiro', 'Adotado', 2, '22.222.222/0001-22', 'https://images.pexels.com/photos/104827/cat-pet-animal-domestic-104827.jpeg?auto=compress&cs=tinysrgb&w=500');