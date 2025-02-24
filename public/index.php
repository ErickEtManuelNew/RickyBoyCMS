
<?php
require '../includes/db.php';
require_once '../includes/functions.php';
$articles = query("SELECT * FROM articles ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS Maison</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <h1>Bienvenue sur le Blog</h1>
    <?php if (!isset($_SESSION['user_id'])) : ?>
        <a href="login.php">Se connecter</a>
    <?php else : ?>
        <a href="dashboard.php">Tableau de bord</a>
    <?php endif; ?>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Gestion des Articles</h1>
        <div class="text-end mb-3">
            <a href="../admin/add_article.php" class="btn btn-success">Ajouter un article</a>
        </div>
        <div class="row">
            <?php foreach ($articles as $article): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h2 class="card-title"> <?= sanitize($article['title']) ?> </h2>
                            <p class="card-text"> <?= substr(sanitize($article['content']), 0, 200) ?>... </p>
                            <a class="btn btn-primary" href="article.php?id=<?= $article['id'] ?>">Lire la suite</a>
                            <a class="btn btn-warning" href="../admin/edit_article.php?id=<?= $article['id'] ?>">Modifier</a>
                            <a class="btn btn-danger" href="../admin/delete_article.php?id=<?= $article['id'] ?>" onclick="return confirm('Voulez-vous vraiment supprimer cet article ?');">Supprimer</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>