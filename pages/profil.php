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
$stmt = $pdo->prepare("SELECT username, name, firstname, age, email, profile_picture, participation_level FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Traitement du formulaire de mise à jour
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_profile"])) {

    // Récupérer les paramètres du formulaire
    $username = trim($_POST["username"]);
    $name = trim($_POST["name"]);
    $firstname = trim($_POST["firstname"]);
    $age = trim($_POST["age"]);
    $email = trim($_POST["email"]);
    $profile_picture = $user['profile_picture']; // Conserver l'ancienne photo si non modifiée
    $participation_level = $user['participation_level'];

    if (!empty($_FILES['profile_picture']['name'])) {
        $target_dir = "../uploads/";
    
        // Vérifier le type de fichier
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        $file_ext = strtolower(pathinfo($_FILES["profile_picture"]["name"], PATHINFO_EXTENSION));
    
        if (!in_array($file_ext, $allowed_types)) {
            $_SESSION['error'] = "Format d'image non autorisé. Formats acceptés : jpg, jpeg, png, gif.";
            header("Location: ../pages/profil.php");
            exit();
        }
    
        // Vérifier la taille du fichier (max 2MB)
        if ($_FILES["profile_picture"]["size"] > 2 * 1024 * 1024) {
            $_SESSION['error'] = "Fichier trop volumineux (max 2MB).";
            header("Location: ../pages/profil.php");
            exit();
        }
    
        // Générer un nom unique pour éviter les conflits
        $new_filename = "profile_" . $user_id . "_" . time() . "." . $file_ext;
        $target_file = $target_dir . $new_filename;
    
        // Supprimer l'ancienne image si elle existe et n'est pas l'image par défaut
        if ($user['profile_picture'] && $user['profile_picture'] !== "../uploads/default_avatar.svg") {
            unlink($user['profile_picture']);
        }
    
        // Déplacer l'image vers le dossier de destination
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            $profile_picture = $target_file;
        } else {
            $_SESSION['error'] = "Erreur lors de l'upload de l'image.";
            header("Location: ../pages/profil.php");
            exit();
        }
    }

    if (updateUserProfile($user_id, $username, $name, $firstname, $age, $email, $profile_picture)) {
        $_SESSION['success'] = "Profil mis à jour avec succès.";
    } else {
        $_SESSION['error'] = "Erreur lors de la mise à jour du profil.";
    }

    // Mise à jour des informations dans la BDD
    //$stmt = $pdo->prepare("UPDATE users SET username = ?, name = ?, firstname = ?, age = ?, email = ?, profile_picture = ? WHERE id = ?");
    //$stmt->execute([$username, $name, $firstname, $age, $email, $profile_picture, $user_id]);

    // Recharge les nouvelles données
    //$stmt = $pdo->prepare("SELECT username, name, firstname, age, email, profile_picture, participation_level FROM users WHERE id = ?");
    //$stmt->execute([$user_id]);
    //$user = $stmt->fetch(PDO::FETCH_ASSOC);

    //$_SESSION['success'] = "Profil mis à jour avec succès.";
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

        <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>

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
                        <span class="star <?= ($i <= $user['participation_level']) ? 'filled' : ''; ?>">★</span>
                        <?php endfor; ?>
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
                        <label>Photo de profil :</label><br>
                        <?php if ($user['profile_picture']): ?>
                        <img src="<?= htmlspecialchars($user['profile_picture']) ?>" alt="Photo de profil" width="100"
                            class="mb-2"><br>
                        <?php else: ?>
                        <img src="../uploads/default_avatar.svg" alt="Avatar par défaut" width="100" class="mb-2"><br>
                        <?php endif; ?>
                        <input type="file" name="profile_picture" class="form-control">
                    </div>


                    <button type="submit" name="update_profile" class="btn btn-success">Enregistrer</button>
                </form>
                <a href="" class="btn btn-primary">Annuler</a>
            </div>
        </div>
    </div>

    <script src="../js/script.js"></script>
</body>

</html>