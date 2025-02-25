<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require './includes/db.php'; // Connexion à la base

$error = '';
$targetDir = "uploads/"; // Dossier où stocker les images

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = trim($_POST['prenom'] ?? '');
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Vérifier que tous les champs obligatoires sont remplis
    if (empty($prenom) || empty($nom) || empty($email) || empty($password)) {
        $error = "Tous les champs sont obligatoires sauf la photo.";
    } else {
        // Vérifier si l'email existe déjà
        $stmt = query("SELECT id FROM users WHERE email = ?", [$email]);
        if ($stmt->rowCount() > 0) {
            $error = "Un compte existe déjà avec cet email.";
        } else {
            // Hacher le mot de passe
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Gérer l'upload de l'image (si fournie)
            $photo = "default.png"; // Image par défaut
            if (!empty($_FILES["photo"]["name"])) {
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

            // Si pas d'erreur, insérer en base
            if (!$error) {
                query("INSERT INTO users (prenom, nom, email, password, role, photo_utilisateur) 
                       VALUES (?, ?, ?, ?, 'utilisateur', ?)", 
                       [$prenom, $nom, $email, $hashedPassword, $photo]);

                // Connexion automatique après l'inscription
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['prenom'] = $prenom;
                $_SESSION['nom'] = $nom;
                $_SESSION['role'] = 'utilisateur';
                $_SESSION['photo_utilisateur'] = $photo;

                header("Location: index.php");
                exit();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">

<div class="card shadow p-4" style="width: 400px;">
    <h2 class="text-center mb-3">📝 Inscription</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Prénom</label>
            <input type="text" name="prenom" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nom</label>
            <input type="text" name="nom" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Mot de passe</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Photo de profil (facultatif)</label>
            <input type="file" name="photo" class="form-control">
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Créer un compte</button>
        </div>
    </form>

    <hr>
    <div class="text-center">
        <p>Déjà un compte ? <a href="login.php" class="text-decoration-none">Se connecter</a></p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
