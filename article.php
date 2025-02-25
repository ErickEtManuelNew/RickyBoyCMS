<?php
require './includes/db.php';
require './includes/functions.php';

if (!isset($_GET['id'])) {
    die("Article introuvable.");
}

// Récupérer l'article, son auteur et la catégorie en une seule requête
$article = query("
    SELECT a.*, c.nom_categorie, u.prenom, u.nom, u.photo_utilisateur 
    FROM articles a
    JOIN categories c ON a.id_categorie = c.id_categorie
    JOIN users u ON a.id_utilisateur = u.id
    WHERE a.id = ?
", [$_GET['id']])->fetch();

if (!$article) {
    die("Article non trouvé.");
}

$photo_auteur = !empty($article['photo_utilisateur']) ? '../uploads/' . $article['photo_utilisateur'] : '../uploads/default.png';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= sanitize($article['title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .article-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .article-header {
            text-align: center;
        }
        .article-title {
            font-size: 2rem;
            font-weight: bold;
        }
        .article-meta {
            color: gray;
            font-size: 0.9rem;
        }
        .article-content {
            margin-top: 20px;
            font-size: 1.1rem;
            line-height: 1.6;
        }
        .author-info {
            display: flex;
            align-items: center;
            margin-top: 20px;
            padding: 10px;
            border-top: 1px solid #ddd;
        }
        .author-photo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px;
        }
    </style>
</head>
<body class="bg-light">

<div class="container">
    <div class="article-container">
        <div class="article-header">
            <h1 class="article-title"><?= sanitize($article['title']) ?></h1>
            <p class="article-meta">
                Publié le <?= date('d-m-Y', strtotime($article['created_at'])) ?> |
                Catégorie: <strong><?= sanitize($article['nom_categorie']) ?></strong>
            </p>
            <?php if (!empty($article['image'])) : ?>
                <img src="../uploads/<?= sanitize($article['image']) ?>" class="img-fluid rounded mt-3" alt="Image de l'article">
            <?php endif; ?>
        </div>

        <div class="article-content">
            <p><?= nl2br(sanitize($article['content'])) ?></p>
        </div>

        <div class="author-info">
            <img src="<?= $photo_auteur ?>" class="author-photo" alt="Photo de l'auteur">
            <div>
                <p><strong>Écrit par :</strong> <?= sanitize($article['prenom'] . ' ' . $article['nom']) ?></p>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="#" onclick="window.history.back(); return false;" class="btn btn-primary">Retour</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
