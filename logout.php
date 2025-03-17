<?php
session_start();
$_SESSION = []; // Vide toutes les variables de session
session_destroy();
setcookie(session_name(), '', time() - 3600, '/'); // Supprime le cookie de session
header("Location: ../pages/festivals.php");
exit();
?>
