<?php
session_start();
require '../includes/config.php';

// Si l'id du festival est bien renseignée, le supprimer
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM festivals WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: ../pages/manage.php");
exit();
?>
