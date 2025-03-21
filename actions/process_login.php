<?php
session_start();
require_once "../includes/config.php";
require_once "../includes/functions.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    global $pdo; 
    $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            "id" => $user['id'],
            "username" => $user['username']
        ];
        $_SESSION['success'] = "Connexion réussie !";
        Sentry\captureMessage("✅ Session start. Date/Time : " . date("F j, Y, g:i a") . " - username : " . $username); // Log
        header("Location: ../pages/festivals.php"); // Redirection après connexion
        exit;
    } else {
        $_SESSION['error'] = "Nom d'utilisateur ou mot de passe incorrect.";
        Sentry\captureMessage("❌ Session error. Date/Time : " . date("F j, Y, g:i a") . " - username : " . $username . ", password : " . $password); // Log
        header("Location: ../pages/login.php");
        exit;
    }
}
?>
