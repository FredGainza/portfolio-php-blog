<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'vendor/PHPMailer-master/src/Exception.php';
require 'vendor/PHPMailer-master/src/PHPMailer.php';
require 'vendor/PHPMailer-master/src/SMTP.php';

$mdpTemp = $_SESSION['mdp_temp'];

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
    $mail->Password = 'xxxxxxxxxx';                           // SMTP password
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
    $body = file_get_contents('../mail/reset_mdp.html');
    $body = str_replace('$mdpTemp', $mdpTemp, $body);

    $body = preg_replace('/\\\\/', '', $body);

    $mail->MsgHTML($body); 

    $mail->AltBody = 'Sujet : Changement de mdp par l\'utilisateur. ' . $firstname . ' ' . $lastname . ' (email : ' . $email . ')';

    $mail->send();
    $_SESSION['success'] = "Un nouveau mot de passe vient de vous être envoyé par mail";
} catch (Exception $e) {
    $_SESSION['errors'] = "Le message n'a pas pu être envoyé. Mailer Error: {$mail->ErrorInfo}";
}
header('Location: ../index.php');
exit;
