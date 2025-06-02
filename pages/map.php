<?php

require_once "../includes/config.php";
require_once "../includes/functions.php";

// Connexion à la base
global $pdo;

// Récupération des festivals et de leurs positions
$festivals = [];
try {
    $stmt = $pdo->query("SELECT name, location, latlong FROM festivals WHERE latlong IS NOT NULL");
    $festivals = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Erreur récupération festivals : " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Carte des festivals</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS  -->
    <link rel="stylesheet" href="../css/style.css">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

</head>
<body>
    <h2 style="text-align:center;">Carte interactive des festivals</h2>
    <div id="map"></div>

    <script>
        // Initialisation de la carte centrée sur la France
        const map = L.map('map').setView([46.603354, 1.888334], 6);

        // Ajout du fond de carte OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Liste des festivals récupérés 
        const festivals = <?= json_encode($festivals); ?>;

        festivals.forEach(festival => {
            if (festival.latlong) {
                const [lat, lng] = festival.latlong.split(',').map(Number);

                L.marker([lat, lng])
                    .addTo(map)
                    .bindPopup(`<strong>${festival.name}</strong><br>${festival.location}`);
            }
        });
    </script>
</body>
</html>
