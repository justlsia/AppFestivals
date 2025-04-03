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
    $profile_picture = $_POST["profile_picture"];
    $administrateur = $_POST["administrateur"];

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

    // Si pas de photo de profil, en mettre une par défaut
    $profile_picture = isset($_POST["profile_picture"]) && !empty($_POST["profile_picture"]) ? $_POST["profile_picture"] : '../uploads/default_avatar.svg';

    // Créer l'utilisateur
    $message = createUser($name, $firstname, $username, $age, $email, $password, $profile_picture, $administrateur);

    if ($message === "Compte créé avec succès !") {
        $_SESSION['success'] = $message;
        $_SESSION['username'] = $name;
        Sentry\captureMessage("✅ Add new user. Date/Time : " . date("F j, Y, g:i a") . " - username : " . $username . "Name festival : " . $name ); // Log    

        header("Location: ../pages/administration.php");
        
    } else {
        $_SESSION['error'] = $message;
        header("Location: ../pages/administration.php");
    }

    exit;
}
?>



<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Créer un utilisateur</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../css/style.css">

    <!-- BOOSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

</head>

<body>

    <!-- Containeur : Créer un utilisateur -->
    <div class="container">
        <div class="card-container">

            <?php if (isset($success) || isset($error)) echo "<p class='text-danger'>" . ($success ?? $error) . "</p>"; ?>

            <h2 class="mt-5">Créer un utilisateur</h2>

            <!-- Formulaire : Création d'un utilisateur -->
            <form id="createUserForm" method="post">
                <div class="mb-3">
                    <label class="form-label">Nom : </label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Prénom : </label>
                    <input type="text" name="firstname" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nom d'utilisateur : </label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">URL de la photo de profil : </label>
                    <input type="text" name="profile_picture" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Age : </label>
                    <input type="number" name="age" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email :</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mot de passe : </label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirmation du mot de passe : </label>
                    <input type="password" name="confirmPassword" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Compte administrateur :</label>
                    <select class="form-select" name="administrateur" aria-label="Default select example">
                        <option value="0">Non</option>
                        <option value="1">Oui</option>
                    </select>
                </div>

                <!-- Valider la création d'un nouvel utilisateur -->
                <button type="submit" class="btn btn-primary">Créer le compte</button>
            </form>

        <!-- Retour à l'acceuil (Liste des festivals) -->
        <a href="../pages/administration.php" class="btn btn-success">Annuler</a>

    </div>

    <!-- Vérification des informations entrées -->
    <!--<script src="../js/createAccount.js" defer></script>-->

</body>

</html>