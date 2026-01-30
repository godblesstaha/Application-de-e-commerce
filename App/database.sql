-- Database: dbecommerce
-- Creation date: 2026-01-29

-- Create Database
CREATE DATABASE IF NOT EXISTS dbecommerce;
USE dbecommerce;

-- 1. USERS TABLE
CREATE TABLE utilisateurs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE NOT NULL,
    motdepasse VARCHAR(255) NOT NULL,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    telephone VARCHAR(20),
    adresse VARCHAR(255),
    ville VARCHAR(100),
    statut ENUM('actif', 'inactif') DEFAULT 'actif',
    dateInscription DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. CATEGORIES TABLE
CREATE TABLE categories (
    idCategorie INT PRIMARY KEY AUTO_INCREMENT,
    nomCategorie VARCHAR(100) NOT NULL,
    description TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. PRODUCTS TABLE
CREATE TABLE produits (
    idProduit INT PRIMARY KEY AUTO_INCREMENT,
    nomProduit VARCHAR(255) NOT NULL,
    description TEXT,
    prix DECIMAL(10,2) NOT NULL,
    urlImage VARCHAR(255),
    idCategorie INT NOT NULL,
    quantiteStock INT NOT NULL,
    marque VARCHAR(100),
    FOREIGN KEY (idCategorie) REFERENCES categories(idCategorie) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. PROMOTIONS TABLE
CREATE TABLE promotions (
    idPromotion INT PRIMARY KEY AUTO_INCREMENT,
    idProduit INT NOT NULL,
    pourcentageReduction INT,
    montantReduction DECIMAL(10,2),
    dateCreation DATETIME DEFAULT CURRENT_TIMESTAMP,
    dateLimite DATETIME NOT NULL,
    FOREIGN KEY (idProduit) REFERENCES produits(idProduit) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. IMAGES TABLE (for logo and other images)
CREATE TABLE images (
    idImage INT PRIMARY KEY AUTO_INCREMENT,
    urlImage VARCHAR(255) NOT NULL,
    type VARCHAR(50)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. PUBLICITES TABLE
CREATE TABLE publicites (
    idPub INT PRIMARY KEY AUTO_INCREMENT,
    urlImage VARCHAR(255) NOT NULL,
    dateDebut DATETIME NOT NULL,
    dateFin DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. ORDERS TABLE
CREATE TABLE commandes (
    idCommande INT PRIMARY KEY AUTO_INCREMENT,
    idUtilisateur INT NOT NULL,
    dateCommande DATETIME DEFAULT CURRENT_TIMESTAMP,
    montantTotal DECIMAL(10,2) NOT NULL,
    statut ENUM('en_attente', 'confirmée', 'expédiée', 'livrée') DEFAULT 'en_attente',
    FOREIGN KEY (idUtilisateur) REFERENCES utilisateurs(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. ORDER ITEMS TABLE
CREATE TABLE lignesCommandes (
    idLigneCommande INT PRIMARY KEY AUTO_INCREMENT,
    idCommande INT NOT NULL,
    idProduit INT NOT NULL,
    quantite INT NOT NULL,
    prixUnitaire DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (idCommande) REFERENCES commandes(idCommande) ON DELETE CASCADE,
    FOREIGN KEY (idProduit) REFERENCES produits(idProduit) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


