<?php
require '../app/bdd.php';

    $delete = $dbh->prepare('DELETE FROM blog_comments WHERE id = :id');
    $delete->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
    $delete->execute();

$_SESSION['success_admin'] = 'Le commentaire a bien été supprimé';

header('Location: admin.php?comment=table'); 


?>