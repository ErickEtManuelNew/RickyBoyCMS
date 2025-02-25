<?php
require '../includes/db.php';
require '../includes/functions.php';

$targetDir = "../uploads/"; // Dossier d'upload

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php"); // Redirige vers la page de connexion
    exit();
}

// 🔥 Vérifier que l'utilisateur est admin ou rédacteur
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'rédacteur')) {
    header("Location: ../login.php"); // Redirection si non autorisé
    exit();
}

$id_utilisateur = $_SESSION['user_id']; // Récupérer l'ID de l'utilisateur connecté

if (!isset($_SESSION['user_id'])) {
    die("Erreur : Vous devez être connecté pour ajouter un article.");
}
$id_utilisateur = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $id_categorie = $_POST['id_categorie'];
    $id_utilisateur = $_SESSION['user_id']; // Associer l'article à l'utilisateur connecté

    // Vérifier si une image a été uploadée
    $image = NULL;
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "../uploads/";
        $fileName = basename($_FILES['image']['name']);
        $targetFilePath = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        // Afficher le format reçu pour diagnostic
        echo "Format reçu : $fileType <br>";

        // Vérification des formats autorisés
        $extensionsAutorisees = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($fileType, $extensionsAutorisees)) {
            die("Format d'image non autorisé. Seuls jpg, jpeg, png et gif sont acceptés.");
        }

        // Vérifier si le dossier existe, sinon le créer
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0775, true); // Création du dossier avec les permissions nécessaires
        }        

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
            $image = $fileName;
        } else {
            die("Erreur lors du téléchargement de l'image.");
        }
    }

    // Insérer l'article en base de données
    $sql = "INSERT INTO articles (title, content, created_at, image, id_categorie, id_utilisateur) 
            VALUES (:title, :content, NOW(), :image, :id_categorie, :id_utilisateur)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':title' => $title,
        ':content' => $content,
        ':image' => $image,
        ':id_categorie' => $id_categorie,
        ':id_utilisateur' => $id_utilisateur
    ]);

    // Récupérer l'ID du dernier article ajouté
    $lastArticleId = $pdo->lastInsertId();

    // Rediriger vers `article_success.php` avec l'ID de l'article
    header("Location: article_success.php?id=$lastArticleId");
    exit();
    }

// Récupérer les catégories pour le formulaire
$categories = query("SELECT * FROM categories")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Article</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <?php include '../includes/session_check.php'; ?>
        <h2 class="text-center">Ajouter un Nouvel Article</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Titre de l'article</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Contenu</label>
                <textarea name="content" class="form-control" rows="5" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Image</label>
                <input type="file" name="image" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Catégorie</label>
                <select name="id_categorie" class="form-select" required>
                    <option value="">Sélectionnez une catégorie</option>
                    <?php foreach ($categories as $cat) : ?>
                        <option value="<?= $cat['id_categorie'] ?>"><?= htmlspecialchars($cat['nom_categorie']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <center> <button type="submit" class="btn btn-primary">Ajouter l'article</button>
                <a href="dashboard.php" class="btn btn-secondary">Annuler</a>
            </center>
        </form>
    </div>
</body>
</html>
