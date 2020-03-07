<?php
session_start();

require '../app/can_connect_admin.php';
require '../toolbox.php';
require '../app/bdd.php';

require '../app/vendor/PHPMailer-master/src/Exception.php';
require '../app/vendor/PHPMailer-master/src/PHPMailer.php';
require '../app/vendor/PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;


$_SESSION['success_admin'] = "";
$_SESSION['errors_admin'] = "";

// printPre($_SESSION['log_id']);
// exit;

if (isset($_POST) && !empty($_POST)) {
    foreach ($_POST as $k => $v) {
        if (!empty($v)) {
            $succes;
        } else {
            $_SESSION['errors_admin'] = "Vous n'avez pas correctement rempli un ou plusieurs champs";
        }
    }
}

if ($_SESSION['errors_admin'] === "") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Attribtion d'un avatar https://avatars.dicebear.com/v2/avataaars/
    // et d'un mdp temporaire
    $mdp_temp = "";
    $code_avatar = "";

    $min = "abcdefghijklmnopqrstuvwxyz";
    $caract = $min . "0123456789";
    $maj = strtoupper($min);
    $caract_spe = $caract . "&#{[|\^@$*%<>!§?;";

    //mdp
    $caract_total = $caract . $maj . $caract_spe;
    $len_caract_total = strlen($caract_total);
    for ($i = 0; $i < 11; $i++) {
        $position_caract = rand(0, $len_caract_total - 1);
        $mdp_temp .= $caract_total[$position_caract];
    }

    //avatar
    $len = strlen($caract);
    for ($i = 0; $i < 20; $i++) {
        $position_caract = rand(0, $len - 1);
        $code_avatar .= $caract[$position_caract];
    }

    $lien_avatar = "https://avatars.dicebear.com/v2/bottts/" . $code_avatar . ".svg";

    $pwd_base = password_hash($mdp_temp, PASSWORD_BCRYPT);

    $insert = $dbh->prepare('INSERT INTO users (avatar, firstname, lastname, email, password, role) VALUES (:avatar, :firstname, :lastname, :email, :password, :role)');
    $insert->bindValue(':avatar', $lien_avatar, PDO::PARAM_STR);
    $insert->bindValue(':firstname', $firstname, PDO::PARAM_STR);
    $insert->bindValue(':lastname', $lastname, PDO::PARAM_STR);
    $insert->bindValue(':email', $email, PDO::PARAM_STR);
    $insert->bindValue(':password', $pwd_base, PDO::PARAM_STR);
    $insert->bindValue(':role', $role, PDO::PARAM_STR);

    $insert->execute();


    $res_insert = $insert->fetch(PDO::FETCH_OBJ);


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
        $mail->Password = 'xxxxxxxxx';                           // SMTP password
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
        $mail->Subject = 'Vous avez été incrit sur notre site';
        $mail->Body    = 'Bonjour, <br>
    Vous avez été inscrit sur notre site par un de nos admins.<br>
    Retrouvez ici les informations relatives à votre compte.<br>
    Nous vous communiquons notamment un mot de passe temporaire; n\'oubliez pas de le changer lors de votre 1ere connexion.<br><br>
    
    Voici les informations enregistrées&nbsp;:&nbsp;<br><br>
        Prénom: ' . $firstname . ' <br>
        Nom: ' . $lastname . ' <br>
        Email: ' . $email . ' <br>
        Mot de passe temporaire: ' . $mdp_temp . ' <br><br>
        Merci et à très bientôt !!<br>
        <a href="https://techno-blog.fgainza.fr">TECHNO-BLOG</a>';

        $mail->AltBody = 'Sujet : Vous avez été incrit sur notre site (ajout d\'un utilisateur par un admin). '.$firstname. ' '. $lastname. ' (email : '.$email.')' ;

        $mail->send();
        $_SESSION['success_admin'] = "Utilisateur ajouté";
        header('Location: ' .$_SERVER['HTTP_REFERER']);
    } catch (Exception $e) {
        $_SESSION['errors'] = "Le message n'a pas pu être envoyé. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    $_SESSION['errors_admin'] = 'Un problème est survenu';
    header('Location: ' .$_SERVER['HTTP_REFERER']);
}
