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
$result = $select->fetchAll(PDO::FETCH_OBJ);

$page = isset($_GET['page']) ? $_GET['page'] : 0;
$debut = '';
$limit = 8;
if ($debut == "") {
  $debut = 0;
} else {
  $debut = $page * $limit;
}
$nb_total = $select->rowCount();
$limite = $dbh->prepare("SELECT * FROM users limit $debut,$limit");
$limit_str = "LIMIT " . $page * $limit . ",$limit";

$resultat = $dbh->prepare("SELECT * FROM users ORDER BY id ASC $limit_str");
$resultat->execute();
$res = $resultat->fetchAll(PDO::FETCH_OBJ);
?>

<?php if (isset($res) || isset($_GET['users']) && $_GET['users'] == "table") : ?>
  <div class="col-12 pt-3">
    <div class="text-right">
      <button type="button" class="btn btn-dark ml-auto px-5"><a href="?user=ajout" class="ajout" title="Ajout">Ajouter un User</a></button>
    </div>
  </div>

  <div class="col-12 d-resp-xl pt-3">

    <table class="my-3 w-97 table-striped">
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
              foreach ($res_user_nb_posts as $v1) {
                $id = $v1->user_id;
                $nb_posts = $v1->nb_posts;
                if ($v->id == $id) {
                  echo $nb_posts;
                }
              }
              ?>
            </td>
            <td class="text-center">
              <?php
              foreach ($res_user_nb_coms as $v2) {
                $id = $v2->user_id;
                $nb_coms = $v2->nb_coms;
                if ($v->id == $id) {
                  echo $nb_coms;
                }
              }
              ?>
            </td>
            <td><?= $created; ?></td>

            <td class="text-center">
              <a href="../user_profil.php?&id=<?= $v->id ?>" class="view pr-1" title="view"><i class="material-icons color-green">visibility</i></a>
              <a href="?user=edit&id=<?= $v->id ?>" class="edit pr-1" title="Edit"><i class="material-icons color-blue"></i></a>
              <a href="?user=delete&id=<?= $v->id ?>" class="delete pr-1" title="Delete" onclick="return confirm('Etes-vous certain de votre choix ?')"><i class="material-icons color-red"></i></a>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
    <nav aria-label="Partie Pagination" class="m-4">
      <ul class="pagination">
        <?php
        if ($page > 0) {
          $precedent = $page - 1;
          echo "<li class=\"page-item\"><a class=\"page-link\" href=\"?user=table&page=$precedent\" aria-label=\"Previous\"><span aria-hidden=\"true\">&laquo;</span><span class=\"sr-only\">Previous</span></a></li>";
        } else {
          $page = 0;
        }
        $i = 0;
        $j = 1;
        if ($nb_total > $limit) {
          while ($i < ($nb_total / $limit)) {
            if ($i != $page) {
              echo "<li class=\"page-item\"><a class=\"page-link\" href=\"?user=table&page=$i\">$j</a></li>";
            } else {
              echo "<li class=\"page-item active\"><a class=\"page-link\" href=\"#\">$j<span class=\"sr-only\">(current)</span></a></li>";
            }
            $i++;
            $j++;
          }
        }
        if ($debut + $limit < $nb_total) {
          $suivant = $page + 1;
          echo "<li class=\"page-item\"><a class=\"page-link\" href=\"?user=table&page=$suivant\" aria-label=\"Next\"><span aria-hidden=\"true\">&raquo;</span><span class=\"sr-only\">Next</span></a></li>";
        }
        echo "</ul></nav>";
        ?>
  </div>


  <div class="col-12 d-resp-xs d-resp-norm pt-3 text-center">
    <table class="my-3 with-resp-97 table-striped user">
      <?php $z = $page * $limit; ?>
      <?php foreach ($res as $v) : ?>
        <?php
        $created = date("d-m-Y", strtotime($v->created_at));
        ?>

        <tr>
          <th>Id</th>
          <td class="text-center b-top"><?= $v->id; ?></td>
        </tr>
        <tr>
          <th>Email</th>
          <td class="text-center"><?= $v->email; ?></td>
        </tr>
        <tr>
          <th>Avatar</th>
          <td class="text-center"><img src="<?= $v->avatar; ?>" class="w-70p"></td>
        </tr>
        <tr>
          <th>Role</th>
          <td class="text-center<?= $v->role == 'admin' ? ' text-danger' : ''; ?>">
            <?= $v->role; ?>
          </td>
        </tr>
        <tr>
          <th>Prenom</th>
          <td class="text-center"><?= $v->firstname; ?></td>
        </tr>
        <tr>
          <th>Nom</th>
          <td class="text-center"><?= $v->lastname; ?></td>
        </tr>
        <tr>
          <th>Nb de POST</th>
          <td class="text-center">
            <?php
            foreach ($res_user_nb_posts as $v1) {
              $id = $v1->user_id;
              $nb_posts = $v1->nb_posts;
              if ($v->id == $id) {
                echo $nb_posts;
              }
            }
            ?>
          </td>
        </tr>
        <tr>
          <th>Nb de COM</th>
          <td class="text-center">
            <?php
            foreach ($res_user_nb_coms as $v2) {
              $id = $v2->user_id;
              $nb_coms = $v2->nb_coms;
              if ($v->id == $id) {
                echo $nb_coms;
              }
            }
            ?>
          </td>
        </tr>
        <tr>
          <th>Date membre</th>
          <td class="text-center"><?= $created; ?></td>
        </tr>
        <tr>
          <th>Actions</th>
          <td class="text-center">
            <a href="../user_profil.php?&id=<?= $v->id ?>" class="view px-3" title="view"><i class="material-icons color-green va-middle">visibility</i></a>
            <a href="?user=edit&id=<?= $v->id ?>" class="edit px-3" title="Edit"><i class="material-icons color-blue va-middle"></i></a>
            <a href="?user=delete&id=<?= $v->id ?>" class="delete px-3" title="Delete" onclick="return confirm('Etes-vous certain de votre choix ?')"><i class="material-icons color-red va-middle"></i></a>
          </td>
        </tr>
        <tr>
          <td colspan="2" class="border-dark-user-top"></td>
        </tr>
        <tr>
          <td colspan="2" class="border-dark-user-bot"></td>
        </tr>

      <?php endforeach ?>
    </table>

    <nav aria-label="Partie Pagination" class="m-4">
      <ul class="pagination">
        <?php
        if ($page > 0) {
          $precedent = $page - 1;
          echo "<li class=\"page-item\"><a class=\"page-link\" href=\"?user=table&page=$precedent\" aria-label=\"Previous\"><span aria-hidden=\"true\">&laquo;</span><span class=\"sr-only\">Previous</span></a></li>";
        } else {
          $page = 0;
        }
        $i = 0;
        $j = 1;
        if ($nb_total > $limit) {
          while ($i < ($nb_total / $limit)) {
            if ($i != $page) {
              echo "<li class=\"page-item\"><a class=\"page-link\" href=\"?user=table&page=$i\">$j</a></li>";
            } else {
              echo "<li class=\"page-item active\"><a class=\"page-link\" href=\"#\">$j<span class=\"sr-only\">(current)</span></a></li>";
            }
            $i++;
            $j++;
          }
        }
        if ($debut + $limit < $nb_total) {
          $suivant = $page + 1;
          echo "<li class=\"page-item\"><a class=\"page-link\" href=\"?user=table&page=$suivant\" aria-label=\"Next\"><span aria-hidden=\"true\">&raquo;</span><span class=\"sr-only\">Next</span></a></li>";
        }
        echo "</ul></nav>";
        ?>
  </div>

<?php endif; ?>
<?php 'require footer_admin.php'; ?>