<?php

session_start();
require '../includes/functions.php';


// Vérification si l'utilisateur est connecté
if (empty($_SESSION['user']['username'])) {
    die("Erreur : Aucun utilisateur connecté. ❌");
}


if (!$user || empty($user['id'])) {
    die("Erreur : Utilisateur introuvable ou ID non valide. ❌" );
}
    


// Vérifier que l'id de l'utilisateur à modifier est présent dans l'URL
if (!isset($_GET['id'])) {
    header('Location: ../pages/about.php'); // PB
    exit();
}

// Récupérer l'utilisateur à modifier selon son id
$id = $_GET['id'];
$userUpdate = getUserProfile($id);

// Si l'utilisateur à modifier n'existe pas, redirection
if (!$userUpdate) {
    header('Location: ../pages/administration.php');
    exit();
}

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($id)) {
    // Récupération des paramètres de la requête
    $username = $_POST['username'];
    $name = $_POST['name'];
    $firstname = $_POST['firstname'];
    $age = $_POST['age'];
    $email = $_POST['email'];
    $profile_picture = $_POST['profile_picture'];
    $administrateur = $_POST['administrateur'];

    // Mise à jour de l'utilisateur
    if (updateUserProfile($id, $username, $name, $firstname, $age, $email, $profile_picture, $administrateur)) {

        echo "Modification de l'utilisateur avec succès. ✅";
        $_SESSION['success'] = "Modification de l'utilisateur avec succès.";
        Sentry\captureMessage("✅ Edit user. Date/Time : " . date("F j, Y, g:i a") . " - username : " . $username . " - name user : " . $name ); // Log    
        header("Location: ../pages/administration.php");
        exit();
    } else {
        $error = "Erreur lors de la mise à jour de l'utilisateur.";
        Sentry\captureMessage("❌ Error edit user. Date/Time : " . date("F j, Y, g:i a") . " - username : " . $username . " - name user : " . $name ); // Log    
    }
}
    
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Modifier un utilisateur</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../css/style.css">

    <!-- BOOSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body>

    <div class="container">
        <div class="card-container">

            <h2 class="mt-5">Modifier l'utilisateur 👤</h2>
            <hr>

            <!-- Vérifier si l'utilisateur est bien chargé -->
            <?php if (isset($success) || isset($error)) echo "<p class='text-danger'>" . ($success ?? $error) . "</p>"; ?>

            <!-- Formulaire de mise à jour d'un utilisateur -->
            <form method="post">

                <div class="mb-3">
                    <label class="form-label">Nom d'utilisateur :</label>
                    <input type="text" name="username" class="form-control"
                        value="<?= htmlspecialchars($userUpdate['username']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nom :</label>
                    <input type="text" name="name" class="form-control"
                        value="<?= htmlspecialchars($userUpdate['name']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Prénom :</label>
                    <input type="text" name="firstname" class="form-control"
                        value="<?= htmlspecialchars($userUpdate['firstname']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email (non modifiable) :</label>
                    <input type="text" name="email" class="form-control"
                        value="<?= htmlspecialchars($userUpdate['email']) ?>" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Age :</label>
                    <input type="number" name="age" class="form-control"
                        value="<?= htmlspecialchars($userUpdate['age']) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Compte administrateur :</label>
                    <select class="form-select" name="administrateur" aria-label="Default select example">
                        <option value="0" <?php if ($userUpdate['administrateur'] == 0) echo 'selected'; ?>>Non</option>
                        <option value="1" <?php if ($userUpdate['administrateur'] == 1) echo 'selected'; ?>>Oui</option>
                    </select>
                </div>

                <!-- Mettre à jour l'utilisateur -->
                <button type="submit" class="btn btn-warning">Modifier</button>

                <a href="../pages/administration.php" class="btn btn-primary">Annuler</a>

            </form>
        </div>
    </div>
</body>

</html>