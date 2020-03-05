<?php
session_start();
require '../toolbox.php';
if (isset($_POST['email']) && !empty($_POST['email'])){
    
    $email = valid_donnees($_POST['email']);
    if (isset($_POST['remember']) && $_POST['remember'] == "ok"){
        // si il a coché la case j'enregistre l'email dans un cookie
        setcookie('remember', $email, time() + 3600*24*365,  '/');
    }
    if (!empty($email)) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

            require 'bdd.php';

            $select = $dbh->prepare('SELECT * FROM users where email = :email');
            $select->bindValue(':email', $email, PDO::PARAM_STR);
            $select->execute();

            $res_log = $select->fetch(PDO::FETCH_OBJ);
            // printPre($res_log);
            // exit;

            if (isset($res_log)) {
                if (!empty($_POST['password'])) {
                    $password_verify = password_verify($_POST['password'], $res_log->password);

                    if ($email === $res_log->email && $password_verify){

                        $_SESSION['log_firstname'] = ucfirst($res_log->firstname);
                        $_SESSION['log_lastname'] = ucfirst($res_log->lastname);
                        $_SESSION['log_id'] = $res_log->id;
                        $_SESSION['log_avatar'] = $res_log->avatar;

                        if(!empty($res_log->role) && $res_log->role === 'user' || !empty($res_log->role) && $res_log->role === 'admin' && $res_log->validate == '0'){
                            $_SESSION['user_access'] = true;
                            header('Location: ../blog_index.php');
                        }

                        if(!empty($res_log->role) && $res_log->role === 'admin' && $res_log->validate == '1'){
                            $_SESSION['user_access'] = true;                            
                            $_SESSION['admin_access'] = true;
                            header('Location: ../admin/admin.php');
                        }
                    } else {
                        $_SESSION['errors'] = "Mauvais mot de passe ou mauvais email.";
                        header('Location: ' . $_SERVER['HTTP_REFERER']);
                    }
                   
                    
                   
                } else {
                    $_SESSION['errors'] = "Vous n'avez pas renseigné de mot de passe";
                    header('Location: ' . $_SERVER['HTTP_REFERER']);
                }
            } else {
                $_SESSION['errors'] = "Votre email est déjà utilisé; veuillez vous connecter";
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            }
        } else {
            $_SESSION['errors'] = "Votre email n'est pas valide";
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        }
    } else {
        $_SESSION['errors'] = "Vous n'avez pas rempli correctement le champ email";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

}
  
        
