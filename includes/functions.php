<?php
require 'config.php'; // Connexion à la base de données

/**
 * Récupère un festival par son ID
 */
function getFestivalById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM festivals WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

/**
 * Met à jour un festival
 */
function updateFestival($id, $name, $location, $date, $description, $image) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE festivals SET name = ?, location = ?, date = ?, description = ?, image = ? WHERE id = ?");
    return $stmt->execute([$name, $location, $date, $description, $image, $id]);
}
?>
