<?php

session_start();

$error = isset($_SESSION['error']) ? $_SESSION['error'] : "";
$success = isset($_SESSION['success']) ? $_SESSION['success'] : "";
unset($_SESSION['error'], $_SESSION['success']);

require '../includes/config.php';
require '../includes/header.php';
require '../includes/functions.php';

// Nombre d'users par page
$usersParPage = 5;

// Récupérer le numéro de la page actuelle (par défaut : 1)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

// Calcul de l'offset pour récupérer les utilisateurs à partir d'une certaine ligne
$offset = ($page - 1) * $usersParPage;

// Récupérer les utilisateurs dans un tableau
$users = getAllUsers($usersParPage, $offset);

// Récupérer le nombre total d'users
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalPages = ceil($totalUsers / $usersParPage);

// Formater la date de dernière participation
//$dateTimeLastParticipation = $users['participation_date'];
//$lastParticipation = $dateTime->format('Y-m-d');

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Titre de la page -->
    <title>À Propos</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../css/style.css">
    
    <!-- BOOSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</head>

<body>

    <!-- Container principale -->
    <div class="container mt-5">
        <div class="card-container">

            <?php if (isset($success) || isset($error)) echo "<p class='text-danger'>" . ($success ?? $error) . "</p>"; ?>

            <!-- Titre du containeur principale -->
            <h2 class="mt-5">Administration de App Festival</h2>

            <h4>Lorem ipsum dolor sit amet consectetur adipisicing elit. Facilis, earum! Sed beatae labore adipisci culpa corporis dolore, laboriosam nobis dolor eveniet! Numquam non nemo esse adipisci eos ea explicabo sunt!</h4>
            <p class="text-start">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Eius placeat reprehenderit aliquid ratione sapiente officia vitae debitis, maxime totam tenetur itaque aliquam eveniet porro accusamus vel aut. Dignissimos, unde voluptatum.
            </p>

            <!-- Table des utilisateurs -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nom d'utilisateur</th>
                        <th>Niveau de participation</th>
                        <th>Dernière participation</th>
                        <th>Compte administrateur</th>
                        <th>Gérer</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Boucler sur les festivals -->
                    <?php foreach ($users as $user): ?>
                    <tr onclick="window.location='detailUser.php?id=<?= $user['id'] ?>';" style="cursor: pointer;">
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <!-- Niveau de participation -->
                        <td>
                            <div class="rating ms-2">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="star <?= ($i <= $user['participation_level']) ? 'filled' : ''; ?>">★</span>
                                <?php endfor; ?>
                            </div>
                        </td>
                         <!-- Date de la dernière participation -->           
                        <td>
                            <?php if ($user['participation_date']) {
                                // Créer un objet DateTime à partir de 'participation_date' et formater la date
                                $dateLastParticipation = new DateTime($user['participation_date']);
                                echo htmlspecialchars($dateLastParticipation->format('j, n, Y'));
                            } else {
                                echo '0';
                            }
                            ?>
                        </td>
                        <!-- Compte administrateur ou non -->
                        <td>
                            <?= htmlspecialchars($user['administrateur']) ?>
                        </td>
                        <td>
                            <a title="Supprimer l'utilisateur" href="../actions/delete_user.php?id=<?= $user['id'] ?>" class="btn btn-danger" onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</a>
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


            <!-- Retour à la page d'acceuil (Liste des festivals) -->
            <a href="festivals.php" class="btn btn-primary">Retour</a>
        </div>
    </div>

</body>

</html>