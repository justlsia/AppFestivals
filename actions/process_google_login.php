<?php
session_start();
require '../includes/config.php'; 
require '../vendor/autoload.php';

use Google\Client as Google_Client;
use Google\Service\Oauth2;

ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

$google_client_id = "234689107098-9slru6dnpgkrsnl8j0c26qbacec4eavo.apps.googleusercontent.com";

if (isset($_POST['credential'])) {
    $token = $_POST['credential'];
    $client = new Google_Client(['client_id' => $google_client_id]);
    $payload = $client->verifyIdToken($token);

    if ($payload) {
        $google_id = $payload['sub'];
        $email = $payload['email'];
        $name = $payload['name'];

        // Vérifie si l'utilisateur existe déjà
        $stmt = $pdo->prepare("SELECT * FROM users WHERE google_id = ? OR email = ?");
        $stmt->execute([$google_id, $email]);
        $user = $stmt->fetch();

        if ($user) {
            if (empty($user['google_id'])) {
                $stmt = $pdo->prepare("UPDATE users SET google_id = ? WHERE email = ?");
                $stmt->execute([$google_id, $email]);
            }
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
        } else {
            // Ajoute un nouvel utilisateur
            $stmt = $pdo->prepare("INSERT INTO users (google_id, email, username) VALUES (?, ?, ?)");
            $stmt->execute([$google_id, $email, $name]);
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['username'] = $name;

        }

        // Ajoute l'email en session
        $_SESSION['user'] = true; // Indique que l'utilisateur est connecté (afficher Gérer dans le header)
        $_SESSION['user_email'] = $email;

        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Token invalide"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Aucun token reçu"]);
}


?>
