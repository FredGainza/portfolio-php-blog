<?php
session_start();
require 'bdd.php';
require '../toolbox.php';
// dumpPre($_SESSION);
// dumpPre($_POST);
// exit;

if (isset($_POST) && !empty($_POST) && isset($_SESSION) && !empty($_SESSION)) {
    $id = $_SESSION['log_id'];
    $pwdChange = false;
    $emailChange = false;
    $avatarChange = false;

    $_SESSION['lien_avatar'] = '';
    // On vérifie si l'avatar a été modifié
    if ($_POST['post_avatar'] != $_SESSION['log_avatar']){
        $avatarChange = true;
        $avatar = $_POST['post_avatar'];
    } else {
        $avatar = $_SESSION['log_avatar'];
    }


    // On vérifie si le mail a été changé
    if ($_POST['email2'] != $_SESSION['log_email']){
        if (!empty($_POST['email2'])) {
            // Si l'email a été modifié, on sécurise les données entrées
            $email2 = valid_donnees($_POST['email2']);

            // On regarde si ce mail est déjà dans la bdd
            $select = $dbh->prepare('SELECT * FROM users where email = :email');
            $select->bindValue(':email', $email2, PDO::PARAM_STR);
            $select->execute();

            $res = $select->fetch(PDO::FETCH_OBJ);

            if (!$res) {
                $email = $email2;
                $emailChange = true;
            } else {
                $_SESSION['errors'] = "Cet email est déjà enregistré. Merci d'en choisir un autre";
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit;
            }

        } else {
            $_SESSION['errors'] = "Vous n'avez pas indiqué d'adresse email";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    } else {
        $email = $_SESSION['log_email'];
    } 

    // On vérifie si le mdp a été changé
    if (!empty($_POST['password']) && !empty($_POST['password2'])) {
        if ($_POST['password'] === $_POST['password2']) {
            $pwd_base = password_hash($_POST['password'], PASSWORD_BCRYPT);      
            $pwdChange = true;
        } else {
            $_SESSION['errors'] = "Les 2 mots de passe ne sont pas identiques";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    } else if (empty($_POST['password']) && empty($_POST['password2'])) {
        $select_user = $dbh->prepare('SELECT * FROM users where id = :id');
        $select_user->bindValue(':id', $id, PDO::PARAM_INT);
        $select_user->execute();

        $res_user = $select_user->fetch(PDO::FETCH_OBJ);
        $pwd_base = $res_user->password;
    } else {
        $_SESSION['errors'] = "Un problème est survenu. <br>
                                Merci de réessayer.";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Si des modifs ont eu lieu, on met à jour dans la bdd
    if ($emailChange || $pwdChange || $avatarChange){
        $insert = $dbh->prepare('UPDATE users SET avatar = :avatar, email = :email, password = :password WHERE id = :id');
        $insert->bindValue(':avatar', $avatar, PDO::PARAM_STR);
        $insert->bindValue(':email', $email, PDO::PARAM_STR);
        $insert->bindValue(':id', $id, PDO::PARAM_INT);
        $insert->bindValue(':password', $pwd_base, PDO::PARAM_STR);

        $insert->execute();

        $res_edit = $insert->fetch(PDO::FETCH_OBJ);

        if ($emailChange || $pwdChange){
            $_SESSION['success'] = 'Mise à jour du profil effectuée. <br>
                                    Merci de vous reconnecter avec vos nouveaux identifiants.';
            header('Location: disconnect_reconnect.php');
            exit;
        } else {
            $_SESSION['success'] = 'Profil mis à jour';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    } else {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

} else {
    $_SESSION['errors'] = "Un problème est survenu. <br>
                            Merci de réessayer.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}
