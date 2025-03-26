<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();  
}

//$user_admin = $_SESSION['user']['administrateur'];
$user_admin = isset($_SESSION['user']['administrateur']) ? $_SESSION['user']['administrateur'] : 0;

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Festival App</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <script defer src="../js/script.js"></script>
</head>

<body>

    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Festivals App</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="../pages/festivals.php">Accueil</a>
                    </li>
                    <!-- Afficher le menu "Gérer" uniquement si l'utilisateur est connecté -->
                    <?php if (isset($_SESSION['user'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../pages/manage.php">Gérer</a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../pages/about.php">A propos</a>
                    </li>

                    <!-- Afficher le lien "Mon profil" uniquement si l'utilisateur est connecté -->
                    <?php if (isset($_SESSION['user'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../pages/profil.php">Mon profil</a>
                    </li>
                    <?php endif; ?>

                    <!-- Afficher le lien "Administration" uniquement si l'utilisateur connecté est un administrateur -->
                    <?php if ($user_admin == 1): ?>

                    <li class="nav-item">
                        <a class="nav-link" href="../pages/administration.php">Administration</a>
                    </li>
                    <?php endif; ?>

                    <!-- Connexion / Déconnexion -->
                    <?php if (isset($_SESSION['user'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">Déconnexion</a>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../pages/login.php">Connexion</a>
                    </li>
                    <?php endif; ?>

                </ul>

                <form class="d-flex position-relative" role="search" action="../actions/search_festival.php"
                    method="GET">
                    <input class="form-control me-2" type="text" id="searchInput" name="query"
                        placeholder="Rechercher un festival..." aria-label="Search" autocomplete="off">
                    <div id="suggestions" class="list-group position-absolute w-100"></div>
                    <button class="btn btn-outline-success" type="submit">Rechercher</button>
                </form>


            </div>
        </div>
    </nav>


    <script>
    document.addEventListener("DOMContentLoaded", function() {
        if (document.getElementById("searchInput")) {
            initSearch(); // Appel de la fonction si l'input existe
        }
    });
    </script>

</body>