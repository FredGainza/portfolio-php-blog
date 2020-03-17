<?php
require '../app/bdd.php';

$select_comment = $dbh->prepare('SELECT * FROM blog_comments');
$select_comment->execute();
$res_comment = $select_comment->fetchAll(PDO::FETCH_OBJ);













?>

<?php if (isset($res_comment) || isset($_GET['comment']) && $_GET['comment'] == "table") : ?>
  <div class="col-12  d-resp-xl pt-3"> 

    <table class="my-3 w-97 table-striped">
      <thead>
        <tr>
          <th class="pl-3">ID Comment</th>
          <th>Article</th>
          <th>Commentaire</th>
          <th>User</th>
          <th>Date</th>
          <th class="text-center w-200p">Actions</th>
        </tr>
      </thead>

      <tbody>
        <?php foreach ($res_comment as $v) : ?>
          <?php
            $created = date("d-m-Y", strtotime($v->created_comment_at));

            $user_id = $v->user_id;
            $select_user = $dbh->prepare('SELECT * FROM users WHERE id = :id');
            $select_user->bindValue(':id', $user_id, PDO::PARAM_INT);   
            $select_user->execute();         
            $res_user = $select_user->fetch(PDO::FETCH_OBJ);
            $user_name = ucfirst($res_user->firstname). ' ' .ucfirst($res_user->lastname). ' (' .ucfirst($res_user->role). ')';

            $post_id = $v->post_id;
            $select_post = $dbh->prepare('SELECT * FROM blog_posts WHERE id = :id');
            $select_post->bindValue(':id', $post_id, PDO::PARAM_INT);
            $select_post->execute();
            $res_post = $select_post->fetch(PDO::FETCH_OBJ);
            $post_name = $res_post->title;
          ?>
          <tr>
            <td class="pl-3"><?= $v->id; ?></td>
            <td><a href="../article.php?id=<?= $v->post_id; ?>" class="text-primary"><?= $post_name; ?></td></a>
            <td><?= $v->commentaire; ?></td>
            <td><a href="../user_profil.php?&id=<?= $v->user_id; ?>" class="text-primary"><?= $user_name; ?></td></a>
            <td><?= $created; ?></td>
            <td class="text-center">
              <a href="../article.php?id=<?= $v->post_id ?>" class="add pr-3" title="Add"><i class="material-icons text-info ml-1">add_circle</i></a>
              <a href="../article.php?id=<?= $v->post_id ?>" class="view pr-3" title="view"><i class="material-icons color-green">visibility</i></a>
              <a href="?comment=edit&id=<?= $v->id ?>" class="edit pr-3" title="Edit"><i class="material-icons color-blue"></i></a>
              <a href="?comment=delete&id=<?= $v->id ?>" class="delete" title="Delete" onclick="return confirm('Etes-vous certain de votre choix ?')"><i class="material-icons color-red"></i></a>
            </td>
          </tr>

        <?php endforeach ?>
      </tbody>

    </table>
  </div>
  <div class="col-12 d-resp-xs d-resp-norm pt-3 text-center">
    <table class="my-3 with-resp-97 table-striped comment">
      <?php foreach ($res_comment as $v) : ?>
      <?php
        $created = date("d-m-Y", strtotime($v->created_comment_at));

        $user_id = $v->user_id;
        $select_user = $dbh->prepare('SELECT * FROM users WHERE id = :id');
        $select_user->bindValue(':id', $user_id, PDO::PARAM_INT);   
        $select_user->execute();         
        $res_user = $select_user->fetch(PDO::FETCH_OBJ);
        $user_name = ucfirst($res_user->firstname). ' ' .ucfirst($res_user->lastname). ' (' .ucfirst($res_user->role). ')';

        $post_id = $v->post_id;
        $select_post = $dbh->prepare('SELECT * FROM blog_posts WHERE id = :id');
        $select_post->bindValue(':id', $post_id, PDO::PARAM_INT);
        $select_post->execute();
        $res_post = $select_post->fetch(PDO::FETCH_OBJ);
        $post_name = $res_post->title;
      ?>
      <tr>
        <th>ID Comment</th>
        <td class="text-center b-top"><?= $v->id; ?></td>
      </tr>
      <tr>
        <th>Article</th>
        <td class="text-center"><a href="../article.php?id=<?= $v->post_id; ?>" class="text-primary"><?= $post_name; ?></td></a>
      </tr>
      <tr>
        <th>Commentaire</th>
        <td class="text-center"><?= $v->commentaire; ?></td>
      </tr>
      <tr>
        <th>User</th>
        <td class="text-center"><a href="../user_profil.php?&id=<?= $v->user_id; ?>" class="text-primary"><?= $user_name; ?></td></a>
      </tr>
      <tr>
        <th>Date</th>
        <td class="text-center"><?= $created; ?></td>
      </tr>
      <tr>  
        <th>Actions</th>
        <td class="text-center">
          <a href="../article.php?id=<?= $v->post_id ?>" class="add pr-3" title="Add"><i class="material-icons text-info ml-1">add_circle</i></a>
          <a href="../article.php?id=<?= $v->post_id ?>" class="view pr-3" title="view"><i class="material-icons color-green">visibility</i></a>
          <a href="?comment=edit&id=<?= $v->id ?>" class="edit pr-3" title="Edit"><i class="material-icons color-blue"></i></a>
          <a href="?comment=delete&id=<?= $v->id ?>" class="delete" title="Delete" onclick="return confirm('Etes-vous certain de votre choix ?')"><i class="material-icons color-red"></i></a>
        </td>
      </tr>
      <tr><td colspan="2" class="border-dark-comment"></td></tr>

      <?php endforeach ?>


    </table>

  </div>
  <?php endif; ?>
  <?php 'require footer_admin.php'; ?>