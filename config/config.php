<?php
// 🔄 Définir l'environnement : 'dev' (local) ou 'prod' (en ligne)
$env = 'prod'; // ⬅ Change 'dev' en 'prod' pour passer en production

// 📌 Configuration selon l'environnement
$config = ($env === 'dev') ? [
    // 📌 Configuration base de données (DEV)
    'db_host' => 'localhost',
    'db_name' => 'cms_maison',
    'db_user' => 'cms_user',
    'db_pass' => '1IxDRgiA[-znLp5E',

    // 📌 Configuration SMTP (Mailtrap pour test)
    'smtp_host' => 'sandbox.smtp.mailtrap.io',
    'smtp_port' => 2525,
    'smtp_user' => '4b6a435cb07c96',
    'smtp_pass' => 'c06f668bbeb009',
    'smtp_secure' => 'tls',
    'email_from' => 'test@local.dev',
    'email_from_name' => 'RickyBoyCMS - Dev',

    'session_timeout' => 15 * 60, // ⏳ Timeout de session en secondes (15 minutes)
    'default_role' => 'utilisateur', // 🆕 Rôle par défaut lors de l'inscription    

    // 📌 Autres paramètres
    'debug' => true, // Mode debug activé
    'base_url' => 'http://localhost:8080/',
] : [
    // 📌 Configuration base de données (PROD)
    'db_host' => 'sql100.infinityfree.com',
    'db_name' => 'if0_38394326_cms_maison',
    'db_user' => 'if0_38394326',
    'db_pass' => 'yjm4Vynk6X3',

    // 📌 Configuration SMTP (Gmail ou autre service en production)
    'smtp_host' => 'smtp.gmail.com',
    'smtp_port' => 587,
    'smtp_user' => 'ericketmanuel@gmail.com',
    'smtp_pass' => 'qugaxhtfjdydwbgk', // ':SS4m%mKpm6:Y%rb'; // ⚠️ Utiliser un "mot de passe d'application" pour Gmail !
    'smtp_secure' => 'tls',
    'email_from' => 'ericketmanuel@gmail.com',
    'email_from_name' => 'RickyBoyCMS',

    'session_timeout' => 15 * 60, // ⏳ Timeout de session en secondes (15 minutes)
    'default_role' => 'utilisateur', // 🆕 Rôle par défaut lors de l'inscription    

    // 📌 Autres paramètres
    'debug' => false, // Mode debug désactivé
    'base_url' => 'https://rickyboycms.free.nf/',
];

// 📌 Connexion à la base de données
try {
    $pdo = new PDO(
        "mysql:host={$config['db_host']};dbname={$config['db_name']};charset=utf8",
        $config['db_user'],
        $config['db_pass'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    if ($config['debug']) {
        die("🛑 Erreur de connexion : " . $e->getMessage());
    } else {
        die("🛑 Erreur de connexion à la base de données. Contactez l'administrateur.");
    }
}

// 📌 Définition des autres constantes de l'application
if (!defined('BASE_URL')) {
    define('BASE_URL', $config['base_url']);
}

if (!defined('DEBUG_MODE')) {
    define('DEBUG_MODE', $config['debug']);
}

// 📌 Retourner la configuration pour pouvoir l’utiliser ailleurs
return $config;