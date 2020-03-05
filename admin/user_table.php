<?php
require '../app/bdd.php';

  $select_post = $dbh->prepare('SELECT user_id, COUNT(user_id) as nb_posts FROM blog_posts LEFT JOIN users ON users.id = blog_posts.user_id GROUP BY user_id');
  $select_post->execute();
  $res_user_nb_posts = $select_post->fetchAll(PDO::FETCH_OBJ);

  $select_com = $dbh->prepare('SELECT user_id, COUNT(user_id) as nb_coms FROM blog_comments GROUP BY user_id');
  $select_com->execute();
  $res_user_nb_coms = $select_com->fetchAll(PDO::FETCH_OBJ);


  $select = $dbh->prepare('SELECT * FROM users');
  $select->execute();
  $res = $select->fetchAll(PDO::FETCH_OBJ);

?>

<?php if(isset($res) || isset($_GET['users']) && $_GET['users'] == "table") : ?>
<div class="col-12 pt-3">
  <div class="text-right">
    <button type="button" class="btn btn-dark mr-0 ml-auto"><a href="?user=ajout" class="ajout" title="Ajout">Ajouter un user</a></button>
  </div>
  <table class="my-3 w-100 table-striped">
    <thead>
      <tr>
        <th class="pl-3">Id</th>
        <th>Email</th>
        <th class="w-70p">Avatar</th>
        <th>Role</th>
        <th>Prenom</th>
        <th>Nom</th>
        <th>Nb de POST</th>
        <th>Nb de COM</th>
        <th>Date membre</th>
        <th class="text-center w-150p">Actions</th>
      </tr>
    </thead>

    <tbody>

    <?php foreach ($res as $v) : ?>
      <?php
      $created = date("d-m-Y", strtotime($v->created_at));
      ?>
      <tr>
        <td class="pl-3"><?= $v->id; ?></td>
        <td><?= $v->email; ?></td>
        <td><img src="<?= $v->avatar; ?>"></td>
        <td <?= $v->role == 'admin' ? ' class="text-danger"' : ''; ?>>
          <?= $v->role; ?>
        </td>
        <td><?= $v->firstname; ?></td>
        <td><?= $v->lastname; ?></td>
        <td class="text-center">
          <?php 
            foreach ($res_user_nb_posts as $v1){
              $id = $v1->user_id;
              $nb_posts = $v1->nb_posts;
              if ($v->id == $id){
                echo $nb_posts;
              }
            }
          ?>
        </td>
        <td class="text-center">
          <?php 
            foreach ($res_user_nb_coms as $v2){
              $id = $v2->user_id;
              $nb_coms = $v2->nb_coms;
              if ($v->id == $id){
                echo $nb_coms;
              }
            }                   
          ?>
        </td>
        <td><?= $created; ?></td>
        
          <td class="text-center">
            <a href="../user_profil.php?&id=<?= $v->id ?>" class="view pr-1" title="view"><i class="material-icons color-green">visibility</i></a>                  
            <a href="?user=edit&id=<?= $v->id ?>" class="edit pr-1" title="Edit"><i class="material-icons color-blue"></i></a>
            <a href="?user=delete&id=<?= $v->id ?>" class="delete pr-1" title="Delete"><i class="material-icons color-red"></i></a>
        </td>
      </tr>
    <?php endforeach ?>
  </tbody>

</table>
<?php endif; ?>
    <?php 'require footer_admin.php'; ?>