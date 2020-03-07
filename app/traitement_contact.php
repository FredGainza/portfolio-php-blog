<?php
session_start();
require 'bdd.php';
require '../toolbox.php';

require 'vendor/PHPMailer-master/src/Exception.php';
require 'vendor/PHPMailer-master/src/PHPMailer.php';
require 'vendor/PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

$_SESSION['valid'] = [];
$ok = false;


if (isset($_POST) && !empty($_POST)) {
    foreach ($_POST as $k => $v) {
        if (isset($v) && !empty($v)) {
            valid_donnees($k);
            $_SESSION['valid'][$k] = $v;
        } else {
            $_SESSION['errors'] = 'Un ou plusieurs champs ne sont pas correctement remplis';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
    }

    if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {

        $firstname = valid_donnees($_POST['firstname']);
        $lastname = valid_donnees($_POST['lastname']);
        $email = valid_donnees($_POST['email']);
        $statut = valid_donnees($_POST['statut']);
        $message = valid_donnees($_POST['message']);

        if ($statut == 'user' || $statut == 'admin') {

            $select = $dbh->prepare('SELECT * FROM users');
            $select->execute();

            $res = $select->fetchAll(PDO::FETCH_OBJ);

            foreach ($res as $val) {
                if ($val->email === $email) {
                    $email_base = $val->email;
                    $id_base = $val->id;
                }
            }

            if (isset($email_base) && !empty($email_base) && isset($id_base) && !empty($id_base)) {
                $insert = $dbh->prepare('INSERT INTO messages (user_id, firstname, lastname, email, statut, message) VALUES (:user_id, :firstname, :lastname, :email, :statut, :message)');
                $insert->bindValue(':user_id', $id_base, PDO::PARAM_INT);
                $insert->bindValue(':firstname', $firstname, PDO::PARAM_STR);
                $insert->bindValue(':lastname', $lastname, PDO::PARAM_STR);
                $insert->bindValue(':email', $email, PDO::PARAM_STR);
                $insert->bindValue(':statut', $statut, PDO::PARAM_STR);
                $insert->bindValue(':message', $message, PDO::PARAM_STR);

                $insert->execute();

                $ok = true;

                // $_SESSION['success'] = "Nous avons bien pris en compte votre demande " . $firstname . " " . $lastname . ".";
                // header('Location: ' . $_SERVER['HTTP_REFERER']);
            } else {
                $_SESSION['errors'] = 'L\'email renseigné ne correspond à aucun utilisateur enregistré';
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            }
        } else {
            $insert = $dbh->prepare('INSERT INTO messages (firstname, lastname, email, statut, message) VALUES (:firstname, :lastname, :email, :statut, :message)');
            $insert->bindValue(':firstname', $firstname, PDO::PARAM_STR);
            $insert->bindValue(':lastname', $lastname, PDO::PARAM_STR);
            $insert->bindValue(':email', $email, PDO::PARAM_STR);
            $insert->bindValue(':statut', $statut, PDO::PARAM_STR);
            $insert->bindValue(':message', $message, PDO::PARAM_STR);

            $insert->execute();

            $i = $dbh->lastInsertId();

            $select = $dbh->prepare('SELECT * FROM messages WHERE id = :id');
            $select->bindValue(':id', $i, PDO::PARAM_INT);
            $select->execute();

            $res_mail = $select->fetch(PDO::FETCH_OBJ);
            // dumpPre($res_mail);

            if (isset($res_mail) && $res_mail != false) {
                $ok = true;
            } else {
                $_SESSION['errors'] = "Problème avec votre adresse mail";
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            }
        }

        
    } else {
        $_SESSION['errors'] = "Problème avec votre adresse mail";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}
if ($ok) {
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
        $mail->setFrom('techno-blog@fgainza.fr', 'Administrateur de Techno-Blog');
        $mail->addAddress($email, $firstname. ' '.$lastname);
        $mail->addBCC('techno-blog@fgainza.fr');

        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Votre demande a bien été prise en compte';
        $mail->Body    = 'Bonjour, <br>
Nous vous remercions pour l\'intérêt que vous nous portez et nous répondrons à votre demande dans les plus brefs délais.
Voici les informations que vous nous avez transmises :<br><br>
    Prénom: ' . $firstname . ' <br>
    Nom: ' . $lastname . ' <br>
    Email: ' . $email . ' <br><br>
    Message: ' . $message . ' <br><br>
    Merci et à très bientôt !!<br>
    <a href="https://techno-blog.fgainza.fr">TECHNO-BLOG</a>';

        $mail->AltBody = 'Sujet : Envoi d\'un message d\un visiteur. '.$firstname. ' '. $lastname. ' (email : '.$email.')' ;

        $mail->send();
        $_SESSION['success'] = "Votre demande a été prise en compte, un email récapitulatif vient de vous être envoyé.";
        header('Location: ../contact.php');
    } catch (Exception $e) {
        $_SESSION['errors'] = "Le message n'a pas pu être envoyé. Mailer Error: {$mail->ErrorInfo}";
        header('Location: ../contact.php');
    }
}
