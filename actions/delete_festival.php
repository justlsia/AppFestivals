<?php
session_start();

require '../includes/config.php';
require '../includes/functions.php';


if (isset($_GET['id'])) { 

    // Récupérer l'id du festival à supprimer
    $id = $_GET['id'];
    if (deleteFestival($id)) {
        $_SESSION['success'] = "Suppression du festival avec succès. ✅";
    } else {
        $_SESSION['error'] = "Erreur lors de la suppression du festival. ❌";
    }
}


header("Location: ../pages/manage.php");
exit();
?>
