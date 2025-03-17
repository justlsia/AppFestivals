<?php
session_start();
require '../includes/config.php';
require '../includes/header.php';

/*
// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}
*/

// Nombre de festivals par page
$festivalsParPage = 20;

// Récupérer le numéro de la page actuelle (par défaut : 1)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

// Calcul de l'offset (à partir de quel festival commencer)
$offset = ($page - 1) * $festivalsParPage;

// Récupération des festivals avec LIMIT et OFFSET
$stmt = $pdo->prepare("SELECT * FROM festivals ORDER BY name, date  LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $festivalsParPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$festivals = $stmt->fetchAll();

// Récupérer le nombre total de festivals
$totalFestivals = $pdo->query("SELECT COUNT(*) FROM festivals")->fetchColumn();
$totalPages = ceil($totalFestivals / $festivalsParPage);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Festivals</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

</head>

<body>
    <div class="container mt-5">
        <div class="card-container">
            <h2>Liste des Festivals</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Lieu</th>
                        <th>Date</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($festivals as $festival): ?>
                    <tr onclick="window.location='detail.php?id=<?= $festival['id'] ?>';" style="cursor: pointer;">
                        <td><?= htmlspecialchars($festival['name']) ?></td>
                        <td><?= htmlspecialchars($festival['location']) ?></td>
                        <td><?= htmlspecialchars($festival['date']) ?></td>
                        <td><?= htmlspecialchars($festival['description']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <nav>
                <ul class="pagination justify-content-center">
                    <!-- Flèche précédente -->
                    <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Précédent">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>

                    <!-- Numéros des pages -->
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                    <?php endfor; ?>

                    <!-- Flèche suivante -->
                    <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Suivant">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</body>

</html>


<?php
require '../includes/footer.php';
?>