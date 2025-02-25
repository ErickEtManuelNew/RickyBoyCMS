<?php
// Vérifie la session et que l'utilisateur est admin (défini dans session_check.php)
include '../includes/session_check.php';

// Connexion à la base
require '../includes/db.php';

// Vérifier que l'utilisateur est admin (si session_check ne le fait pas déjà)
if ($_SESSION['role'] !== 'admin') {
    echo '<script>
            alert("🚫 Accès refusé ! Seuls les admins peuvent supprimer un utilisateur.");
            window.location.href = "manage_users.php";
          </script>';
    exit();
}

// Vérifier si un id est passé en GET
if (!isset($_GET['id'])) {
    echo '<script>
            alert("Aucun utilisateur sélectionné.");
            window.location.href = "manage_users.php";
          </script>';
    exit();
}

$idToDelete = (int) $_GET['id'];
$loggedUserId = (int) $_SESSION['user_id'];

// Empêcher l'utilisateur connecté de se supprimer lui-même
if ($idToDelete === $loggedUserId) {
    echo '<script>
            alert("Vous ne pouvez pas vous supprimer vous-même !");
            window.location.href = "manage_users.php";
          </script>';
    exit();
}

// Vérifier si l'utilisateur existe en base
$stmt = query("SELECT id FROM users WHERE id = ?", [$idToDelete]);
if ($stmt->rowCount() === 0) {
    echo '<script>
            alert("Utilisateur introuvable.");
            window.location.href = "manage_users.php";
          </script>';
    exit();
}

// Supprimer l'utilisateur
query("DELETE FROM users WHERE id = ?", [$idToDelete]);

echo '<script>
        alert("Utilisateur supprimé avec succès !");
        window.location.href = "manage_users.php";
      </script>';
exit();
