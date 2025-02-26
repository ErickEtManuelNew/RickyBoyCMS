<?php
include '../includes/session_check.php'; // Vérifie que l'utilisateur est connecté
require '../includes/db.php'; // Connexion à la base

// Vérifier si l'utilisateur est admin
if ($_SESSION['role'] !== 'admin') {
    echo '<script>
            alert("🚫 Accès refusé ! Seuls les admins peuvent modifier un utilisateur.");
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

$idToEdit = (int) $_GET['id'];

// Récupérer les infos de l'utilisateur
$stmt = query("SELECT * FROM users WHERE id = ?", [$idToEdit]);
$user = $stmt->fetch();

if (!$user) {
    echo '<script>
            alert("Utilisateur introuvable.");
            window.location.href = "manage_users.php";
          </script>';
    exit();
}

$error = '';

// Gestion de la mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = trim($_POST['prenom']);
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $role = ($user['id'] === $_SESSION['user_id']) ? 'admin' : $_POST['role'];

    // Vérifier que les champs ne sont pas vides
    if (empty($prenom) || empty($nom) || empty($email)) {
        $error = "Tous les champs sont obligatoires.";
    } else {
        // Vérifier si une nouvelle image est téléchargée
        $photo = $user['photo_utilisateur'];
        if (!empty($_FILES["photo"]["name"])) {
            $targetDir = "../uploads/";
            $fileName = time() . "_" . basename($_FILES["photo"]["name"]);
            $targetFilePath = $targetDir . $fileName;
            $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

            // Vérifier le format de l'image
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array($fileType, $allowedTypes)) {
                // Déplacer l'image téléchargée
                if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFilePath)) {
                    $photo = $fileName;
                } else {
                    $error = "Erreur lors du téléchargement de la photo.";
                }
            } else {
                $error = "Format d'image non autorisé. Seuls JPG, JPEG, PNG et GIF sont acceptés.";
            }
        }

        // Si pas d'erreur, mettre à jour l'utilisateur
        if (!$error) {
            query("UPDATE users SET prenom = ?, nom = ?, email = ?, role = ?, photo_utilisateur = ? WHERE id = ?", 
                  [$prenom, $nom, $email, $role, $photo, $idToEdit]);

            echo '<script>
                    alert("Utilisateur mis à jour avec succès !");
                    window.location.href = "manage_users.php";
                  </script>';
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Utilisateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">

<div class="card shadow p-4" style="width: 400px;">
    <h2 class="text-center mb-3">✏️ Modifier Utilisateur</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="text-center mb-3">
            <img src="../uploads/<?= htmlspecialchars($user['photo_utilisateur'] ?: 'default.png') ?>" 
                 alt="Photo de profil" class="rounded-circle" width="80" height="80">
        </div>
        
        <div class="mb-3">
            <label class="form-label">Prénom</label>
            <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($user['prenom']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nom</label>
            <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($user['nom']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Rôle</label>
            <select name="role" class="form-select" required <?= ($user['id'] === $_SESSION['user_id']) ? 'disabled' : '' ?>>
                <option value="utilisateur" <?= $user['role'] === 'utilisateur' ? 'selected' : '' ?>>Utilisateur</option>
                <option value="rédacteur" <?= $user['role'] === 'rédacteur' ? 'selected' : '' ?>>Rédacteur</option>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Nouvelle photo de profil (facultatif)</label>
            <input type="file" name="photo" class="form-control">
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-success">Mettre à jour</button>
        </div>
    </form>

    <hr>
    <div class="text-center">
        <a href="manage_users.php" class="btn btn-secondary">⬅ Retour</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
