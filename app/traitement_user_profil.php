<?php
    session_start();
    require 'bdd.php';
    require '../toolbox.php';

    if (isset($_POST) && !empty($_POST) && isset($_SESSION) && !empty($_SESSION)) {
        $id = $_SESSION['log_id'];
        $reconnect = false;
        $avatar = isset($_SESSION['lien_avatar']) ? $_SESSION['lien_avatar'] : $_SESSION['log_avatar'];

        if (!empty($_POST['password']) || !empty($_POST['password2'])) {
            if ($_POST['password'] === $_POST['password2']){
                $pwd_base = password_hash($_POST['password'], PASSWORD_BCRYPT);
                $reconnect = true;

                $insert = $dbh->prepare('UPDATE users SET avatar = :avatar, password = :password WHERE id = :id');
                $insert->bindValue(':avatar', $avatar, PDO::PARAM_STR);
                $insert->bindValue(':id', $id, PDO::PARAM_INT);
                $insert->bindValue(':password', $pwd_base, PDO::PARAM_STR);
            
                $insert->execute();
            
                $res_edit = $insert->fetch(PDO::FETCH_OBJ);
                // var_dump($res_edit);exit;
        
                if ($reconnect){
                    $_SESSION['success'] = 'Mise à jour du profil effectuée. Merci de vous reconnecter avec votre nouveau mot de passe';
                    // dumpPre($_SESSION);exit;
                    header('Location: disconnect_reconnect.php');
                } else {
                    $_SESSION['success'] = 'Profil mis à jour';
                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                }

            } else {
                $_SESSION['errors'] = "Les 2 mots de passe ne sont pas identiques";
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            }
        } else {
            $insert = $dbh->prepare('UPDATE users SET avatar = :avatar WHERE id = :id');
            $insert->bindValue(':avatar', $avatar, PDO::PARAM_STR);
            $insert->bindValue(':id', $id, PDO::PARAM_INT);
        
            $insert->execute();

            $_SESSION['success'] = 'Votre profil a été mis à jour';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }

    } else {
        $_SESSION['errors'] = "Un problème est survenu";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }


