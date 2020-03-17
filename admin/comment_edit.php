<?php
require '../app/bdd.php';


$select = $dbh->prepare('SELECT * FROM users LEFT JOIN blog_comments ON blog_comments.user_id = users.id WHERE blog_comments.id = :id');
$select->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
$select->execute();
$res = $select->fetch(PDO::FETCH_OBJ);

$created = $res->created_comment_at;
$created = date("d-m-Y à H:i:s", strtotime($created));

?>

<div class="col-lg-12 pt-3">
  <span class="h3 font-weight-bold ml-3">Edition de commentaire</span>
  <form action="traitement_edit_comment.php" method="POST" class="py-3 px-2">
    <h5>Commentaire envoyé par : <?= $res->firstname; ?> <?= $res->lastname; ?>, le <?= $created; ?></h5>
    <div class="form-row py-3">
      <textarea name="com" id="com" class="px-1 w-50" width="70%" rows="10"><?= $res->commentaire . '<br>----------<br><span class="text-danger">Raison de la modération: </span>'; ?></textarea>
    </div>
    <input type="hidden" name="moderate" value="yes">
    <input type="hidden" name="id" value="<?= $res->id; ?>">
    <button type="submit" class="btn btn-dark my-2 w-edit-com margin-auto">Mettre à jour</button>
  </form>
</div>