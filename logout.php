<?php
session_start();
// Vider toutes les variables de session
$_SESSION = []; 
session_destroy();
// Supprimer le cookie de session
setcookie(session_name(), '', time() - 3600, '/'); 
// Rediriger sur la page d'acceuil (liste des festivals)
header("Location: index.php");
exit();
?>
