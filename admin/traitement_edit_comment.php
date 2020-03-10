<?php
session_start();
require '../app/bdd.php';

if (isset($_POST['moderate']) && $_POST['moderate'] == "yes") {
  $com_moderate = '<span class="text-danger">COMMENTAIRE MODERE</span><br>' . $_POST['com'];

  $update = $dbh->prepare('UPDATE blog_comments SET commentaire = :commentaire WHERE id = :id');
  $update->bindValue(':id', $_POST['id'], PDO::PARAM_INT);
  $update->bindValue(':commentaire', $com_moderate, PDO::PARAM_STR);
  $update->execute();

  $resultat = $update->fetchAll(PDO::FETCH_OBJ);

  $_SESSION['success_admin'] = "Commentaire correctement édité";
  header('Location: admin.php?comment=table');
  exit;
}
