CREATE DATABASE cms_maison DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci; USE
    cms_maison;
CREATE TABLE users(
    id INT AUTO_INCREMENT PRIMARY KEY,
    prenom VARCHAR(100) NOT NULL,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    photo_utilisateur VARCHAR(255) DEFAULT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'rédacteur', 'utilisateur') NOT NULL DEFAULT 'utilisateur',
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
); CREATE TABLE categories(
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom_categorie VARCHAR(100) NOT NULL UNIQUE
); CREATE TABLE articles(
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
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
    role
)
VALUES(
    'Admin',
    'Principal',
    'admin@example.com',
    '$2y$10$ABCDE...',
    'admin'
),(
    'Jean',
    'Dupont',
    'jean@example.com',
    '$2y$10$FGHIJ...',
    'rédacteur'
);
INSERT INTO categories(nom_categorie)
VALUES('Technologie'),('Science'),('Sport'),('Culture');
INSERT INTO articles(
    title,
    content,
    image,
    id_categorie,
    id_utilisateur
)
VALUES(
    'Introduction à PHP',
    'PHP est un langage serveur puissant...',
    NULL,
    1,
    2
),(
    'Découverte de l\'IA',
    'L\'intelligence artificielle révolutionne...',
    NULL,
    2,
    2
);