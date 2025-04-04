<?php
session_start();

$error = isset($_SESSION['error']) ? $_SESSION['error'] : "";
$success = isset($_SESSION['success']) ? $_SESSION['success'] : "";
unset($_SESSION['error'], $_SESSION['success']);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Titre de la page -->
    <title>Connexion</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../css/style.css">

    <!-- BOOSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <!-- SDK Google authentification service -->
    <script src="https://accounts.google.com/gsi/client" async defer></script>

</head>

<body>

    <div class="container mt-5">
        <div class="card-container">

            <h2 class="mt-5">Connexion</h2>

            <?php if (isset($success) || isset($error)) echo "<p class='text-danger'>" . ($success ?? $error) . "</p>"; ?>

            <!-- Formulaire : connexion utilisateur -->
            <form method="post" action="../actions/process_login.php">
                <div class="mb-3">
                    <label class="form-label">Nom d'utilisateur :</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mot de passe :</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Se connecter</button>
            </form>

            <!-- Connexion Google Auth  -->
            <hr>
            <div class="d-flex justify-content-center">
                <div id="g_id_onload"
                    data-client_id="234689107098-9slru6dnpgkrsnl8j0c26qbacec4eavo.apps.googleusercontent.com" data-callback="handleCredentialResponse" data-auto_prompt="false">
                </div>

                <div class="g_id_signin" data-type="standard" data-shape="rectangular" data-theme="outline" data-text="sign_in_with" data-size="large">
                </div>
            </div>
            <hr>


            <!-- Récupérer son mot de passe -->
            <a href="../actions/reset_request.php " class="btn btn-warning">Mot de passe oublié</a>

            <!-- Créer un compte -->
            <a href="createAccount.php" class="btn btn-success">Créer un compte</a>

            <!-- Retour à l'acceuil (Liste des festivals) -->
            <a href="createAccount.php" class="btn btn-primary">Retour</a>

        </div>

        <!-- Connexion Google Auth -->
        <script src="../js/googleAuth.js" defer></script>


</body>

</html>