### /admin/delete_article.php
<?php
require '../includes/db.php';

if (!isset($_GET['id'])) {
    die("Article introuvable.");
}

query("DELETE FROM articles WHERE id = ?", [$_GET['id']]);
header("Location: ../public/index.php");
exit;
?>
