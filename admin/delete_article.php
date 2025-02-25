### /admin/delete_article.php
<?php
require '../includes/db.php';
include '../includes/session_check.php'; 

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// 🔥 Vérifier que l'utilisateur est admin ou rédacteur
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'rédacteur')) {
    echo '<script>
            alert("🚫 Accès refusé ! Vous allez être redirigé vers la page de connexion.");
            window.location.href = "../login.php";
          </script>';
    exit();
}

if (!isset($_GET['id'])) {
    die("Article introuvable.");
}

query("DELETE FROM articles WHERE id = ?", [$_GET['id']]);
// Détecter la page précédente et y retourner
$previousPage = $_SERVER['HTTP_REFERER'] ?? '../index.php';
header("Location: $previousPage");
exit;
?>
