<?php
require '../includes/db.php';
require '../includes/functions.php';
include '../includes/session_check.php'; // Vérifie si l'utilisateur est connecté

// Récupérer les articles avec leur catégorie
$articles = query("
    SELECT a.*, c.nom_categorie
    FROM articles a
    LEFT JOIN categories c ON a.id_categorie = c.id_categorie
    ORDER BY a.created_at DESC
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .dashboard-container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #ddd;
        }
        .table thead {
            background: #007bff;
            color: white;
        }
        .btn-logout {
            background: #dc3545;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
        }
        .btn-logout:hover {
            background: #c82333;
        }
    </style>
</head>
<body class="bg-light">

<div class="container dashboard-container">
    <div class="header">
        <h2>👋 Bienvenue, <?= htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']) ?></h2>
        <a href="../logout.php" class="btn-logout">Déconnexion</a>
    </div>

    <div class="mt-4">
        <a href="add_article.php" class="btn btn-primary">➕ Ajouter un article</a>
    </div>

    <div class="mt-4">
    <!-- Tableau pour organisation des boutons -->
        <table class="table-controls">
            <tr>
                <td class="text-end">
                    <div class="full-width">
                        <?php if ($_SESSION['role'] === 'admin') : ?>
                            <a href="manage_users.php" class="btn btn-secondary">👥 Gérer les utilisateurs</a>
                        <?php endif; ?>
                        <a href="../index.php" class="btn btn-secondary ms-auto">⬅ Retour à l'accueil</a>
                    </div>
                </td>
            </tr>
        </table>
        <br> <br>
        <h4>📜 Liste des articles</h4>
        <?php if (count($articles) > 0) : ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Catégorie</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articles as $article) : ?>
                        <tr>
                            <td><?= sanitize($article['title']) ?></td>
                            <td><?= htmlspecialchars($article['nom_categorie'] ?? 'Non classé') ?></td>
                            <td><?= date('d-m-Y', strtotime($article['created_at'])) ?></td>
                            <td>
                            <?php if (isset($_SESSION['user_id']) && ($_SESSION['role'] === 'admin' || $_SESSION['user_id'] == $article['id_utilisateur'])): ?>
                                <a href="edit_article.php?id=<?= urlencode($article['id']) ?>" class="btn btn-warning btn-sm">✏️ Modifier</a>
                                <a href="delete_article.php?id=<?= urlencode($article['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous vraiment supprimer cet article ?')">🗑 Supprimer</a>
                            <?php endif; ?>                            
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p class="text-muted mt-3">Aucun article trouvé.</p>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
