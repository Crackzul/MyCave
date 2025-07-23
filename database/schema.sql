-- Création de la base de données
CREATE DATABASE IF NOT EXISTS mycave_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE mycave_db;

-- Table des utilisateurs
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table des vins
CREATE TABLE wines (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    year INT NOT NULL,
    grapes VARCHAR(255),
    country VARCHAR(100),
    region VARCHAR(100),
    description TEXT,
    picture VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insertion d'un utilisateur admin par défaut
INSERT INTO users (email, password, name, role) VALUES 
('admin@mycave.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Didier Admin', 'admin'),
('didier@mycave.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Didier Martin', 'user');
-- Mot de passe par défaut : "password"

-- Insertion de quelques vins d'exemple
INSERT INTO wines (user_id, name, year, grapes, country, region, description, picture) VALUES 
(2, 'Château Margaux', 2015, 'Cabernet Sauvignon, Merlot', 'France', 'Bordeaux', 'Un grand cru exceptionnel avec des arômes complexes de fruits rouges et d\'épices. Parfait pour les grandes occasions.', 'margaux.jpg'),
(2, 'Domaine de la Côte', 2018, 'Pinot Noir', 'France', 'Bourgogne', 'Un pinot noir élégant aux notes de cerise et de sous-bois. Idéal avec les viandes rouges.', 'cote.jpg'),
(2, 'Barolo Brunate', 2016, 'Nebbiolo', 'Italie', 'Piémont', 'Un Barolo puissant et tannique, avec une longue garde. Notes de rose et de truffe.', 'barolo.jpg'),
(2, 'Chablis Premier Cru', 2019, 'Chardonnay', 'France', 'Bourgogne', 'Un blanc minéral et frais, parfait avec les fruits de mer et poissons.', 'chablis.jpg'),
(2, 'Rioja Gran Reserva', 2014, 'Tempranillo', 'Espagne', 'Rioja', 'Un rouge espagnol complexe, élevé en fût de chêne. Notes de vanille et de fruits mûrs.', 'rioja.jpg'),
(2, 'Champagne Dom Pérignon', 2012, 'Chardonnay, Pinot Noir', 'France', 'Champagne', 'Un champagne d\'exception aux bulles fines et persistantes. Parfait pour célébrer.', 'dom_perignon.jpg');