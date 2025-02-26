<?php
require './includes/db.php'; // Connexion à la base de données

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Vérifier si le token existe et n'a pas expiré
    $stmt = $pdo->prepare("SELECT * FROM users WHERE token = ? AND email_verifie = 0 AND token_expiration > NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        // Confirmer l'email et supprimer le token
        $stmt = $pdo->prepare("UPDATE users SET email_verifie = 1, token = NULL, token_expiration = NULL WHERE token = ?");
        $stmt->execute([$token]);

        // Message de succès et lien vers la connexion
        $message = "✅ Votre email a été confirmé avec succès ! Vous pouvez maintenant vous connecter.";
        $alertType = "success";
    } else {
        // Token invalide ou expiré
        $message = "⚠️ Lien de confirmation invalide ou expiré. Veuillez vous inscrire à nouveau.";
        $alertType = "danger";
    }
} else {
    $message = "❌ Aucun token fourni.";
    $alertType = "danger";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation Email</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">

<div class="card shadow p-4 text-center" style="max-width: 400px;">
    <h2>📩 Confirmation de l'email</h2>
    <div class="alert alert-<?= $alertType ?>"><?= $message ?></div>

    <?php if ($alertType === "success"): ?>
        <a href="login.php" class="btn btn-primary">Se connecter</a>
    <?php else: ?>
        <a href="register.php" class="btn btn-secondary">S'inscrire à nouveau</a>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
