<?php
require './includes/session_check.php'; // Vérification de session et timeout
require './config/config.php'; // Charger la configuration
require './includes/db.php'; // Connexion à la base de données
require './includes/functions.php';

// Récupérer les articles avec leurs catégories
$articles = query("SELECT a.*, c.nom_categorie FROM articles a 
                   JOIN categories c ON a.id_categorie = c.id_categorie
                   ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ricky Boy CMS Maison</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .article-img {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }
        .card-text {
            color: #555;
        }
        .nav-link {
            color: white !important;
        }
        .card {
            transition: transform 0.2s ease-in-out;
        }
        .card:hover {
            transform: scale(1.02);
        }
    </style>
</head>
<body class="bg-light">
    <?php include './includes/header.php'; ?> <!-- Ajout du header global -->

    <div class="container mt-5">
        <h1 class="text-center mb-4">📜 Liste des Articles</h1>

        <div class="row">
            <?php if (count($articles) > 0): ?>
                <?php foreach ($articles as $article): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="position-relative">
                                <?php if (!empty($article['image'])): ?>
                                    <img src="uploads/<?= sanitize($article['image']) ?>" class="card-img-top article-img" alt="Image de l'article">
                                <?php else: ?>
                                    <img src="assets/default.jpg" class="card-img-top article-img" alt="Image par défaut">
                                <?php endif; ?>
                            </div>
                            <div class="card-body">
                                <h2 class="card-title"><?= sanitize($article['title']) ?></h2>
                                <p class="card-text">
                                    <small class="text-muted">
                                        Publié le <?= date('d-m-Y', strtotime($article['created_at'])) ?> | 
                                        Catégorie : <?= sanitize($article['nom_categorie']) ?>
                                    </small>
                                </p>
                                <p class="card-text"><?= htmlspecialchars(mb_strimwidth($article['content'], 0, 200, "...")) ?></p>
                                <a class="btn btn-primary" href="article.php?id=<?= $article['id'] ?>">Lire la suite</a>

                                <!-- Boutons Modifier et Supprimer (Seulement pour Admin et Auteur) -->
                                <?php if (isset($_SESSION['user_id']) && ($_SESSION['role'] === 'admin' || $_SESSION['user_id'] == $article['id_utilisateur'])): ?>
                                    <a class="btn btn-warning" href="admin/edit_article.php?id=<?= $article['id'] ?>">Modifier</a>
                                    <a class="btn btn-danger" href="admin/delete_article.php?id=<?= $article['id'] ?>" onclick="return confirm('Voulez-vous vraiment supprimer cet article ?');">Supprimer</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center text-muted">Aucun article disponible.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
