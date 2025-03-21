<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../vendor/autoload.php';

use Dotenv\Dotenv;
use PDO;
use PDOException;
use Sentry;

// Charger les variables d'environnement
$dotenv = Dotenv::createImmutable(__DIR__ . "/../"); 
$dotenv->load();

// Vérifier que les variables d'environnement sont bien chargées
$env_vars = ['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS'];
foreach ($env_vars as $var) {
    if (!isset($_ENV[$var]) || empty($_ENV[$var])) {
        die("⚠️ Erreur : La variable d'environnement $var est manquante dans .env");
    }
}

// Initialisation de Sentry avec DSN sécurisé
Sentry\init(['dsn' => $_ENV['SENTRY_DSN']]);

try {
    $pdo = new PDO(
        "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'] . ";charset=utf8", $_ENV['DB_USER'], $_ENV['DB_PASS']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    Sentry\captureMessage("✅ Connexion BDD réussie. Date/Time : " . date("F j, Y, g:i a"));
} catch (PDOException $e) {
    Sentry\captureMessage("❌ Connexion BDD échouée : " . $e->getMessage());
    die("Erreur de connexion : " . $e->getMessage());
}

?>
