-- Banco de dados para portfólio THOMAS videomaker
CREATE DATABASE IF NOT EXISTS thomas_videomaker CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE thomas_videomaker;

-- Tabela de administradores
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de vídeos
CREATE TABLE IF NOT EXISTS videos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    category ENUM('Aftermovie Evento', 'Aftermovie DJ', 'Vídeo Drop') NOT NULL,
    video_url VARCHAR(500) NOT NULL,
    thumbnail_url VARCHAR(500),
    duration VARCHAR(20),
    views INT DEFAULT 0,
    featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inserir admin padrão
-- NOTA: Use o arquivo install.php para criar o admin com senha hash correta
-- Ou execute: INSERT INTO admins (username, password, email) VALUES 
-- ('admin', 'HASH_GERADO_POR_PASSWORD_HASH', 'admin@thomasvideomaker.com');

