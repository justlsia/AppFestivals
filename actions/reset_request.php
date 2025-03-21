<?php

session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../includes/config.php';
require '../vendor/autoload.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    // Générer un token sécurisé
    $token = bin2hex(random_bytes(50)); 
    // Expiration dans 1 heure
    date_default_timezone_set('Europe/Paris');  
    //$expires = date("Y-m-d H:i:s", strtotime("+1 hour")); 
    $expires = date('Y-m-d H:i:s', time() + 3600);
    //$expires = '2025-03-19 15:45:15';
    echo $expires; // TEMP

    // Vérifier si l'email existe dans la base
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Supprimer les anciens tokens pour cet utilisateur
        $stmt = $pdo->prepare("DELETE FROM password_resets WHERE email = ?");
        $stmt->execute([$email]);

        // Insérer un nouveau token
        $stmt = $pdo->prepare("INSERT INTO password_resets (email, token, expires) VALUES (?, ?, ?)");
        $stmt->execute([$email, $token, $expires]);

        // Envoyer l'email avec le lien
        $reset_link = "http://172.16.201.254/AppSituationExam/situationUn/AppFestivals/pages/reset_password.php?token=$token";
        
        $mail = new PHPMailer(true);
        //mail($email, "Réinitialisation du mot de passe", "Cliquez ici pour réinitialiser votre mot de passe : $reset_link");

        //echo "Un email de réinitialisation a été envoyé.";

        try {
            // Configuration du serveur SMTP
            $mail->isSMTP();
            //$mail->Host = 'smtp.gmail.com'; // Serveur SMTP
            $mail->Host = '172.16.0.100'; // Serveur SMTP
            $mail->SMTPAuth = true;
            //$mail->Username = 'lisa.wrmr@gmail.com'; // Email expéditeur
            $mail->Username = 'fenelon-bts-sio@fenelon.com';
            //$mail->Password = 'cbwe leqb cyse tsef'; // Mot de passe d'application Gmail
            $mail->Password = 'Azertysio-01';
            //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->SMTPSecure = false;
            //$mail->Port = 587; // 465 : SSL, 587 : TLS
            $mail->Port = 1025; 

            // Paramètres de l'email
            $mail->setFrom('lisa.wrmr@gmail.com', 'AppFestivals'); // Adresse d'expédition
            $mail->addAddress($email); // Destinataire
            $mail->Subject = 'Réinitialisation du mot de passe';
            $mail->isHTML(true);
            $mail->Body = "
                <h3>Réinitialisation du mot de passe de votre compte App Festivals</h3>
                <p>Cliquez sur le lien ci-dessous pour réinitialiser votre mot de passe :</p>
                <a href='$reset_link'>$reset_link</a>
                <p>Ce lien expirera dans 15 minute.</p>
            ";

            // Envoyer l'email
            $mail->send();
            echo "Un email de réinitialisation a été envoyé.";

        } catch (Exception $e) {
            echo "Erreur lors de l'envoi de l'email : {$mail->ErrorInfo}";
        }


    } else {
        echo "Aucun compte trouvé avec cet email.";
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Mot de passe oublié</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../css/style.css">

    <!-- BOOSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

</head>

<body>

    <div class="container">
        <!-- Carte principale -->
        <div class="card-container">

            <h2 class="mt-5">Mot de passe oublié</h2>
            <p class="text-start">Un email de réinitialisation vous sera envoyé avec un lien pour réinitialiser votre mot de passe. Vérifiez vos spams si vous ne le voyez pas dans votre boîte de réception. Le lien sera valable pendant 15 minutes.</p>

            <!-- Formulaire : Reinitialiser son mot de passe -->
            <form method="post">

                <div class="mb-3">
                    <label class="form-label">Adresse email :</label>
                    <input type="email" name="email" class="form-control" required>
                </div>


                <!-- Valider la reinitialisation du mot de passe -->
                <button type="submit" class="btn btn-success">Reinitialiser mon mot de passe</button>

            </form>
            <a href="../pages/login.php" class="btn btn-primary">Annuler</a>
        </div>
    </div>
</body>

</html>