<?php
session_start();
require_once "../includes/config.php";
require_once "../includes/functions.php";


try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupérer les paramètres de la requête
        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);

        // Vérifier si l'utilisateur existe
        $user = getUserByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                "id" => $user['id'],
                "username" => $user['username'],
                "administrateur" => $user['administrateur']
            ];
            $_SESSION['success'] = "Connexion réussie !";
            Sentry\captureMessage("✅ Session start. Date/Time : " . date("F j, Y, g:i a") . " - username : " . $username);
            header("Location: ../pages/festivals.php");
            exit;
        } 
    }
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    Sentry\captureMessage("❌ Session error. Date/Time : " . date("F j, Y, g:i a") . " - username : " . $username);
    header("Location: ../pages/login.php");
    exit;
}

?>
