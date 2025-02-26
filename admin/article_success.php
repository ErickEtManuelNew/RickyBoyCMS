<?php
require '../includes/db.php';
require '../includes/functions.php';
include '../includes/session_check.php';

if (!isset($_GET['id'])) {
    die("Erreur : Aucun article trouvé.");
}

$id_article = $_GET['id'];
$article = query("SELECT * FROM articles WHERE id = ?", [$id_article])->fetch();

if (!$article) {
    die("Erreur : Article introuvable.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Article Ajouté</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container-center {
            max-width: 600px;
            margin: 100px auto;
            padding: 20px;
            background: #fff;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-custom {
            margin-top: 10px;
            width: 100%;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container container-center">
        <h2 class="text-success">✅ Article ajouté avec succès !</h2>
        <p class="text-muted">Votre article <strong>"<?= htmlspecialchars($article['title']) ?>"</strong> a bien été publié.</p>

        <div class="mt-4">
            <a href="dashboard.php" class="btn btn-primary btn-custom">🔙 Retour aux articles</a>
            <a href="add_article.php" class="btn btn-success btn-custom">➕ Ajouter un nouvel article</a>
            <a href="../article.php?id=<?= $id_article ?>" class="btn btn-info btn-custom">👀 Voir l'article</a>
        </div>
    </div>
</body>
</html>
