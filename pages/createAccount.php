<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Créer un compte</title>

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

    <!-- Containeur : Créer un compte -->
    <div class="container">
        <div class="card-container">

            <?php if (isset($success) || isset($error)) echo "<p class='text-danger'>" . ($success ?? $error) . "</p>"; ?>

            <h2 class="mt-5">Créer un compte</h2>
            <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Architecto eius reiciendis eligendi at
                perferendis iusto. Necessitatibus itaque quae, nostrum mollitia, facilis tenetur sunt adipisci excepturi
                officia debitis sint ipsa vel.</p>

            <!-- Formulaire : Création d'un nouvel utilisateur -->
            <form id="registerForm" method="post" action="../actions/add_user.php">
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

                <!-- Valider la création d'un nouvel utilisateur -->
                <button type="submit" class="btn btn-primary">Créer mon compte</button>
            </form>

        <!-- Retour à l'acceuil (Liste des festivals) -->
        <a href="festivals.php" class="btn btn-success">Retour à l'accueil</a>

    </div>

    <!-- Vérification des informations entrées -->
    <!--<script src="../js/createAccount.js" defer></script>-->

</body>

</html>