<?php

session_start();
require '../includes/functions.php';

// Vérifier que l'id est présent dans l'URL
if (!isset($_GET['id'])) {
    header('Location: manage.php');
    exit();
}

$id = $_GET['id'];
$festival = getFestivalById($id);

// Si le festival n'existe pas, redirection
if (!$festival) {
    header('Location: manage.php');
    exit();
}

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($id)) {
    // Récupération des paramètres de la requête
    $name = $_POST['name'];
    $location = $_POST['location'];
    $date = $_POST['date'];
    $description = $_POST['description'];
    $image = $_POST['image'];
    $official_website = $_POST['official_website'];

    // Mise à jour du festival
    if (updateFestival($id, $name, $location, $date, $description, $image)) {
        echo "Modification du festival avec succès.";
        $_SESSION['success'] = "Modification du festival avec succès.";
        Sentry\captureMessage("✅ Edit festival. Date/Time : " . date("F j, Y, g:i a") . " - username : " . $username . " - name festival : " . $name ); // Log    
        header("Location: ../pages/manage.php");
        exit();
    } else {
        $error = "Erreur lors de la mise à jour du festival.";
        Sentry\captureMessage("❌ Error edit festival. Date/Time : " . date("F j, Y, g:i a") . " - username : " . $username . " - name festival : " . $name ); // Log    
    }
}
    
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Modifier un Festival</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../css/style.css">

    <!-- BOOSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body>

    <div class="container">
        <div class="card-container">

            <h2 class="mt-5">Modifier le Festival</h2>

            <!-- Vérifier si le festival est bien chargé -->
            <?php if (isset($error)) : ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <!-- Formulaire de mise à jour d'un festival -->
            <form method="post">

                <div class="mb-3">
                    <label class="form-label">Nom</label>
                    <input type="text" name="name" class="form-control"
                        value="<?= htmlspecialchars($festival['name']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Lieu</label>
                    <input type="text" name="location" class="form-control"
                        value="<?= htmlspecialchars($festival['location']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Date</label>
                    <input type="date" name="date" class="form-control"
                        value="<?= htmlspecialchars($festival['date']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description"
                        class="form-control"><?= htmlspecialchars($festival['description']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">URL de l'image</label>
                    <input type="text" name="image" class="form-control"
                        value="<?= htmlspecialchars($festival['image']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Lien vers le site officiel du festival</label>
                    <input type="text" name="image" class="form-control"
                        value="<?= htmlspecialchars($festival['official_website']) ?>" required>
                </div>

                <!-- Mettre à jour le festival -->
                <button type="submit" class="btn btn-warning">Modifier</button>

                <a href="../pages/manage.php" class="btn btn-primary">Annuler</a>

            </form>
        </div>
    </div>
</body>

</html>