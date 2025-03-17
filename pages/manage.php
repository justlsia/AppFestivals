<?php
session_start();
require '../includes/config.php';

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Nombre de festivals par page
$festivalsParPage = 15;

// Récupérer le numéro de la page actuelle (par défaut : 1)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

// Calcul de l'offset pour récupérer les festivals à partir d'une certaine ligne
$offset = ($page - 1) * $festivalsParPage;

// Récupération des festivals avec LIMIT et OFFSET
$stmt = $pdo->prepare("SELECT * FROM festivals order by name, date LIMIT :limit OFFSET :offset");
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
    <title>Gérer les Festivals</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container">
        <div class="card-container">
            <h2 class="mt-5">Gérer les Festivals</h2>
            <a href="festivals.php" class="btn btn-primary mb-3">Retour</a>
            <a href="../actions/add_festival.php" class="btn btn-success mb-3">Ajouter un Festival</a>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Lieu</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($festivals as $festival): ?>
                    <tr>
                        <td><?= htmlspecialchars($festival['name']) ?></td>
                        <td><?= htmlspecialchars($festival['location']) ?></td>
                        <td><?= htmlspecialchars(date("d/m/Y", strtotime($festival['date']))) ?></td>
                        <td>
                            <a href="../actions/edit_festival.php?id=<?= $festival['id'] ?>"
                                class="btn btn-warning">Modifier</a>
                            <a href="../actions/delete_festival.php?id=<?= $festival['id'] ?>" class="btn btn-danger"
                                onclick="return confirm('Supprimer ce festival ?')">Supprimer</a>
                        </td>
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