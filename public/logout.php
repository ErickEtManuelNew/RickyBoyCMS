<?php
session_start();
session_destroy();
header("Location: login.php"); // Retour à la page de connexion
exit();
