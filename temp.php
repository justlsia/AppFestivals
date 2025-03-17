<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_email'])) {
    echo "Aucun utilisateur connecté.";
    exit;
}

$email = $_SESSION['user_email'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h2>Bienvenue sur votre profil</h2>
        <p><strong>Email :</strong> <?php echo htmlspecialchars($email); ?></p>
        <a href="festivals.php" class="btn btn-primary">Aller aux festivals</a>
        <a href="logout.php" class="btn btn-danger">Déconnexion</a>
    </div>
</body>
</html>
