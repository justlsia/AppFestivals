<?php
session_start();
require '../includes/config.php';
require '../includes/functions.php';
require '../includes/header.php';

// Vérifier si un ID (id d'un utilisateur) est passé en paramètre
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: festivals.php");
    exit();
}

$id = (int) $_GET['id']; // Sécurisation de l'ID

// Récupérer les informations de l'utilisateur 
$user = getUserProfile($id);


if (!$user) {
    echo "<p>Utilisateur non trouvé.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Titre de la page + nom de l'utilisateur recherché -->
    <title><?= htmlspecialchars($user['username']) ?></title>

    <!-- CSS -->
    <link rel="stylesheet" href="../css/style.css">

    <!-- BOOSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    
    <!-- Containeur : Fiche detail d'un utilisateur -->
    <div class="container mt-5">
        <div class="card mx-auto" style="width: 24rem;">

            <img src="<?= htmlspecialchars($user['profile_picture']) ?>" class="card-img-top" alt="<?= htmlspecialchars($user['username']) ?>">
            
            <div class="card-body">
                <h5 class="card-title"><strong>Nom d'utilisateur : </strong><?= htmlspecialchars($user['username']) ?></h5>
                <hr>
                <p class="card-text"><strong>Nom/prénom : </strong><?= htmlspecialchars($user['name']) . " " . htmlspecialchars($user['firstname'])  ?></p>
                <p class="card-text"><strong>Email : </strong><?=htmlspecialchars($user['email']) ?></p>
                <p class="card-text"><strong>Age : </strong><?= htmlspecialchars($user['age']) ?> ans</p>
  
                <p class="card-text"><strong>Niveau de participation : 
                    <div class="rating ms-2">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <span class="star <?= ($i <= $user['participation_level']) ? 'filled' : ''; ?>">★</span>
                        <?php endfor; ?>
                    </div>
                </p>
                
                <p class="card-text"><strong>Compte administrateur : </strong>
                    <?php if (isset($user['administrateur']) && $user['administrateur'] === 1) {
                                echo htmlspecialchars('✅');
                            } else {
                                echo htmlspecialchars('❌');
                            } 
                    ?>
                </p>
            </div>

            <!-- Retour à l'acceuil (liste des festivals) -->
            <div class="card-body text-center">
                <a href="administration.php" class="btn btn-primary">Retour</a>
                
                <!-- Mettre à jour l'utilisateur (sauf pour les connexion avec Google )-->
                <?php if($user['google_id'] == Null) { ?>
                    <a href="../actions/edit_user.php?id=<?= $user['id'] ?>" class="btn btn-warning">Modifier</a>
                <?php } ?>

            </div>

        </div>
    </div>

</body>

</html>