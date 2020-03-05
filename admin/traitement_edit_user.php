<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '../app/vendor/PHPMailer-master/src/Exception.php';
require '../app/vendor/PHPMailer-master/src/PHPMailer.php';
require '../app/vendor/PHPMailer-master/src/SMTP.php';

require '../app/can_connect_admin.php';
require '../app/bdd.php';
require '../toolbox.php';

if (isset($_POST['reset_pwd']) && $_POST['reset_pwd'] === 'valid'){
    $new_pwd = "";
    $min = "abcdefghijklmnopqrstuvwxyz";
    $chiffres = "0123456789";
    $maj = strtoupper($min);
    $caract_spe = "&#{[|\^@$*%<>!§?;";
    $caract_total = $min.$maj.$chiffres.$caract_spe;
    $len_caract_total = strlen($caract_total);
    for ($i = 0; $i < 11; $i++) {
        $position_caract = rand(0, $len_caract_total - 1);
        $new_pwd .= $caract_total[$position_caract];
    }
    
    $pwd = password_hash($new_pwd, PASSWORD_BCRYPT);
    $_SESSION['mdp_temp'] = $new_pwd;
    $mail = true;
    // $new_pwd à communiquer à l'user

    $insert_pwd = $dbh->prepare('UPDATE users SET password = :password WHERE id = :id');
    $insert_pwd->bindValue(':id', $_POST['id_user'], PDO::PARAM_INT);
    $insert_pwd->bindValue(':password', $pwd, PDO::PARAM_STR);
    $insert_pwd->execute();
    $res_pwd = $insert_pwd->fetch(PDO::FETCH_OBJ);
}

if (!empty($_POST)) {

    $id = $_POST['id_user'];
    $avatar = $_POST['avatar'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    
    
    $insert = $dbh->prepare('UPDATE users SET avatar = :avatar, firstname = :firstname, lastname = :lastname, email = :email, role= :role WHERE id = :id');
    $insert->bindValue(':id', $id, PDO::PARAM_INT);
    $insert->bindValue(':avatar', $avatar, PDO::PARAM_STR);
    $insert->bindValue(':firstname', $firstname, PDO::PARAM_STR);
    $insert->bindValue(':lastname', $lastname, PDO::PARAM_STR);
    $insert->bindValue(':email', $email, PDO::PARAM_STR);
    $insert->bindValue(':role', $role, PDO::PARAM_STR);

    $insert->execute();

    $res_edit = $insert->fetch(PDO::FETCH_OBJ);

    // var_dump($res_edit);exit;
    if (isset($_SESSION ["lien_avatar"])){
        unset($_SESSION ["lien_avatar"]);
    }     
    
    if ($mail){
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
            $mail->Password = 'Bifj29!3';                           // SMTP password
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
            $mail->Subject = 'Réinitialisation de votre mot de passe';
                $mail->Body    = 'Bonjour,<br>
                Par mesure de sécurité, une réinitialisation de votre mot de passe a été effectuée.<br>
                Votre mot de passe temporaire est le suivant :<br>'
                .$_SESSION['mdp_temp'].'<br><br>
                Nous vous conseillons fortement de modifier votre passe lors de votre prochaine connexion.<br>
                Merci et à très bientôt !!<br>
                <a href="https://techno-blog.fgainza.fr">TECHNO-BLOG</a>';

            $mail->AltBody = 'Sujet : Changement de mdp par mesure de sécurité. '.$firstname. ' '. $lastname. ' (email : '.$email.')' ;

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $_SESSION['success_admin'] = 'Mise à jour correctement réalisée des infos de l\'utilisateur';
    }

} else {
    $_SESSION['errors_admin'] = 'Un problème est survenu';
}
$_SESSION['success_admin'] = 'Mise à jour correctement réalisée des infos de l\'utilisateur';
header('Location: admin.php?user=table');