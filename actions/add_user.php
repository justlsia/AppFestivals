<?php
session_start();
require_once "../includes/functions.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Récupérer les paramètres de la requête
    $name = htmlspecialchars(trim($_POST["name"]));
    $firstname = htmlspecialchars(trim($_POST["firstname"]));
    $username = htmlspecialchars(trim($_POST["username"]));
    $age = filter_var($_POST["age"], FILTER_VALIDATE_INT);
    $email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];

    // Vérifier si tous les champs sont remplis
    if (!$name || !$firstname || !$username || !$age || !$email || !$password || !$confirmPassword) {
        $_SESSION['error'] = "Tous les champs sont obligatoires.";
        header("Location: ../pages/createAccount.php");
        exit;
    }

    // Vérifier si les mots de passe correspondent
    if ($password !== $confirmPassword) {
        $_SESSION['error'] = "Les mots de passe ne correspondent pas.";
        header("Location: ../pages/createAccount.php");
        exit;
    }

    
    // Enregistrer l'utilisateur
    $message = registerUser($name, $firstname, $username, $age, $email, $password);

    if ($message === "Compte créé avec succès !") {
        $_SESSION['success'] = $message;
        $_SESSION['username'] = $name;
        Sentry\captureMessage("✅ Add new user. Date/Time : " . date("F j, Y, g:i a") . " - username : " . $username . "Name festival : " . $name ); // Log    

        header("Location: ../pages/festivals.php");
        
    } else {
        $_SESSION['error'] = $message;
        header("Location: ../pages/createAccount.php");
    }

    exit;
}
?>
