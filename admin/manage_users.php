<?php
require '../includes/db.php';

$users = query("SELECT * FROM users")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center">👥 Gestion des Utilisateurs</h2>
        
        <!-- Bouton de retour au dashboard -->
        <div class="text-end mb-3">
            <a href="dashboard.php" class="btn btn-secondary">⬅ Retour au tableau de bord</a>
        </div>

        <table class="table table-striped text-center">
            <thead class="table-dark">
                <tr>
                    <th>Photo</th>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td>
                            <img src="../uploads/<?= htmlspecialchars($user['photo_utilisateur'] ?: 'default.png') ?>" 
                                 alt="Photo de <?= htmlspecialchars($user['prenom']) ?>" 
                                 class="rounded-circle" width="50" height="50">
                        </td>
                        <td><?= htmlspecialchars($user['prenom']) ?></td>
                        <td><?= htmlspecialchars($user['nom']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><span class="badge bg-primary"><?= htmlspecialchars($user['role']) ?></span></td>
                        <td>
                            <a class="btn btn-warning btn-sm" href="edit_user.php?id=<?= $user['id'] ?>">Modifier</a>

                            <?php if ($user['id'] !== $_SESSION['user_id']): ?>
                                <a class="btn btn-danger btn-sm" href="delete_user.php?id=<?= $user['id'] ?>" 
                                   onclick="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?');">
                                    Supprimer
                                </a>
                            <?php else: ?>
                                <button class="btn btn-secondary btn-sm" disabled>Impossible</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>