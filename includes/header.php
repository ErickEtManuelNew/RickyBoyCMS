<?php
// Démarrer la session si elle n'est pas déjà active
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si un utilisateur est connecté
$isLoggedIn = isset($_SESSION['user_id']);
$role = $isLoggedIn ? $_SESSION['role'] : null;
$user_display = $isLoggedIn ? "👤 Connecté en tant que <strong>" . htmlspecialchars($_SESSION['prenom']) . " " . htmlspecialchars($_SESSION['nom']) . " ($role)</strong>" : "";
?>

<!-- Barre de navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="../index.php">Ricky Boy CMS Maison</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if ($isLoggedIn): ?>
                    <?php if ($role === 'admin' || $role === 'rédacteur'): ?>
                        <li class="nav-item"><a class="nav-link" href="../admin/dashboard.php">📂 Tableau de bord</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link text-danger" href="../logout.php">🚪 Déconnexion</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="../login.php">🔑 Se connecter</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Affichage de l'utilisateur connecté -->
<div class="container mt-3">
    <p class="text-end text-muted"><?= $user_display ?></p>
</div>
