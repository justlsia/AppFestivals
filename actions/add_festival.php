<?php

session_start();

require '../includes/config.php';
require '../includes/functions.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Récupérer les paramètres de la requête
    $name = $_POST['name'];
    $location = $_POST['location'];
    $date = $_POST['date'];
    $description = $_POST['description'];
    $official_website = $_POST['official_website'];
    $image = $_POST['image'];

    try {

        // Ajouter un festival
        if (addFestival($name, $location, $date, $description, $image, $official_website)) {
            $_SESSION['success'] = "Festival ajouté avec succès ! ✅";
            header("Location: ../pages/manage.php");
        } 

    } catch (PDOException $e) {

        echo "Erreur lors de l'ajout du festival : " . $e->getMessage();
    }

}


// TEST

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