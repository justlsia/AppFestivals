<?php

session_start();


require '../includes/config.php';
require '../includes/functions.php';

// Vérification si l'utilisateur est connecté
if (empty($_SESSION['user'])) {
    die("❌ Erreur : Aucun utilisateur connecté.");
}

$username = $_SESSION['user']['username'];
//var_dump($username); // Vérifie la valeur du username

$user = getUserByUsername($username);
//var_dump($user); // Vérifie ce que retourne la fonction

if (!$user) {
    die("❌ Erreur : Utilisateur non trouvé en base.");
}

if (!empty($user['id'])) {
    $user_id = $user['id'];
} else {
    die("❌ Erreur : Impossible de récupérer l'ID utilisateur.");
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Récupérer les paramètres de la requête
    $name = $_POST['name'];
    $location = $_POST['location'];
    $date = $_POST['date'];
    $description = $_POST['description'];
    $official_website = $_POST['official_website'];
    $image = $_POST['image'];   

    try {


        // Vérifier si le festival existe déja 
        if (checkFestivalExist($name, $location, $date) > 0) {
            $_SESSION['error'] = "Ce festival existe déjà ! ⚠️";
            header("Location: ../pages/manage.php");
            exit();
        }

        // Ajouter un festival et récupérer son ID
        $festival_id = addFestival($name, $location, $date, $description, $image, $official_website);

        if ($festival_id) {
            // Vérifier le niveau de participation de l'utilisateur
            $totalPoints = getParticipationUserById($user_id);

            if ($totalPoints < 5) {
                // Ajouter un point
                $sucess = addParticipationLevel($user_id, $festival_id); 
                if ($success) {
                    echo "✅ Participation ajoutée avec succès !";
                } else {
                    echo "❌ Erreur lors de l'ajout de la participation.";
                }
            }

            $_SESSION['success'] = "Festival ajouté avec succès ! ✅";
            header("Location: ../pages/manage.php");
            exit();
        } else {
            $_SESSION['error'] = "Erreur lors de l'ajout du festival.";
            header("Location: ../pages/manage.php");
            exit();
        }


    } catch (PDOException $e) {
        error_log("Erreur dans le processus d'ajout : " . $e->getMessage());
        $_SESSION['error'] = "❌ Une erreur est survenue.";
        header("Location: ../pages/manage.php");
    }

}


?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Festival</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

</head>

<body>
    <div class="container">
        <div class="card-container">
            <h2 class="mt-5">Ajouter un Festival</h2>
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Nom</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Lieu</label>
                    <input type="text" name="location" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Date</label>
                    <input type="date" name="date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">URL de l'image :</label>
                    <input type="text" name="image" class="form-control"><br>
                </div>
                <div class="mb-3">
                    <label class="form-label">URL du site officiel :</label>
                    <input type="text" name="official_website" class="form-control" required><br>
                </div>

                <!-- Valider l'ajout du festival -->
                <button type="submit" class="btn btn-success">Ajouter</button>

                <a href="../pages/manage.php" class="btn btn-primary">Annuler</a>

            </form>
        </div>
    </div>
</body>

</html>