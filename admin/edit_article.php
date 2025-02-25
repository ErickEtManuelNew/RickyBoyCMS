<?php
require '../includes/db.php';
require '../includes/functions.php';

if (!isset($_GET['id'])) {
    die("Article introuvable.");
}

$article = query("SELECT * FROM articles WHERE id = ?", [$_GET['id']])->fetch();
if (!$article) {
    die("Article non trouvé.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitize($_POST['title']);
    $content = sanitize($_POST['content']);
    query("UPDATE articles SET title = ?, content = ? WHERE id = ?", [$title, $content, $_GET['id']]);
    header("Location: ../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un article</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    
    <div class="container mt-5">
        <?php include '../includes/session_check.php'; ?>        
        <h1 class="text-center mb-4">Modifier l'Article</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Titre</label>
                <input type="text" name="title" id="title" class="form-control" value="<?= sanitize($article['title']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Contenu</label>
                <textarea name="content" id="content" class="form-control" rows="5" required><?= sanitize($article['content']) ?></textarea>
            </div>
            <button type="submit" class="btn btn-warning">Mettre à jour</button>
            <a href="../index.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>