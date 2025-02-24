<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require '../includes/db.php'; // Connexion à la base

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Vérifier si l'utilisateur existe
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Démarrer la session et enregistrer l'utilisateur
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['prenom'] = $user['prenom'];
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['email'] = $user['email'];

        header("Location: ../admin/dashboard.php"); // Redirection après connexion
        exit();
    } else {
        $error = "Email ou mot de passe incorrect.";
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
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center">Connexion</h2>
        <?php if (isset($error)) : ?>
            <p class="alert alert-danger"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Mot de passe</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <center> <button type="submit" class="btn btn-primary">Se connecter</button>
                <a href="../public/index.php" class="btn btn-secondary">Annuler</a>
            </center>            
        </form>
    </div>
</body>
</html>
