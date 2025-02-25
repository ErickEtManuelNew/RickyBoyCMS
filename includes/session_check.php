<?php
// Démarrer la session uniquement si elle n'est pas déjà active
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// 🔥 Vérifier que l'utilisateur est admin ou rédacteur
if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'rédacteur') {
    echo '<script>
            alert("🚫 Accès refusé ! Vous allez être redirigé vers la page de connexion.");
            window.location.href = "../login.php";
          </script>';
    exit();
}
?>
