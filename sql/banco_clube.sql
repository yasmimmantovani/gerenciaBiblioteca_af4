
CREATE DATABASE IF NOT EXISTS clubelivro DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE clubelivro;


CREATE TABLE IF NOT EXISTS usuarios_adm (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  telefone VARCHAR(20),
  senha VARCHAR(255) NOT NULL,
  nivel ENUM('admin','funcionario') NOT NULL DEFAULT 'funcionario',
  data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS clientes (
  id_clientes INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  endereco VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS livros (
  id_livro INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(150) NOT NULL,
  autor VARCHAR(100),
  ano INT,
  genero VARCHAR(50),
  quantidade INT NOT NULL DEFAULT 1,
  disponibilidade ENUM('Disponível','Emprestado') DEFAULT 'Disponível'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS emprestimos (
  id_emprestimo INT AUTO_INCREMENT PRIMARY KEY,
  id_clientes INT,
  id_livro INT,
  data_emprestimo DATE NOT NULL,
  data_devolucao DATE,
  status ENUM('Ativo','Devolvido') DEFAULT 'Ativo',
  CONSTRAINT fk_emprestimos_clientes FOREIGN KEY (id_clientes) REFERENCES clientes(id_clientes) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_emprestimos_livros   FOREIGN KEY (id_livro)    REFERENCES livros(id_livro) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
