<?php
session_start();
session_destroy();
header("Location: index.php"); // Retour à la page de connexion
exit();
