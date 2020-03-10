<?php
session_start();
require '../toolbox.php';

if (isset($_POST) && isset($_SESSION)) {

    if (isset($_POST['com']) && $_POST['com'] === 'commentaire_traitement') {
        if (isset($_SESSION['user_access'])) {
            if (isset($_POST['commentaire']) && !empty($_POST['commentaire'])) {

                require 'bdd.php';

                $insert = $dbh->prepare('INSERT INTO blog_comments (post_id, user_id, commentaire) VALUES (:post_id, :user_id, :commentaire)');
                $insert->bindValue(':post_id', $_POST['post_id'], PDO::PARAM_INT);
                $insert->bindValue(':user_id', $_SESSION['log_id'], PDO::PARAM_INT);
                $insert->bindValue(':commentaire', $_POST['commentaire'], PDO::PARAM_STR);

                $insert->execute();

                $res_insert = $insert->fetch(PDO::FETCH_OBJ);

                $_SESSION['comm'] = $res_insert->commentaire;
            } else {
                $_SESSION['errors'] = 'Vous n\'avez rien indiqué comme commentaire';
            }
        } else {
            $_SESSION['errors'] = 'Vous n\'êtes pas membre, vous ne pouvez pas commenter.<br>
                Cliquez&nbsp;<a class="nav-link d-inline link-alert-error soulign" href="index.php">ici</a>&nbsp;pour vous connecter<br>
                Cliquez&nbsp;<a class="nav-link d-inline link-alert-error soulign" href="inscription.php">ici</a>&nbsp;pour vous enregistrer';
        }
    }
}
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
