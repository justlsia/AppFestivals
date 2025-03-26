<?php
session_start();

require '../includes/config.php';
require '../includes/functions.php';


if (isset($_GET['id'])) { 

    // Récupérer l'id de l'utilisateur à supprimer
    $id = $_GET['id'];
    if (deleteUser($id)) {
        $_SESSION['success'] = "Suppression de l'utilisateur avec succès. ✅";
    } else {
        $_SESSION['error'] = "Erreur lors de la suppression de l'utilisateur. ❌";
    }
}


header("Location: ../pages/administration.php");
exit();
?>
