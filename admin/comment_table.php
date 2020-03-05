<?php
require '../app/bdd.php';

  $select_comment = $dbh->prepare('SELECT * FROM blog_comments');
  $select_comment->execute();
  $res_comment = $select_comment->fetchAll(PDO::FETCH_OBJ);

?>

<?php if(isset($res_comment) || isset($_GET['comment']) && $_GET['comment'] == "table") : ?>
  <div class="col-12 pt-3">
    <table class="my-3 w-100 table-striped">
      <thead>
        <tr>
          <th  class="pl-3">ID</th>
          <th>Post ID</th>
          <th>Commentaire</th>
          <th>User_ID</th>
          <th>Date</th>
          <th class="text-center w-200p">Actions</th>
        </tr>
      </thead>

      <tbody>
        <?php foreach ($res_comment as $v) : ?>
          <?php
          $created = date("d-m-Y", strtotime($v->created_comment_at));
          ?>
          <tr>
            <td class="pl-3"><?= $v->id; ?></td>
            <td><a href="../article.php?id=<?= $v->post_id; ?>" class="text-primary"><?= $v->post_id; ?></td></a>
            <td><?= $v->commentaire; ?></td>
            <td><a href="?user=table&id=<?= $v->user_id; ?>" class="text-primary"><?= $v->user_id; ?></td></a>
            <td><?= $created; ?></td>
            
              <td class="text-center">
                <a href="../article.php?id=<?= $v->post_id ?>" class="add pr-3" title="Add"><i class="material-icons text-info ml-1">add_circle</i></a>
                <a href="../article.php?id=<?= $v->post_id ?>" class="view pr-3" title="view"><i class="material-icons color-green">visibility</i></a>                  
                <a href="?comment=edit&id=<?= $v->id ?>" class="edit pr-3" title="Edit"><i class="material-icons color-blue"></i></a>
                <a href="?comment=delete&id=<?= $v->id ?>" class="delete" title="Delete"><i class="material-icons color-red"></i></a>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>

    </table>
  <?php endif; ?>
<?php 'require footer_admin.php'; ?>


