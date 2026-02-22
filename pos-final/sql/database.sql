-- ============================================================
-- Script SQL – Système Point de Vente (POS)
-- ============================================================

CREATE DATABASE IF NOT EXISTS pos_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE pos_system;

-- Table utilisateurs
CREATE TABLE IF NOT EXISTS utilisateurs (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    login         VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role          ENUM('admin','caissier') NOT NULL DEFAULT 'caissier',
    created_at    DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Table commandes
CREATE TABLE IF NOT EXISTS commandes (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    client_name   VARCHAR(150) NOT NULL,
    total_amount  DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    status        ENUM('en_cours','validee','annulee') NOT NULL DEFAULT 'en_cours',
    created_at    DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at    DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Table lignes de commandes
CREATE TABLE IF NOT EXISTS ligne_commandes (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    commande_id   INT NOT NULL,
    produit_nom   VARCHAR(200) NOT NULL,
    quantite      INT NOT NULL DEFAULT 1,
    prix_unitaire DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (commande_id) REFERENCES commandes(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Table produits
CREATE TABLE IF NOT EXISTS produits (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    nom        VARCHAR(200) NOT NULL,
    prix       DECIMAL(10,2) NOT NULL,
    categorie  VARCHAR(100) DEFAULT 'Général',
    actif      TINYINT(1) DEFAULT 1
) ENGINE=InnoDB;

-- ============================================================
-- Utilisateurs par défaut
-- admin    → mot de passe : admin123
-- caissier → mot de passe : caissier1
-- Hashes bcrypt $2b$12$ générés et vérifiés
-- ============================================================
INSERT INTO utilisateurs (login, password_hash, role) VALUES
('admin',    '$2b$12$0pHq.0nFE9xeObeWbsLk7O6zoGAxah1/9AqnSDaSy0JnxPUOAw3HW', 'admin'),
('caissier', '$2b$12$fm59Od4pvyew/5w5slS7WOjCPWKgu3hDat2QAyPO4PUkVpC7hvsUO', 'caissier');

-- Produits de démonstration
INSERT INTO produits (nom, prix, categorie) VALUES
('Café Espresso',    1.50, 'Boissons'),
('Café Crème',       2.00, 'Boissons'),
('Thé Vert',         1.80, 'Boissons'),
('Eau Minérale 50cl',1.00, 'Boissons'),
('Jus d''Orange',   2.50, 'Boissons'),
('Croissant',        1.20, 'Viennoiseries'),
('Pain au Chocolat', 1.30, 'Viennoiseries'),
('Cookie Chocolat',  1.50, 'Pâtisseries'),
('Tarte aux Pommes', 3.00, 'Pâtisseries'),
('Sandwich Jambon',  4.50, 'Restauration'),
('Sandwich Végétarien',4.00,'Restauration'),
('Salade César',     6.50, 'Restauration');
