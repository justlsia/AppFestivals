<?php

$host = 'localhost';
$dbname = 'festival_db';
$username = 'userFestival'; 
$password = '4S6aF4lzBo7Nu3S9nsmM'; 

// Serveur de logs GlitchTip
require '../vendor/autoload.php';
Sentry\init(['dsn' => 'http://c1885b9abaac4e638e180b3d456dea08@172.16.0.100:8000/2' ]);

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    Sentry\captureMessage("✅ Connexion BDD sucess. Date/Time : " . date("F j, Y, g:i a"));
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
    Sentry\captureMessage("❌ Connexion BDD error. Date/Time : " . date("F j, Y, g:i a"));
}

?>
