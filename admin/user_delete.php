<?php
require '../app/bdd.php';
    $delete = $dbh->prepare('DELETE FROM users WHERE id = :id');
    $delete->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
    $delete->execute();

$_SESSION['success_admin'] = 'L\'utilisateur a bien été supprimé';
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
