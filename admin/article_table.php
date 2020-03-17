<?php
require '../app/bdd.php';

$select_aut_post = $dbh->prepare('SELECT * FROM users LEFT JOIN blog_posts ON blog_posts.user_id = users.id WHERE user_id IS NOT NULL');

$select_aut_post->execute();
$res_aut = $select_aut_post->fetchAll(PDO::FETCH_OBJ);

?>

<?php if (isset($res_aut) || isset($_GET['affiche']) && $_GET['affiche'] == 'ok') : ?>

  <div class="col-12 pt-3">
    <div class="text-right">
      <button type="button" class="btn btn-dark mb-3 ml-auto"><a href="?article=ajout" class="ajout" title="Ajout">Ajouter une release</a></button>
    </div>

    <table class="my-3 w-100 table-striped d-resp-xl">
      <thead>
        <tr>
          <th style="width: 5%;" class="pl-2">Id</th>
          <th style="width: 5%;">Image</th>
          <th style="width: 5%;">Catégorie</th>
          <th style="width: 5%;">Auteur</th>
          <th style="width: 5%;">Titre</th>
          <th style="width: 5%;">Date de sortie</th>
          <th style="width: 5%;">Label</th>
          <th style="width: 10%;">Description</th>
          <th style="width: 20%;">Contenu</th>
          <th style="width: 5%;">Date création</th>
          <th style="width: 25%;">Lecture</th>
          <th style="width: 5%;" class="text-center mx-1">Actions</th>
        </tr>
      </thead>

      <tbody class="fz-90p">
        <?php foreach ($res_aut as $value) : ?>

          <?php
          $date_sql = $value->date;
          $seulementDate = date("d-m-Y", strtotime($date_sql));

          $created = date("d-m-Y", strtotime($value->created_at));
          ?>

          <tr>
            <td class="pl-3"><?= $value->id; ?></td>
            <td><a href="../images/<?= $value->image; ?>" target="_blank"><img src="../images/miniatures/<?= $value->image; ?>"></a></td>
            <td><?= ucfirst($value->category); ?></td>
            <td><?= $value->firstname . ' ' . $value->lastname; ?></td>
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

    <table class="w90pct mx-auto d-resp-norm border-dark">
      <?php

      $page = isset($_GET['page']) ? $_GET['page'] : 0;
      $debut = '';
      $limit = 3;
      if ($debut == "") {
        $debut = 0;
      } else {
        $debut = $page * $limit;
      }
      $nb_total = $select_aut_post->rowCount();
      $limite = $dbh->prepare("SELECT * FROM users LEFT JOIN blog_posts ON blog_posts.user_id = users.id WHERE user_id IS NOT NULL limit $debut,$limit");
      $limit_str = "LIMIT " . $page * $limit . ",$limit";

      $result = $dbh->prepare("SELECT * FROM users LEFT JOIN blog_posts ON blog_posts.user_id = users.id WHERE user_id IS NOT NULL ORDER BY user_id ASC $limit_str");
      $result->execute();
      $res = $result->fetchAll(PDO::FETCH_OBJ);
      ?>
      <?php $z = $page * $limit; ?>
      <?php foreach ($res as $value) : ?>
        <?php

        $date_sql = $value->date;
        $seulementDate = date("d-m-Y", strtotime($date_sql));
        $z++;
        $created = date("d-m-Y", strtotime($value->created_at));
        ?>



        <tr>
          <th colspan="2" class="text-danger bg-perso2 my-5 text-center w-100">ITEM <?= $z; ?></th>
        </tr>
        <tr>
          <td colspan="2" class="text-center w-100">
            <a href="../article.php?id=<?= $value->id ?>" class="view px-4" title="view"><i class="material-icons color-green-item va-middle">visibility</i></a>
            <a href="?article=edit&id=<?= $value->id ?>" class="edit px-4" title="Edit"><i class="material-icons color-blue-item va-middle"></i></a>
            <a href="?article=delete&id=<?= $value->id ?>" class="delete px-4" title="Delete" onclick="return confirm('Etes-vous certain de votre choix ?')"><i class="material-icons color-red-item va-middle"></i></a>
          </td>
        </tr>
        <tr>
          <th colspan="1" class="b-bot-grey b-top-grey">Id</th>

          <td colspan="1" class="w60pct b-bot-grey b-top-grey text-center"><?= $value->id; ?></td>
        </tr>
        <tr>
          <th colspan="1" class="b-bot-grey">Image</th>

          <td colspan="1" class="w60pct b-bot-grey text-center"><a href="../images/<?= $value->image; ?>" target="_blank"><img src="../images/miniatures/<?= $value->image; ?>" class="w-100p p-1"></a></td>
        </tr>
        <tr>
          <th colspan="1" class="b-bot-grey">Catégorie</th>

          <td colspan="1" class="w60pct b-bot-grey text-center"><?= ucfirst($value->category); ?></td>
        </tr>
        <tr>
          <th colspan="1" class="b-bot-grey">Auteur</th>

          <td colspan="1" class="w60pct b-bot-grey text-center"><?= $value->firstname . ' ' . $value->lastname; ?></td>
        </tr>
        <tr>
          <th colspan="1" class="b-bot-grey">Titre</th>

          <td colspan="1" class="w60pct b-bot-grey text-center"><?= $value->title; ?></td>
        </tr>
        <tr>
          <th colspan="1" class="b-bot-grey">Date de sortie</th>

          <td colspan="1" class="w60pct b-bot-grey text-center"><?= $seulementDate; ?></td>
        </tr>
        <tr>
          <th colspan="1" class="b-bot-grey">Label</th>

          <td colspan="1" class="w60pct b-bot-grey text-center"><?= $value->label; ?></td>
        </tr>

        <tr>
          <th colspan="1" class="b-bot-grey">Date création</th>

          <td colspan="1" class="w60pct b-bot-grey text-center"><?= $created; ?></td>
        </tr>
        <th colspan="1" class="b-bot-grey">Description</th>

        <td colspan="1" class="w60pct b-bot-grey text-center"><?= $value->description; ?></td>
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


        <tr>
          <td class="border-0"></td>
        </tr>
        <tr>
          <td class="border-0"></td>
        </tr>
        <tr>
          <td class="border-0"></td>
        </tr>
        <tr></tr>
      <?php endforeach ?>

        
        <table class="w90pct mx-auto d-resp-xs border-dark">
          <?php
          $page = isset($_GET['page']) ? $_GET['page'] : 0;
          $debut = '';
          $limit = 3;
          if ($debut == "") {
            $debut = 0;
          } else {
            $debut = $page * $limit;
          }
          $nb_total = $select_aut_post->rowCount();
          $limite = $dbh->prepare("SELECT * FROM users LEFT JOIN blog_posts ON blog_posts.user_id = users.id WHERE user_id IS NOT NULL limit $debut,$limit");
          $limit_str = "LIMIT " . $page * $limit . ",$limit";

          $result = $dbh->prepare("SELECT * FROM users LEFT JOIN blog_posts ON blog_posts.user_id = users.id WHERE user_id IS NOT NULL ORDER BY user_id ASC $limit_str");
          $result->execute();
          $res = $result->fetchAll(PDO::FETCH_OBJ);
          ?>
          <?php $z = $page * $limit; ?>
          <?php foreach ($res as $value) : ?>
            <?php

            $date_sql = $value->date;
            $seulementDate = date("d-m-Y", strtotime($date_sql));
            $z++;
            $created = date("d-m-Y", strtotime($value->created_at));
            ?>

            <tr>
              <th class="text-danger bg-perso2 my-5 text-center">ITEM <?= $i; ?></th>
            </tr>
            <tr>
              <td class="text-center b-bot">
                <a href="../article.php?id=<?= $value->id ?>" class="view px-4" title="view"><i class="material-icons color-green-item va-middle">visibility</i></a>
                <a href="?article=edit&id=<?= $value->id ?>" class="edit px-4" title="Edit"><i class="material-icons color-blue-item va-middle"></i></a>
                <a href="?article=delete&id=<?= $value->id ?>" class="delete px-4" title="Delete" onclick="return confirm('Etes-vous certain de votre choix ?')"><i class="material-icons color-red-item va-middle"></i></a>
              </td>
            </tr>
            <tr>
              <th>Id</th>
            </tr>
            <tr>
              <td><?= $value->id; ?></td>
            </tr>
            <tr>
              <th>Image</th>
            </tr>
            <tr>
              <td><a href="../images/<?= $value->image; ?>" target="_blank"><img src="../images/miniatures/<?= $value->image; ?>" class="w-100p p-1"></a></td>
            </tr>
            <tr>
              <th>Catégorie</th>
            </tr>
            <tr>
              <td><?= ucfirst($value->category); ?></td>
            </tr>
            <tr>
              <th>Auteur</th>
            </tr>
            <tr>
              <td><?= $value->firstname . ' ' . $value->lastname; ?></td>
            </tr>
            <tr>
              <th>Titre</th>
            </tr>
            <tr>
              <td><?= $value->title; ?></td>
            </tr>
            <tr>
              <th>Date de sortie</th>
            </tr>
            <tr>
              <td><?= $seulementDate; ?></td>
            </tr>
            <tr>
              <th>Label</th>
            </tr>
            <tr>
              <td><?= $value->label; ?></td>
            </tr>
            <tr>
              <th style="width: 15%">Description</th>
            </tr>
            <tr>
              <td><?= $value->description; ?></td>
            </tr>
            <tr>
              <th style="width: 22%">Contenu</th>
            </tr>
            <tr>
              <td><?= tronquer_texte($value->content, 200); ?></td>
            </tr>
            <tr>
              <th>Date création</th>
            </tr>
            <tr>
              <td><?= $created; ?></td>
            </tr>
            <tr>
              <th>Lecture</th>
            </tr>
            <tr>
              <td>
                <?php if (isset($value->spotify_URI) && !empty($value->spotify_URI)) : ?>
                  <iframe src="https://open.spotify.com/embed/album/<?= $value->spotify_URI; ?>" width=300" height="80" frameborder="0" allowtransparency="true"></iframe>
                <?php else : ?>
                  <h6 class="color-red-cay my-auto">Pas d'audio dispo</h6>
                <?php endif; ?>
              </td>


            <tr>
              <td class="border-0"></td>
            </tr>
            <tr>
              <td class="border-0"></td>
            </tr>
            <tr>
              <td class="border-0"></td>
            </tr>
            <tr></tr>
          <?php endforeach ?>


        
        <nav aria-label="Partie Pagination" class="d-resp-norm d-resp-xs">
          <ul class="pagination">
            <?php
            if ($page > 0) {
              $precedent = $page - 1;
              echo "<li class=\"page-item\"><a class=\"page-link\" href=\"?article=table&page=$precedent\" aria-label=\"Previous\"><span aria-hidden=\"true\">&laquo;</span><span class=\"sr-only\">Previous</span></a></li>";
            } else {
              $page = 0;
            }
            $i = 0;
            $j = 1;
            if ($nb_total > $limit) {
              while ($i < ($nb_total / $limit)) {
                if ($i != $page) {
                  echo "<li class=\"page-item\"><a class=\"page-link\" href=\"?article=table&page=$i\">$j</a></li>";
                } else {
                  echo "<li class=\"page-item active\"><a class=\"page-link\" href=\"#\">$j<span class=\"sr-only\">(current)</span></a></li>";
                }
                $i++;
                $j++;
              }
            }
            if ($debut + $limit < $nb_total) {
              $suivant = $page + 1;
              echo "<li class=\"page-item\"><a class=\"page-link\" href=\"?article=table&page=$suivant\" aria-label=\"Next\"><span aria-hidden=\"true\">&raquo;</span><span class=\"sr-only\">Next</span></a></li>";
            }
            echo "</ul></nav>";
            ?>
</table>
          <?php endif; ?>

          <?php 'require footer_admin.php'; ?>