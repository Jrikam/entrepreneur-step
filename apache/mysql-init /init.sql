CREATE DATABASE IF NOT EXISTS entrepreneur_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE entrepreneur_db;

CREATE TABLE utilisateurs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  mot_de_passe VARCHAR(255) NOT 
  role ENUM ('utilisateur','admin') DEFAULT 'utilisateur',
  date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100) NOT NULL,
  description TEXT,    
  );

CREATE TABLE services (
  id INT AUTO_INCREMENT PRIMARY KEY,
  utilisateur_id INT NOT NULL,
  categorie_id INT NOT NULL,
  titre VARCHAR(100) NOT NULL,
  description TEXT,
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
  FOREIGN KEY (categorie_id) REFERENCES categories(id) ON DELETE CASCADE
);

INSERT INTO categories (nom, description) VALUES
('Artisanat', 'Projets liés aux métiers manuels et artisanaux'),
('Audiovisuel', 'Projets liés à la vidéo, au cinéma, au son'),
('Informatique', 'Projets liés au développement web, logiciels, IT'),
('Commerce', 'Projets liés à la vente et distribution'),
('Marketing', 'Projets liés à la communication et la publicité');

CREATE TABLE progression (
    id_progression INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_domaine INT NOT NULL,
    etape VARCHAR(255) NOT NULL,
    statut ENUM('en cours', 'terminé', 'bloqué') DEFAULT 'en cours',
    date_mise_a_jour TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES utilisateurs(id),
    FOREIGN KEY (id_domaine) REFERENCES domaines(id)
);

CREATE TABLE projets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    titre VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES utilisateurs(id)
);
