<?php

session_start();
require_once "../includes/config.php";
require '../includes/header.php';
require_once "../includes/functions.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: advencedSearch.php");
    exit();
}

$user_id = $_SESSION['user']['id'];

// Récupérer les informations de l'utilisateur
$user = getUserProfile($user_id);


// Traitement du formulaire de recherche
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search_festival"])) {

    // Récupérer les paramètres du formulaire
    $name = trim($_POST["name"]);
    $date = trim($_POST["date"]);
    $location = trim($_POST["location"]);
    $note = trim($_POST["note"]);

    $results = advencedSearchFestival($name, $date, $location, $note);

    header("Location: ../pages/advencedSearch.php");
    exit();
}


?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche avancée</title>
    <!-- CSS -->
    <link rel="stylesheet" href="../css/style.css?v=<?= time(); ?>">

    <!-- BOOSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    function toggleEdit() {
        document.getElementById("profileInfo").style.display = "none";
        document.getElementById("editProfileForm").style.display = "block";
    }
    </script>
</head>

<body>
    <div class="container mt-5">

        <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

        <div class="card p-3">
            <h2>Recherche avancée d'un festival</h2>
            <hr>

            <!-- Formulaire de recherche -->
            <div id="searchFestivalForm">
                <form action="advencedSearch.php" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label>Nom :</label>
                        <input type="text" name="name" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Date :</label>
                        <input type="date" name="date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Lieu :</label>
                        <input type="text" name="location" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Note :</label>
                        <input type="number" name="note" class="form-control" max="5">
                    </div>
                    
                    <button type="submit" name="search_festival" class="btn btn-success">Rechercher</button>
                    <a href="advencedSearch.php" class="btn btn-secondary ms-2">Réinitialiser</a>
                    
                </form>

                <?php if (!empty($results)): ?>
                <hr>
                <h3 class="mt-4">🔍 Résultats de la recherche :</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Date</th>
                            <th>Lieu</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($results as $festival): ?>
                        <tr>
                            <td><?= htmlspecialchars($festival['name']) ?></td>
                            <td><?= htmlspecialchars($festival['date']) ?></td>
                            <td><?= htmlspecialchars($festival['location']) ?></td>
                            <td><?= htmlspecialchars($festival['note']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
                    <p class="mt-3 text-danger">Aucun festival trouvé.</p>
                <?php endif; ?>

                <a href="" class="btn btn-primary">Annuler</a>
            </div>

        </div>
    </div>

</body>

</html>
