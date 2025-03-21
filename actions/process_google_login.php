<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require '../includes/config.php'; 
require '../vendor/autoload.php';

use Dotenv\Dotenv;
use PDO;
use PDOException;
use Google\Client as Google_Client;
use Google\Service\Oauth2;


// Charger les variables d'environnement
$dotenv = Dotenv::createImmutable(__DIR__ . "/../"); 
$dotenv->load();


header('Content-Type: application/json');

//$google_client_id = "234689107098-9slru6dnpgkrsnl8j0c26qbacec4eavo.apps.googleusercontent.com";
$google_client_id = $_ENV['GOOGLE_CLIENT_ID'];

if (isset($_POST['credential'])) {
    $token = $_POST['credential'];
    $client = new Google_Client(['client_id' => $google_client_id]);
    $payload = $client->verifyIdToken($token);

    if ($payload) {
        $google_id = $payload['sub'];
        $email = $payload['email'];
        $name = $payload['name'];

        // Requête : Rechercher un utilisateur par sin google_id ou son email
        $req = "SELECT * FROM users WHERE google_id = :google_id OR email = :email";
        $stmt = $pdo->prepare($req);

        $stmt->bindParam(':google_id',$google_id);
        $stmt->bindParam(':email',$email);

        $stmt->execute();
        $user = $stmt->fetch();

        if ($user) {
            if (empty($user['google_id'])) {
                // Requête : Mettre à jour un google_id selon un email
                $req = "UPDATE users SET google_id = :google_id WHERE email = :email";
                $stmt = $pdo->prepare($req);

                $stmt->bindParam(':google_id',$google_id);
                $stmt->bindParam(':email',$email);

                $stmt->execute();

            }
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
        } else {
            // Requête : Ajouter un utilisateur 
            $req = "INSERT INTO users (google_id, email, username) VALUES (:google_id, :email, :username)";
            $stmt = $pdo->prepare($req);

            $stmt->bindParam(':google_id',$google_id);
            $stmt->bindParam(':email',$email);
            $stmt->bindParam(':username',$name);


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
