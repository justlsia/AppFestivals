<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require '../includes/config.php'; 
require '../includes/functions.php'; 
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

$google_client_id = $_ENV['GOOGLE_CLIENT_ID'];

if (isset($_POST['credential'])) {
    $token = $_POST['credential'];
    $client = new Google_Client(['client_id' => $google_client_id]);
    $payload = $client->verifyIdToken($token);

    if ($payload) {

        $google_id = $payload['sub'];
        $email = $payload['email'];
        $username = $payload['name'];
        $name = $payload['name'];
        $firstname = $payload['name'];
        $profile_picture = $payload['picture'];

        // Requête : Rechercher un utilisateur par son google_id ou son email
        $user = getUserByEmailOrGoogleId($email, $google_id);

        if ($user) {
            if (empty($user['google_id'])) {
                // Associe l'ID Google à l'utilisateur existant
                UpdateGoogleIdByEmail($google_id, $email);
            }
        
            // Stocke correctement les infos utilisateur en session
            $_SESSION['user'] = [
                "id" => $user['id'],
                "username" => $user['username'],
                "email" => $user['email'],
                "firstname" => $user['firstname'],
                "name" => $user['name']
            ];
        } else {
            // Créer un utilisateur Google
            $newUserId = addUserByGoogleAuth($google_id, $email, $username, $name, $firstname, $profile_picture);
        
            if ($newUserId) {
                $_SESSION['user'] = [
                    "id" => $newUserId,
                    "username" => $name,
                    "email" => $email,
                    "firstname" => $firstname,
                    "name" => $name,
                    "profile_picture" => $profile_picture
                ];
            } else {
                echo json_encode(["success" => false, "message" => "Erreur lors de l'enregistrement de l'utilisateur"]);
                exit;
            }
        }
        
        // Retourne la réponse JSON
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Token invalide"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Aucun token reçu"]);
}


?>
