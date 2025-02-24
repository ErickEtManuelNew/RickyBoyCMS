<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Configuration de la base de données avec Docker
$host = 'localhost'; // Nom du conteneur Docker MySQL
$dbname = 'cms';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>