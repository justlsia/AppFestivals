<?php
// Rediriger : page d'acceuil (liste des festivals)
header("Location: pages/festivals.php");
exit();
// Serveur de logs GlitchTip
Sentry\init(['dsn' => 'http://ab62b5fb0837424aa4b3a9290c4daa6a@172.16.0.100:8000/1' ]);
throw new Exception("My first GlitchTip error : Lisa !");

?>
