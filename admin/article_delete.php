<?php
require '../app/can_connect_admin.php';
require '../app/bdd.php';

    $delete = $dbh->prepare('DELETE FROM blog_posts WHERE id = :id');
    $delete->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
    $delete->execute();

    $_SESSION['success_admin'] = 'L\'article a bien été supprimé';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
