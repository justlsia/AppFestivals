<?php
session_start();
$error = isset($_SESSION['error']) ? $_SESSION['error'] : "";
$success = isset($_SESSION['success']) ? $_SESSION['success'] : "";
unset($_SESSION['error'], $_SESSION['success']);
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

    <!-- Titre de la page -->
    <title>Gérer les Festivals</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../css/style.css?v=<?= time(); ?>">

    <!-- BOOSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body>

    <!-- Containeur : Gérer les festivals -->
    <div class="container">
        <div class="card-container">

            <?php if (isset($success)) echo "<p class='text-danger'>$success</p>";?>
            
            <h2 class="mt-5">🎛 Gestion des Festivals : Ajout, Modification et Suppression</h2>
            <p>Bienvenue sur la page de gestion des festivals, un espace réservé aux utilisateurs connectés qui
                souhaitent participer activement à l’évolution du site. En tant que membre de la communauté, vous avez
                la possibilité de contribuer directement en ajoutant de nouveaux festivals, en mettant à jour des
                informations existantes ou en supprimant des événements obsolètes.</p>

            <!-- Popup d'informations de gestion des festivals -->
            <!-- Bouton pour ouvrir la popup -->
            <button class="btn btn-warning mb-3" onclick="openPopup()">Informations !</button>

            <!-- Popup personnalisée -->
            <div id="customModal" class="custom-modal" style="display: none;">
                <div class="custom-modal-content">

                <h5 class="modal-title">🛠️ Administration des Festivals : Ajout, Modification et Suppression</h5>
                    <hr>
                    <div class="modal-body">
                        <h4>✍ Ajouter un Festival</h4>
                        <p>Vous avez connaissance d’un festival qui ne figure pas encore sur le site ? Vous pouvez
                            l’ajouter facilement en renseignant les informations essentielles :</p>
                        <ul>
                            <li>✅ Nom du festival</li>
                            <li>✅ Lieu</li>
                            <li>✅ Date</li>
                            <li>✅ Description</li>
                            <li>✅ Lien vers le site officiel (si disponible)</li>
                            <li>✅ Image ou affiche du festival (optionnel, mais recommandé)</li>
                        </ul>
                        <p>Merci de vérifier l’exactitude des informations avant de soumettre un nouvel événement.</p>

                        <h4>✏ Modifier un Festival</h4>
                        <p>Les festivals évoluent, et leurs informations peuvent changer. Si vous constatez une erreur
                            ou une mise à jour nécessaire, vous pouvez modifier une fiche existante pour qu’elle reste
                            fiable et à jour.</p>

                        <h4>🗑 Supprimer un Festival</h4>
                        <p>Certains festivals peuvent être annulés ou ne plus exister. Dans ce cas, il est possible de
                            supprimer une fiche afin de ne pas afficher des événements obsolètes.</p>

                        <h4>🤝 Un Site Basé sur la Confiance et la Collaboration</h4>
                        <p>En tant que contributeur, vous jouez un rôle clé dans la qualité et la fiabilité du site.</p>

                        <hr>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" onclick="closePopup()">Fermer</button>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Ouverture/fermeture de la popup -->
            <script>
            /*
             * Ouvrir une popup
             */
            function openPopup() {
                let modal = document.getElementById("customModal");
                if (modal) {
                    modal.style.display = "flex";
                }
            }
            /*
             * Fermer une popup
             */
            function closePopup() {
                let modal = document.getElementById("customModal");
                if (modal) {
                    modal.style.display = "none";
                }
            }
            </script>

            <!-- Ajouter un festival -->
            <a href="../actions/add_festival.php" class="btn btn-success mb-3">Ajouter un Festival</a>

            <!-- Table des festivals -->
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
                    <!-- Boucler sur les festivals -->
                    <?php foreach ($festivals as $festival): ?>
                    <tr>
                        <td><?= htmlspecialchars($festival['name']) ?></td>
                        <td><?= htmlspecialchars($festival['location']) ?></td>
                        <td><?= htmlspecialchars(date("d/m/Y", strtotime($festival['date']))) ?></td>
                        <td>
                            <a href="../actions/edit_festival.php?id=<?= $festival['id'] ?>" class="btn btn-warning">Modifier</a>
                            <a href="../actions/delete_festival.php?id=<?= $festival['id'] ?>" class="btn btn-danger" onclick="return confirm('Supprimer ce festival ?')">Supprimer</a>
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

            <!-- Retour à l'acceuil (liste des festivals) -->
            <a href="festivals.php" class="btn btn-primary mb-3">Retour</a>

        </div>
    </div>


</body>

</html>