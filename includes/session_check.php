<?php
// Vérifier si la session est active avant de la démarrer
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$config = require __DIR__ . '/../config/config.php';
$timeout = $config['session_timeout']; // Timeout de session défini dans config.php
// Vérifier si l'utilisateur est actif et réinitialiser en cas d'inactivité
if (!empty($_SESSION['LAST_ACTIVITY'])) {
    $timeSinceLastActivity = time() - $_SESSION['LAST_ACTIVITY'];

    // Si l'inactivité dépasse le timeout, on détruit la session et force la reconnexion
    if ($timeSinceLastActivity > $timeout) {
        session_unset(); // Supprime toutes les variables de session
        session_destroy(); // Détruit la session

        // 🔥 Redémarrer une session proprement après destruction
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
            session_regenerate_id(true); // Sécurisation de session
        }

        header("Location: ../login.php?timeout=1"); // Redirection avec message timeout
        exit();
    }
}

// Met à jour le timestamp de la dernière activité pour éviter la déconnexion prématurée
$_SESSION['LAST_ACTIVITY'] = time();

$pages_publiques = ['index.php', 'login.php', 'register.php', 'article.php'];

// Si l'utilisateur est déjà sur index.php, ne pas le rediriger encore
if (!in_array(basename($_SERVER['PHP_SELF']), $pages_publiques)) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../login.php");
        exit();
    }
}

$currentDir = basename(dirname(__FILE__)); 

if ($currentDir === 'admin') {
    // 🔥 Vérifier que l'utilisateur a bien les droits requis (admin ou rédacteur)
    if (!in_array($_SESSION['role'], ['admin', 'rédacteur'])) {
        echo '<script>
                alert("🚫 Accès refusé ! Vous allez être redirigé vers la page de connexion.");
                window.location.href = "../login.php";
            </script>';
        exit();
    }
}
?>
