<?php 
session_start();
include '../toolbox.php';

$sucess=false;
if (isset($_POST['email']) && !empty($_POST['email'])){
    require 'bdd.php';

    $select = $dbh->prepare('SELECT * FROM users WHERE email = :email');
    $select->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
    $select->execute();

    $res = $select->fetch(PDO::FETCH_OBJ);

    if (isset($res) && !empty($res)) {
        $new_pwd = "";
        $min = "abcdefghijklmnopqrstuvwxyz";
        $chiffres = "0123456789";
        $maj = strtoupper($min);
        $caract_spe = "&#@_";
        $caract_total = $min.$maj.$chiffres.$caract_spe;
        $len_caract_total = strlen($caract_total);
        for ($i = 0; $i < 11; $i++) {
            $position_caract = rand(0, $len_caract_total - 1);
            $new_pwd .= $caract_total[$position_caract];
        }
        
        $pwd = password_hash($new_pwd, PASSWORD_BCRYPT);
    // $new_pwd à communiquer à l'user
    
        $insert_pwd = $dbh->prepare('UPDATE users SET password = :password WHERE email = :email');
        $insert_pwd->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
        $insert_pwd->bindValue(':password', $pwd, PDO::PARAM_STR);
        $insert_pwd->execute();
        $res_pwd = $insert_pwd->fetch(PDO::FETCH_OBJ);

        $_SESSION['email_forget_pwd'] = $_POST['email'];
        $_SESSION['mdp_temp'] = $new_pwd;
        header('Location: envoi_mail_forget_pwd.php');
     } else {
        $_SESSION['errors'] = "Nous n'avons pas trouvé votre email dans notre base de données";
        header('Location: ../index.php');
    }
} else {
    $_SESSION['errors'] = "Un problème est survenu";
    header('Location: ../index.php');
}