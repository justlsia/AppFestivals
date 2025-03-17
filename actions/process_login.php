<?php
session_start();
require_once "../includes/config.php";
require_once "../includes/functions.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    global $pdo; // Assurez-vous que $pdo est accessible
    $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            "id" => $user['id'],
            "username" => $user['username']
        ];
        $_SESSION['success'] = "Connexion réussie !";
        
        header("Location: ../pages/festivals.php"); // Redirection après connexion
        exit;
    } else {
        $_SESSION['error'] = "Nom d'utilisateur ou mot de passe incorrect.";
        header("Location: ../pages/login.php");
        exit;
    }
}
?>
