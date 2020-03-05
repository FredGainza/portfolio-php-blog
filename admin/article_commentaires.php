
<?php
require '../app/bdd.php';

  $select = $dbh->prepare('SELECT * FROM blog_comments');
  $select->execute();
  $res = $select->fetchAll(PDO::FETCH_OBJ);

?>

<?php if(isset($res) || isset($_GET['affiche']) && $_GET['affiche'] == 'ok') : ?>
  <div class="col-12 pt-3">
  <table class="my-3 w-100">
    <thead>
      <tr>
        <th>Id</th>
        <th>Article</th>
        <th>Auteur du com</th>
        <th>Commentaire</th>
        <th>Date</th>
        <th data-field="actions" data-formatter="operateFormatter" data-events="operateEvents" class="w-100p">Actions</th>       
      </tr>
    </thead>

    <tbody>       
      <?php foreach ($res as $value) : ?>
        <?php
          $date_sql = $value->date;
          $created = date_format($date_sql, 'Y-m-d H:i:s');
          
        ?>
        <tr>
          <td><?= $value->id; ?></td>
          <td><a href="../images/<?= $value->image; ?>" target="_blank"><img src="../images/miniatures/<?= $value->image; ?>"></a></td>
          <td><?= $value->category; ?></td>
          <td><?= $value->user_id; ?></td>
          <td><?= $value->title; ?></td>
          <td><?= $seulementDate; ?></td>
          <td><?= $value->label; ?></td>
          <td><?= $value->description; ?></td>
          <td><?= $value->content; ?></td>
          <td><?= $created; ?></td>          
          <td><a href="../article.php?id=<?= $value->id ?>" class="view" title="view" data-toggle="tooltip"><i class="material-icons color-green mb-3">visibility</i></a>
            <a href="?article=ajout&id=<?= $value->id ?>" class="delete" title="Delete" data-toggle="tooltip"><i class="material-icons text-info">add_circle</i></a>
            
          <a href="?article=edit&id=<?= $value->id ?>" class="edit" title="Edit" data-toggle="tooltip"><i class="material-icons color-blue mb-3"></i></a>
          <a href="?article=delete&id=<?= $value->id ?>" class="delete" title="Delete" data-toggle="tooltip"><i class="material-icons color-red"></i></a>
          </td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
<?php endif; ?>

<?php 'require footer_admin.php'; ?>