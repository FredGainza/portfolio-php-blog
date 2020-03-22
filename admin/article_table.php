<?php
require '../app/bdd.php';

$select_aut_post = $dbh->prepare('SELECT * FROM users LEFT JOIN blog_posts ON blog_posts.user_id = users.id WHERE user_id IS NOT NULL');

$select_aut_post->execute();
$res_aut = $select_aut_post->fetchAll(PDO::FETCH_OBJ);

$page = isset($_GET['page']) ? $_GET['page'] : 0;
$_SESSION['page-art']= $page;
$debut = 0;

// if (!isset($limit)){
//   $limit=5;
//   $_SESSION['limit-art']= $limit;
// }
if (isset($_GET['nb_items']) && $_GET['nb_items']>0){
  $limit=intval($_GET['nb_items']);  
} else {
  if (isset($_SESSION['limit-art'])){
    $limit = intval($_SESSION['limit-art']);
  } else {
    $limit=5;
  }
}
$_SESSION['limit-art']= $limit;

if ($debut == 0) {
  $debut = 0;
} else {
  $debut = $page * $limit;
}
$nb_total = $select_aut_post->rowCount();
$limite = $dbh->prepare("SELECT * FROM blog_posts LEFT JOIN users ON blog_posts.user_id = users.id WHERE user_id limit $debut,$limit");
$limit_str = "LIMIT " . $page * $limit . ",$limit";

$result = $dbh->prepare("SELECT * FROM blog_posts LEFT JOIN users ON blog_posts.user_id = users.id WHERE user_id ORDER BY user_id ASC $limit_str");
$result->execute();
$res = $result->fetchAll(PDO::FETCH_OBJ);


$pagin = [];
for ($i = 1; $i <= floor($nb_total / $limit); $i++) {
  $pagin[] = $limit * $i;
}
if ($nb_total % $limit != 0) {
  $pagin[] = $nb_total;
}
?>

<?php if (isset($res_aut) || isset($_GET['affiche']) && $_GET['affiche'] == 'ok') : ?>
  <div class="row align-items-start width-resp justify-content-center mt-3 mb-1 mx-auto">

    <div class="col-auto ml-0 mr-auto mb-2">

      <form action="admin.php" method="GET">
          <div class="form-group form-select row w-100 align-items-start flex-nowrap">
                        
              <label class="col-auto col-form-label col-form-label-lg" for="nb_items">Eléments par page</label>
              <div class="col-auto pad-l-0 ">
                  <select name="nb_items" id="nb_items" class="form-control form-control-lg pad-r-0 custom-select">
                      <option value="3" <?= $limit==3 ? ' selected="selected"' :''; ?>>3</option>
                      <option value="5" <?= $limit==5 ? ' selected="selected"' :''; ?>>5</option>
                      <option value="10" <?= $limit==10 ? ' selected="selected"' :''; ?>>10</option>
                      <option value="20" <?= $limit==20 ? ' selected="selected"' :''; ?>>20</option>
                      <option value="25" <?= $limit==25 ? ' selected="selected"' :''; ?>>25</option>
                      <option value="50" <?= $limit==50 ? ' selected="selected"' :''; ?>>50</option>
                      <option value="100" <?= $limit==100 ? ' selected="selected"' :''; ?>>100</option>
                  </select>
              </div>
              <input type="hidden" name="article" value="table">

              <div class="form-group form-select w-25 pl-3 valid">
                  <button type="submit" class="btn btn-success btn-valid px-3 py-1 mb-2">Valider</button>
              </div>
          </div>
      </form>

    </div>
    <div class="col-auto ml-auto mr-2 mb-2 ajout">
        <button type="button" class="btn btn-ajout btn-dark px-2 ml-auto"><a href="?article=ajout" class="ajout" title="Ajout">Ajouter une Release</a></button>
    </div>
  </div>


  <div class="col-12 table-responsive d-resp-xl">
    <table class="my-3 w-100 table-striped">
      <thead>
        <tr>
          <th style="width: 5%;" class="pl-2">Id</th>
          <th style="width: 5%;">Image</th>
          <th style="width: 5%;">Catégorie</th>
          <th style="width: 5%;">Auteur</th>
          <th style="width: 5%;">Titre</th>
          <th style="width: 5%;">Date de sortie</th>
          <th style="width: 5%;">Label</th>
          <th style="width: 10%;">Description&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
          <th style="width: 20%;">Contenu&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
          <th style="width: 5%;">Date création</th>
          <th style="width: 25%;">Lecture</th>
          <th style="width: 5%;" class="text-center mx-1">Actions</th>
        </tr>
      </thead>

      <tbody class="fz-90p">
        <?php $z = $page * $limit; ?>
        <?php foreach ($res as $value) : ?>

          <?php
          $title = $dbh->prepare('SELECT id from blog_posts WHERE title = :title');
          $title->bindValue(':title', $value->title, PDO::PARAM_STR);
          $title->execute();        
          $post_id=$title->fetch(PDO::FETCH_OBJ);
          
          $date_sql = $value->date;
          $seulementDate = date("d-m-Y", strtotime($date_sql));
          $z++;
          $created = date("d-m-Y", strtotime($value->created_at));
          ?>

          <tr>
            <td class="pl-3"><?= $post_id->id; ?></td>
            <td><a href="../images/<?= $value->image; ?>" target="_blank"><img src="../images/miniatures/<?= $value->image; ?>"></a></td>
            <td><?= ucfirst($value->category); ?></td>
            <td>
              <?php if($value->id != '') : ?>
                <a href="/user_profil.php?&id=<?= $value->id; ?>" class="link-list">
                  <?= $value->firstname . ' ' . $value->lastname; ?>
                </a>
              <?php else : ?>
                <span class="text-danger">Utilisateur supprimé</span>
              <?php endif; ?> 
            </td>
            <td><?= $value->title; ?></td>
            <td><?= $seulementDate; ?></td>
            <td><?= $value->label; ?></td>
            <td><?= $value->description; ?></td>
            <td><?= tronquer_texte($value->content, 200); ?></td>
            <td><?= $created; ?></td>
            <td class=" text-center">
              <?php if (isset($value->spotify_URI) && !empty($value->spotify_URI)) : ?>
                <iframe src="https://open.spotify.com/embed/album/<?= $value->spotify_URI; ?>" width=300" height="80" frameborder="0" allowtransparency="true"></iframe>
              <?php else : ?>
                <h6 class="color-red-cay text-center">Pas d'audio dispo</h6>
              <?php endif; ?>
            </td>

            <td class="text-center">
              <a href="../article.php?id=<?= $value->id ?>" class="view pr-1" title="view"><i class="material-icons color-green-item mb-3">visibility</i></a>
              <a href="?article=edit&id=<?= $value->id ?>" class="edit pr-1" title="Edit"><i class="material-icons color-blue-item mb-3"></i></a>
              <a href="?article=delete&id=<?= $value->id ?>" class="delete" title="Delete" onclick="return confirm('Etes-vous certain de votre choix ?')"><i class="material-icons color-red-item"></i></a>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>


  <div class="col-12 d-resp-xs d-resp-norm mx-auto text-center">
    <table class="w95pct mx-auto border-dark">
      <?php $z = $page * $limit; ?>
      <?php foreach ($res as $value) : ?>
        <?php
        $date_sql = $value->date;
        $seulementDate = date("d-m-Y", strtotime($date_sql));
        $z++;
        $created = date("d-m-Y", strtotime($value->created_at));
        ?>

        <tr>
          <th colspan="2" class="text-white bg-perso my-5 text-center">ITEM <?= $z; ?></th>
        </tr>
        <tr>
          <td colspan="2" class="text-center">
            <a href="../article.php?id=<?= $value->id ?>" class="view px-4" title="view"><i class="material-icons color-green-item va-middle">visibility</i></a>
            <a href="?article=edit&id=<?= $value->id ?>" class="edit px-4" title="Edit"><i class="material-icons color-blue-item va-middle"></i></a>
            <a href="?article=delete&id=<?= $value->id ?>" class="delete px-4" title="Delete" onclick="return confirm('Etes-vous certain de votre choix ?')"><i class="material-icons color-red-item va-middle"></i></a>
          </td>
        </tr>
        <tr>
          <th colspan="1" class="b-bot b-top">Id</th>
          <td colspan="1" class="w60pct b-bot b-top text-center"><?= $value->id; ?></td>
        </tr>
        <tr>
          <th colspan="1" class="b-bot">Image</th>
          <td colspan="1" class="w60pct b-bot text-center"><a href="../images/<?= $value->image; ?>" target="_blank"><img src="../images/miniatures/<?= $value->image; ?>" class="w-100p p-1"></a></td>
        </tr>
        <tr>
          <th colspan="1" class="b-bot">Catégorie</th>
          <td colspan="1" class="w60pct b-bot text-center"><?= ucfirst($value->category); ?></td>
        </tr>
        <tr>
          <th colspan="1" class="b-bot">Auteur</th>
          <td colspan="1" class="w60pct b-bot text-center">
            <?php if($value->id != '') : ?>
              <a href="/user_profil.php?&id=<?= $value->id; ?>" class="link-list">
                <?= $value->firstname . ' ' . $value->lastname; ?>
              </a>
            <?php else : ?>
              <span class="text-danger">Utilisateur supprimé</span>
            <?php endif; ?>  
          </td>
        </tr>
        <tr>
          <th colspan="1" class="b-bot">Titre</th>
          <td colspan="1" class="w60pct b-bot text-center"><?= $value->title; ?></td>
        </tr>
        <tr>
          <th colspan="1" class="b-bot">Date de sortie</th>
          <td colspan="1" class="w60pct b-bot text-center"><?= $seulementDate; ?></td>
        </tr>
        <tr>
          <th colspan="1" class="b-bot">Label</th>
          <td colspan="1" class="w60pct b-bot text-center"><?= $value->label; ?></td>
        </tr>

        <tr>
          <th colspan="1" class="b-bot">Date création</th>
          <td colspan="1" class="w60pct b-bot text-center"><?= $created; ?></td>
        </tr>
        <tr>
          <th colspan="1" class="b-bot">Description</th>
          <td colspan="1" class="w60pct b-bot text-center"><?= $value->description; ?></td>
        </tr>
        <tr>
          <th colspan="2">Contenu</th>
        </tr>
        <tr>
          <td colspan="2"><?= tronquer_texte($value->content, 200); ?></td>
        </tr>

        <tr>
          <th colspan="2">Lecture</th>
        </tr>
        <tr>
          <td colspan="2" class="b-bot text-center">
            <?php if (isset($value->spotify_URI) && !empty($value->spotify_URI)) : ?>
              <iframe src="https://open.spotify.com/embed/album/<?= $value->spotify_URI; ?>" width=300" height="80" frameborder="0" allowtransparency="true"></iframe>
            <?php else : ?>
              <h6 class="color-red-cay my-auto">Pas d'audio dispo</h6>
            <?php endif; ?>
          </td>
        </tr>

        <?php for ($i = 0; $i < count($pagin); $i++) : ?>
          <?php if ($z != $pagin[$i]) : ?>
            <tr>
              <td class="border-0"></td>
            </tr>
            <tr>
              <td class="border-0"></td>
            </tr>
            <?php break; ?>
          <?php endif; ?>
        <?php endfor; ?>
      <?php endforeach ?>
    </table>
  </div>


  <nav aria-label="Partie Pagination">
    <ul class="pagination align-resp">
      <?php
      if ($page > 0) {
        $precedent = $page - 1;
        echo "<li class=\"page-item\"><a class=\"page-link\" href=\"?nb_items=$limit&article=table&page=$precedent\" aria-label=\"Previous\"><span aria-hidden=\"true\">&laquo;</span><span class=\"sr-only\">Previous</span></a></li>";
      } else {
        $page = 0;
      }
      $i = 0;
      $j = 1;
      if ($nb_total > $limit) {
        while ($i < ($nb_total / $limit)) {
          if ($i != $page) {
            echo "<li class=\"page-item\"><a class=\"page-link\" href=\"?nb_items=$limit&article=table&page=$i\">$j</a></li>";
          } else {
            echo "<li class=\"page-item active\"><a class=\"page-link\" href=\"#\">$j<span class=\"sr-only\">(current)</span></a></li>";
          }
          $i++;
          $j++;
        }
      }
      if ($debut + $limit < $nb_total) {
        $suivant = $page + 1;
        echo "<li class=\"page-item\"><a class=\"page-link\" href=\"?nb_items=$limit&article=table&page=$suivant\" aria-label=\"Next\"><span aria-hidden=\"true\">&raquo;</span><span class=\"sr-only\">Next</span></a></li>";
      }
      echo "</ul></nav>";
      ?>
    <?php endif; ?>

    <?php 'require footer_admin.php'; ?>