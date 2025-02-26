<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require './includes/db.php'; // Connexion à la base

// Si un timeout s'est produit, réinitialiser la session proprement
if (isset($_GET['timeout'])) {
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_unset();
        session_destroy();
        session_start(); // 🔥 Redémarre une session propre
        session_regenerate_id(true);
    }
    $error = 'Votre session a expiré pour inactivité. Veuillez vous reconnecter.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = query("SELECT * FROM users WHERE email = ?", [$email]);
    $user = $stmt->fetch();

    if ($user) {
        // 🔥 Vérifier si l'email a été confirmé
        if ($user['email_verifie'] == 0) {
            $error = "Votre email n'a pas été vérifié. Veuillez confirmer votre adresse avant de vous connecter.";
        } elseif (password_verify($password, $user['password'])) {
            // 🔥 Stocker l'utilisateur en session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['prenom'] = $user['prenom'];
            $_SESSION['nom'] = $user['nom'];
            $_SESSION['role'] = $user['role'];

            header("Location: index.php");
            exit();
        } else {
            $error = "Identifiants incorrects.";
        }
    } else {
        $error = "Identifiants incorrects.";
    }    
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">

<div class="card shadow p-4" style="width: 400px;">
    <h2 class="text-center mb-3">🔐 Connexion</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">Email :</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe :</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Se connecter</button>
        </div>
    </form>

    <hr>
    <div class="text-center">
        <p>Pas encore inscrit ? <a href="register.php" class="text-decoration-none">Créer un compte</a></p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

