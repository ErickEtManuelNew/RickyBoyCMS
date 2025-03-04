CREATE DATABASE cms_maison DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci; 

CREATE USER 'cms_user'@'localhost' IDENTIFIED BY '1IxDRgiA[-znLp5E';
GRANT ALL PRIVILEGES ON cms_maison.* TO 'cms_user'@'localhost' WITH GRANT OPTION;
FLUSH PRIVILEGES;

USE cms_maison;
CREATE TABLE users(
    id INT AUTO_INCREMENT PRIMARY KEY,
    prenom VARCHAR(100) NOT NULL,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    photo_utilisateur VARCHAR(255) DEFAULT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'rédacteur', 'utilisateur') NOT NULL DEFAULT 'utilisateur',
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    email_verifie BOOLEAN DEFAULT FALSE,
    token VARCHAR(255) NULL,
    token_expiration DATETIME NULL    
); CREATE TABLE categories(
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom_categorie VARCHAR(100) NOT NULL UNIQUE
); CREATE TABLE articles(
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content longtext NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    image VARCHAR(255) DEFAULT NULL,
    id_categorie INT NOT NULL,
    id_utilisateur INT NOT NULL,
    FOREIGN KEY(id_categorie) REFERENCES categories(id_categorie) ON DELETE CASCADE,
    FOREIGN KEY(id_utilisateur) REFERENCES users(id) ON DELETE CASCADE
); INSERT INTO users(
    prenom,
    nom,
    email,
    password,
    role,
    email_verifie
)
VALUES(
    'Admin',
    'Principal',
    'admin@example.com',
    '$2y$10$RBWHuvgC/IQUwMq8TdiuK.yvHJ.I.3cVje7pKQt37lUHkPZ7EOD7C',
    'admin',
    1
);
INSERT INTO categories(nom_categorie)
VALUES('Technologie'),('Science'),('Sport'),('Culture');