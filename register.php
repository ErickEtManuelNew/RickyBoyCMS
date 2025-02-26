<?php
$config = require __DIR__ . '/config/config.php';
require './includes/db.php'; // Connexion à la base
require './vendor/autoload.php'; // Charger PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$error = '';
$targetDir = "uploads/"; // Dossier pour stocker les images

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = trim($_POST['prenom'] ?? '');
    $nom = trim($_POST['nom'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password'] ?? '');

    // Vérifier que tous les champs obligatoires sont remplis
    if (empty($prenom) || empty($nom) || empty($email) || empty($password)) {
        $error = "Tous les champs sont obligatoires sauf la photo.";
    } else {
        // Vérifier si l'email existe déjà
        $stmt = query("SELECT id FROM users WHERE email = ?", [$email]);
        if ($stmt->rowCount() > 0) {
            $error = "Un compte existe déjà avec cet email.";
        } else {
            // Hacher le mot de passe
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Générer un token de validation
            $token = bin2hex(random_bytes(50)); 
            $expiration = date('Y-m-d H:i:s', strtotime('+24 hours')); // Expiration en 24h

            // Gérer l'upload de l'image (si fournie)
            $photo = "default.png"; // Image par défaut
            if (!empty($_FILES["photo"]["name"])) {
                $fileName = time() . "_" . basename($_FILES["photo"]["name"]);
                $targetFilePath = $targetDir . $fileName;
                $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

                // Vérifier le format de l'image
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
                if (in_array($fileType, $allowedTypes)) {
                    // Déplacer l'image téléchargée
                    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFilePath)) {
                        $photo = $fileName;
                    } else {
                        $error = "Erreur lors du téléchargement de la photo.";
                    }
                } else {
                    $error = "Format d'image non autorisé. Seuls JPG, JPEG, PNG et GIF sont acceptés.";
                }
            }

            // Si pas d'erreur, insérer en base
            if (!$error) {
                query("INSERT INTO users (prenom, nom, email, password, role, photo_utilisateur, email_verifie, token, token_expiration) 
                       VALUES (?, ?, ?, ?, 'utilisateur', ?, 0, ?, ?)", 
                       [$prenom, $nom, $email, $hashedPassword, $photo, $token, $expiration]);

                // Envoyer un email de confirmation
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = $config['smtp_host'];
                    $mail->SMTPAuth = true;
                    $mail->Username = $config['smtp_user']; 
                    $mail->Password = $config['smtp_pass'];
                    $mail->SMTPSecure = $config['smtp_secure'];
                    $mail->Port = $config['smtp_port'];
                
                    // Expéditeur et destinataire
                    $mail->setFrom($config['email_from'], $config['email_from_name']);
                    $mail->addAddress($email, $prenom . ' ' . $nom);                    

                    // Contenu de l'email
                    $mail->isHTML(true);
                    $mail->Subject = "Confirmez votre email - RickyBoyCMS";
                    $mail->Body = "Bonjour $prenom,<br><br>
                    Merci de vous être inscrit. Cliquez sur ce lien pour confirmer votre email :<br><br>
                    <a href='". BASE_URL ."confirm_email.php?token=$token'>Confirmer mon email</a><br><br>
                    Ce lien expirera dans 24 heures.";
                    $mail->send();
                    header("Location: confirmation_sent.php");
                    exit();
                } catch (Exception $e) {
                    $error = "L'envoi de l'email a échoué : " . $mail->ErrorInfo;
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">

<div class="card shadow p-4" style="width: 400px;">
    <h2 class="text-center mb-3">📝 Inscription</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Prénom</label>
            <input type="text" name="prenom" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nom</label>
            <input type="text" name="nom" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Mot de passe</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Photo de profil (facultatif)</label>
            <input type="file" name="photo" class="form-control">
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Créer un compte</button>
        </div>
    </form>

    <hr>
    <div class="text-center">
        <p>Déjà un compte ? <a href="login.php" class="text-decoration-none">Se connecter</a></p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
