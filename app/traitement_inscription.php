<?php
session_start();
require 'bdd.php';
require '../toolbox.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'vendor/PHPMailer-master/src/Exception.php';
require 'vendor/PHPMailer-master/src/PHPMailer.php';
require 'vendor/PHPMailer-master/src/SMTP.php';
// printPre($_POST);
// exit;
// if (isset($_POST['inscription']) && $_POST['inscription'] === 1 && !isset($_POST['connexion']))
if (isset($_POST) && !empty($_POST)) {
    if (!empty($_POST['firstname']) && !empty($_POST['lastname']) && !empty($_POST['role'])) {
        if (!empty($_POST['email'])) {
            if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {

                $firstname = valid_donnees($_POST['firstname']);
                $firstname = ucfirst($firstname);
                $lastname = valid_donnees($_POST['lastname']);
                $lastname = ucfirst($lastname);
                $email = valid_donnees($_POST['email']);


                $select = $dbh->prepare('SELECT * FROM users where email = :email');
                $select->bindValue(':email', $email, PDO::PARAM_STR);
                $select->execute();

                $res = $select->fetch(PDO::FETCH_OBJ);

                if (!$res) {
                    if (!empty($_POST['password'])) {
                        $pwd_base = password_hash($_POST['password'], PASSWORD_BCRYPT);

                        // Attribution d'un avatar https://avatars.dicebear.com/v2/avataaars/
                        $code_avatar = "";
                        $caract = "abcdefghijklmnopqrstuvwxyz0123456789";
                        $len = strlen($caract);
                        for ($i = 0; $i < 20; $i++) {
                            $position_caract = rand(0, $len - 1);
                            $code_avatar .= $caract[$position_caract];
                        }

                        $lien_avatar = "https://avatars.dicebear.com/v2/bottts/" . $code_avatar . ".svg";

                        $insert = $dbh->prepare('INSERT INTO users (avatar, firstname, lastname, email, password, role) VALUES (:avatar, :firstname, :lastname, :email, :password, :role)');
                        $insert->bindValue(':avatar', $lien_avatar, PDO::PARAM_STR);
                        $insert->bindValue(':firstname', $firstname, PDO::PARAM_STR);
                        $insert->bindValue(':lastname', $lastname, PDO::PARAM_STR);
                        $insert->bindValue(':email', $email, PDO::PARAM_STR);
                        $insert->bindValue(':password', $pwd_base, PDO::PARAM_STR);
                        $insert->bindValue(':role', $_POST['role'], PDO::PARAM_STR);


                        $insert->execute();

                        $res_insert = $insert->fetchAll(PDO::FETCH_OBJ);

                        $_SESSION['success'] = 'Utilisateur enregistré';

                        // $_SESSION['prenom'] = $firstname;
                        // $_SESSION['email'] = $email;

                        $role = $_POST['role'];

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
                            $mail->addAddress($email, $firstname . ' ' . $lastname);
                            $mail->addBCC('techno-blog@fgainza.fr');


                            // Content
                            $mail->isHTML(true);                                  // Set email format to HTML
                            $mail->Subject = 'Inscription sur Techno-Blog validée';

                            if ($role == "user") {
                                $body = file_get_contents('../mail/welcome_user.html');
                                $body = str_replace('$firstname', $firstname, $body);
                                $body = str_replace('$lastname', $lastname, $body);

                                $body = preg_replace('/\\\\/', '', $body);

                                $mail->MsgHTML($body);

                                $mail->AltBody = 'Sujet : Enregistrement d\'un utilisateur. ' . $firstname . ' ' . $lastname . ' ' . $role . ' (email : ' . $email . ')';
                            }
                            if ($role == "admin") {
                                $body = file_get_contents('../mail/welcome_admin.html');
                                $body = str_replace('$firstname', $firstname, $body);
                                $body = str_replace('$lastname', $lastname, $body);

                                $body = preg_replace('/\\\\/', '', $body);

                                $mail->MsgHTML($body);

                                $mail->AltBody = 'Sujet : Enregistrement d\'un admin. ' . $firstname . ' ' . $lastname . ' ' . $role . ' (email : ' . $email . ')';
                            }

                            $mail->send();
                            if (!empty($_POST['role']) && $_POST['role'] === 'user') {
                                $_SESSION['user_access'] = true;                                
                                $_SESSION['success'] = 'Félicitations, vous êtes enregistré !!<br>
                                                        Vous pouvez maintenant vous connecter.<br>';
                            }

                            if (!empty($_POST['role']) && $_POST['role'] === 'admin') {
                                $_SESSION['user_access'] = true;
                                $_SESSION['admin_access'] = true;
                                $_SESSION['success'] = 'Félicitations, vous êtes enregistré en tant qu\'administrateur!!<br>
                                                        Vous pouvez maintenant vous connecter.<br>
                                                        Vos fonctionnalités d\'administrateur doivent être validées avant d\'être actives (délais de 48h au maximum)';
                            }
                            header('Location: ../index.php');
                            exit;
                        } catch (Exception $e) {
                            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                        }
                    } else {
                        $_SESSION['errors'] = "Vous n'avez pas renseigné de mot de passe";
                        header('Location: ' . $_SERVER['HTTP_REFERER']);
                        exit;
                    }
                } else {
                    $_SESSION['errors'] = "Votre email est déjà utilisé. Veuillez vous connecter";
                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                    exit;
                }
            } else {
                $_SESSION['errors'] = "Votre email n'est pas valide";
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit;
            }
        } else {
            $_SESSION['errors'] = "Vous n'avez pas rempli correctement le champ email";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    } else {
        $_SESSION['errors'] = "Vous n'avez pas rempli correctement certains champs";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}
