<?php
session_start();
require '../includes/config.php';

// Vérifier si un ID (id d'un festival) est passé en paramètre
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: festivals.php");
    exit();
}

$id = (int) $_GET['id']; // Sécurisation de l'ID

// Récupérer les informations du festival
$stmt = $pdo->prepare("SELECT * FROM festivals WHERE id = :id");
$stmt->execute(['id' => $id]);
$festival = $stmt->fetch();

if (!$festival) {
    echo "<p>Festival non trouvé.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Titre de la page + nom du festival recherché -->
    <title><?= htmlspecialchars($festival['name']) ?></title>

    <!-- CSS -->
    <link rel="stylesheet" href="../css/style.css">

    <!-- BOOSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    
    <!-- Containeur : Fiche detail d'un festival -->
    <div class="container mt-5">
        <div class="card mx-auto" style="width: 24rem;">

            <img src="<?= htmlspecialchars($festival['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($festival['name']) ?>">
            
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($festival['name']) ?></h5>
                <p class="card-text"><?= htmlspecialchars($festival['description']) ?></p>
            </div>

            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Lieu :</strong> <?= htmlspecialchars($festival['location']) ?></li>
                <li class="list-group-item"><strong>Date :</strong> <?= htmlspecialchars($festival['date']) ?></li>
            </ul>

            <div class="card-body text-center">
                <a href="https://maps.google.com/?q=<?= urlencode($festival['location']) ?>" class="card-link" target="_blank">Accéder au site officiel</a>
            </div>

            <!-- Retour à l'acceuil (liste des festivals) -->
            <div class="card-body text-center">
                <a href="festivals.php" class="btn btn-primary">Retour</a>
            </div>

        </div>
    </div>

</body>

</html>