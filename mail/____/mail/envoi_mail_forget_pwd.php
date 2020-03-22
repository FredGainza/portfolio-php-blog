<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'vendor/PHPMailer-master/src/Exception.php';
require 'vendor/PHPMailer-master/src/PHPMailer.php';
require 'vendor/PHPMailer-master/src/SMTP.php';


// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->CharSet = "utf-8";
    $mail->SMTPDebug = SMTP::DEBUG_OFF;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'mail.fgainza.fr';                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username = 'contact@fgainza.fr';
    $mail->Password = 'xxxx';                           // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    $mail->Port       = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('techno-blog@fgainza.fr', 'Techno-Blog');
    $mail->addAddress($_SESSION['email_forget_pwd']);
    $mail->addBCC('techno-blog@fgainza.fr');

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Mot de passe oublié';
    $mail->Body    = 'Bonjour, <br>
                Vous avez demandé un nouveau mot de passe.
                N\'oubliez pas de le changer lors de votre 1ere connexion.
                Rerouvez ici les informations relatives à votre compte.
                
                Voici vos informations :<br><br>
                    Mot de passe temporaire: ' . $_SESSION['mdp_temp'] . ' <br><br>
                    Merci et à très bientôt !!<br>
                    <a href="https://techno-blog.fgainza.fr">TECHNO-BLOG</a>';

    $mail->AltBody = 'Sujet : Changement de mdp par l\'utilisateur. ' . $firstname . ' ' . $lastname . ' (email : ' . $email . ')';

    $mail->send();
    $_SESSION['success'] = "Un nouveau mot de passe vient de vous être envoyé par mail";
} catch (Exception $e) {
    $_SESSION['errors'] = "Le message n'a pas pu être envoyé. Mailer Error: {$mail->ErrorInfo}";
}
header('Location: ../index.php');
exit;
