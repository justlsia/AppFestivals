<?php

session_start();

require_once "../includes/config.php";
require '../includes/header.php';
require_once "../includes/functions.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];

// Récupérer les informations de l'utilisateur
$user = getUserProfile($user_id);

// Récupérer les points de participations de l'utilisateur
$participation_level = getParticipationUserById($user_id);

// Traitement du formulaire de mise à jour
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_profile"])) {

    // Récupérer les paramètres du formulaire
    $username = trim($_POST["username"]);
    $name = trim($_POST["name"]);
    $firstname = trim($_POST["firstname"]);
    $age = trim($_POST["age"]);
    $email = trim($_POST["email"]);
    $profile_picture = $user['profile_picture']; 
    $administrateur = $user['administrateur'];
    //$participation_level = $user['participation_level'];


    if (updateUserProfile($user_id, $username, $name, $firstname, $age, $email, $profile_picture, $administrateur)) {
        //$_SESSION['success'] = "Profil mis à jour avec succès.";
        $_SESSION['popup_message'] = "Profil mis à jour avec succès.";
        $_SESSION['popup_status'] = true; 
        
    } else {
        //$_SESSION['error'] = "Erreur lors de la mise à jour du profil.";
        $_SESSION['popup_message'] = "Erreur lors de la mise à jour du profil.";
        $_SESSION['popup_status'] = false; 
    }

    header("Location: ../pages/profil.php");
    exit();
}


?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil</title>
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


        <!-- Popup d'informations -->
        <div id="popupContainer" class="popup" style="display: none;">
            <div class="popup-content">
                <p id="popupMessage"></p>
                <button id="closePopupBtn" onclick="closePop()" class="btn btn-primary">OK</button>
            </div>
        </div>

        <script src="../js/popup.js"></script>


        <?php if (isset($success) || isset($error)) echo "<p class='text-danger'>" . ($success ?? $error) . "</p>"; ?>

        <div class="card p-3">
            <h2>👤 Mon Profil</h2>
            <hr>
            <!-- Affichage des infos (lecture) -->
            <div id="profileInfo">
                <p><strong>Photo de profil :</strong><br>
                    <?php if ($user['profile_picture']): ?>
                    <img src="<?= $user['profile_picture'] ?>" alt="Photo de profil" width="100" class="mb-2">
                    <?php else: ?>
                    <span>Pas de photo</span>
                    <img src="../uploads/default_avatar.svg" alt="Photo de profil" width="100" class="mb-2">
                    <?php endif; ?>
                </p>
                <p><strong>Nom d'utilisateur :</strong> <?= htmlspecialchars($user['username']) ?></p>
                <p><strong>Nom :</strong> <?= htmlspecialchars($user['name']) ?></p>
                <p><strong>Prénom :</strong> <?= htmlspecialchars($user['firstname']) ?></p>
                <p><strong>Âge :</strong> <?= htmlspecialchars($user['age']) ?></p>
                <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
                <!-- TEMP -->
                <div class="d-flex align-items-center">
                    <strong>Niveau de participation :</strong>
                    <div class="rating ms-2">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span class="star <?= ($i <= $participation_level) ? 'filled' : ''; ?>">★</span>
                        <?php endfor; ?>
                    </div>
                    <!-- Bouton "?" pour afficher les infos -->
                    <button type="button" class="btn btn-secondary btn-sm ms-2" data-bs-toggle="modal"
                        data-bs-target="#infoModal">?</button>
                </div>

                <!-- Modal (POPUP) d'information -->
                <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="infoModalLabel">Niveau de Participation</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>⭐ Le niveau de participation évolue dynamiquement en fonction de votre engagement
                                    dans l'organisation et la gestion des festivals.<br></p>
                                <p>➜ Ajout d'un festival : Chaque fois que vous ajoutez un nouveau festival, votre
                                    niveau de participation est mis à jour pour refléter votre contribution.<br></p>
                                <p>➜ Modification d'un festival : Toute mise à jour d’un festival existant impacte
                                    également votre niveau de participation, en valorisant votre implication
                                    continue.<br></p>
                                <p>💡 Plus vous êtes actif, plus votre niveau de participation augmente ! Cela permet de
                                    mesurer votre engagement et votre investissement dans la gestion des événements. 🎉
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            </div>
                        </div>
                    </div>
                </div>


                <button class="btn btn-primary" onclick="toggleEdit()">Modifier</button>
            </div>

            <!-- Formulaire de modification caché par défaut -->
            <div id="editProfileForm" style="display: none;">
                <form action="profil.php" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label>Nom d'utilisateur:</label>
                        <input type="text" name="username" class="form-control"
                            value="<?= htmlspecialchars($user['username']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Nom :</label>
                        <input type="text" name="name" class="form-control"
                            value="<?= htmlspecialchars($user['name']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Prénom :</label>
                        <input type="text" name="firstname" class="form-control"
                            value="<?= htmlspecialchars($user['firstname']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Âge :</label>
                        <input type="number" name="age" class="form-control"
                            value="<?= htmlspecialchars($user['age']) ?>">
                    </div>
                    <div class="mb-3">
                        <label>Email :</label>
                        <input type="email" name="email" class="form-control"
                            value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>URL de votre photo de profil :</label>
                        <input type="text" name="profile_picture" class="form-control"
                            value="<?= htmlspecialchars($user['profile_picture']) ?>">
                    </div>


                    <button type="submit" name="update_profile" class="btn btn-success">Enregistrer</button>
                </form>
                <a href="" class="btn btn-primary">Annuler</a>
            </div>
        </div>
    </div>

    <script src="../js/script.js"></script>


    <?php
    // Vérifier si un message de popup est stocké dans la session
    if (isset($_SESSION['popup_message'])) {
        $message = json_encode($_SESSION['popup_message']);
        $status = $_SESSION['popup_status'] ? 'true' : 'false';

        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            showPopup($status, $message);
        });
        </script>";


        // Supprimer les variables de session pour éviter une réaffichage
        unset($_SESSION['popup_message']);
        unset($_SESSION['popup_status']);
    }
    ?>



</body>

</html>