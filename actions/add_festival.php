<?php
session_start();
require '../includes/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $name = $_POST['name'];
    $location = $_POST['location'];
    $date = $_POST['date'];
    $description = $_POST['description'];
    $image = $_POST['image'];
    $official_website = $_POST['official_website'];

    // Vérifier si le festival existe déja (nom/lieu/date)
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM festivals WHERE name = ? AND location = ? AND date = ?");
    $stmt->execute([$name,$location,$date]);
    $festivalExists = $stmt->fetchColumn();

    // Si le festival existe déja 
    if ($festivalExists > 0) {
        $_SESSION['error'] = "Ce festival existe déjà ! ⚠️";
    } else {
        // Ajouter le festival
        $stmt = $pdo->prepare("INSERT INTO festivals (name, location, date, description, image, official_website) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $location, $date, $description, $image, $official_website]);
        $_SESSION['success'] = "Festival ajouté avec succès ! ✅";
        echo "Festival ajouté avec succès !";
        header("Location: ../pages/manage.php");
    exit();
    }

}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ajouter un Festival</title>

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

            <h2 class="mt-5">Ajouter un Festival</h2>


            <!-- Messages d'erreur ou de succès A RETIRER ICI --> 
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>



            <!-- Formulaire : Ajouter d'un nouveau festival -->
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
                    <textarea name="description" class="form-control"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">URL de l'image :</label>
                    <input type="text" name="image" class="form-control"><br>
                </div>

                <div class="mb-3">
                    <label class="form-label">URL du site officiel du festival :</label>
                    <input type="text" name="official_website" class="form-control" required>
                </div>

                <!-- Valider l'ajout -->
                <button type="submit" class="btn btn-success">Ajouter</button>

                <a href="../pages/manage.php" class="btn btn-primary">Annuler</a>

            </form>
        </div>
    </div>
</body>

</html>