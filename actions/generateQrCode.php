<?php

// Inclusion des dépendances
require_once dirname(__FILE__).'/vendor/autoload.php';
use OTPHP\TOTP;

/***********************
 * Génération d'un secret
***********************/
$otp = TOTP::create(
    $secret,            // secret utilisé (généré plus haut)
    30,                 // période de validité
    'sha256',           // Algorithme utilisé
    6                   // 6 digits
);
$otp->setLabel('AppFestivals'); // The label
$otp->setIssuer('Connexion AppFestivals');
$otp->setParameter('image', 'https://cdn.discordapp.com/attachments/1176135983595536464/1353722091173576736/IMG_1164.png?ex=67e2af71&is=67e15df1&hm=d4ee8354d365640d0a0874705771885e39a5970056a11e8455cd2c3657623882&'); // FreeOTP can display image

$otpOutput = "The current OTP is: {$otp->now()}\n";

/***********************
 * Affichage du temps pour information
 ***********************/
// Définition de la zone de temps
date_default_timezone_set('Europe/Paris');
$maintenant = time() ;

// Affichage de maintenant
$dateOutput = date('Y-m-d H:i:s',$maintenant);


/***********************
 * Génération du QrCode
 ***********************/
// Note: You must set label before generating the QR code
$grCodeUri = $otp->getQrCodeUri(
    'https://api.qrserver.com/v1/create-qr-code/?data=[DATA]&size=300x300&ecc=M',
    '[DATA]'
);
$qrCodeOutput = "<img src='{$grCodeUri}'>";



/***********************
 * Fonction de vérification du formulaire
 ***********************/

// Vérifie la valeur OTP
function checkOTP($otp_form): bool
{
    global $otp;

    return $otp->verify($otp_form);
}

$formOutput = '';

// Traitement du formulaire de login:
if (!empty($_POST['login']))
{
    if (checkOTP( $_POST['otp'] ) )
        $formOutput = "Login OK !";
    else
        $formOutput = "Echec login";
}
?>
