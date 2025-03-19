<?php

require '../includes/config.php';
require '../actions/reset_request.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $token = $_POST['token'];
    $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Vérifier si le token est valide
    $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = ? AND expires > NOW()");
    $stmt->execute([$token]);
    $reset = $stmt->fetch();

    if(!$reset) {
        echo "Lien pas ok";

    }

    if ($reset) {
        // Mettre à jour le mot de passe de l'utilisateur
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->execute([$new_password, $reset['email']]);

        // Supprimer le token utilisé
        $stmt = $pdo->prepare("DELETE FROM password_resets WHERE email = ?");
        $stmt->execute([$reset['email']]);

        echo "Mot de passe mis à jour avec succès.";
        // Rediriger vers la page de connexion
        header("Location: ../pages/login.php");
        exit;
        
    } else {
        echo "Lien invalide ou expiré.";
    }
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reinitialiser votre mot de passe</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../css/style.css">

    <!-- BOOSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

</head>

<body>

    <div class="container">
        <!-- Carte principale -->
        <div class="card-container">

            <h2 class="mt-5">Nouveau mot de passe</h2>
            <p class="test-start">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ut voluptates illo nisi impedit nesciunt maiores voluptatibus praesentium velit, quam quo? Quibusdam culpa non mollitia ipsam harum consectetur ipsa in quod.</p>

            <!-- Formulaire : Reinitialiser son mot de passe -->
            <form method="post">

                <div class="mb-3">
                    <label class="form-label">Nouveau mot de passe :</label>
                    <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
                    <input type="password" name="password" class="form-control" placeholder="Nouveau mot de passe" required>
                    <input type="password" name="confirmPassword" class="form-control" placeholder="Nouveau mot de passe" required>
                </div>


                <!-- Valider la reinitialisation du mot de passe -->
                <button type="submit" class="btn btn-success">Reinitialiser</button>

            </form>
            <a href="../pages/login.php" class="btn btn-primary">Annuler</a>
        </div>
    </div>
</body>

</html>