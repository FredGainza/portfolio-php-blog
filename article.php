<?php
session_start();
require 'header.php';
require 'toolbox.php';
if (isset($_GET) && !empty($_GET)) {
  require 'app/bdd.php';

  $select_com = $dbh->prepare('SELECT commentaire, DATE_FORMAT(created_at, \'%d/%m/%Y à %Hh%imin%ss\') as dateCre  FROM blog_comments WHERE post_id = :post_id ORDER BY id DESC');
  $select_com->bindValue(':post_id', $_GET['id'], PDO::PARAM_INT);
  $select_com->execute();
  $res_select_com = $select_com->fetchAll(PDO::FETCH_OBJ);

  $selecta = $dbh->prepare('SELECT * FROM blog_posts WHERE id = :id');
  $selecta->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
  $selecta->execute();

  $resultat = $selecta->fetch(PDO::FETCH_OBJ);
  $dateFr = date("d/m/Y" . " à " . "H:i:s", strtotime($resultat->created_at));

  $auteur = $dbh->prepare('SELECT firstname, lastname FROM users WHERE id = :id');
  $auteur->bindValue(':id', $resultat->user_id, PDO::PARAM_INT);
  $auteur->execute();

  $res = $auteur->fetch(PDO::FETCH_OBJ);

  $aut_comment = $dbh->prepare('SELECT * FROM blog_comments LEFT JOIN users ON users.id = blog_comments.user_id WHERE blog_comments.post_id = :post_id ORDER BY blog_comments.id DESC');
  $aut_comment->bindValue(':post_id', $_GET['id'], PDO::PARAM_INT);
  $aut_comment->execute();

  $res_com_aut = $aut_comment->fetchAll(PDO::FETCH_OBJ);

  $quote = $dbh->prepare('SELECT * FROM citations');
  $quote->execute();
  $res_quote = $quote->fetchAll(PDO::FETCH_OBJ);
  // var_dump($res_quote);exit;
}
?>


<div class="container">
  <button class="retour mt-1" onClick='javascript:history.back();'><i class="fa fa-angle-double-left mr-2" aria-hidden="true"></i>Précédent</button>
  <div class="row">

    <!-- Blog Entries Column -->
    <div class="col-xl-8">

      <!-- Title -->
      <h3 class="mt-4 fz-150 color-green"><?= $resultat->title; ?></h3>

      <!-- Author -->
      <p>
        par
        <a href="#" class="color-red-cay"><?= $res->firstname . ' ' . $res->lastname; ?></a><br>
        Posté le <?= $dateFr; ?>
      </p>

      <hr>

      <!-- Date/Time -->
      <p>Classé en catégorie : <a href="blog_index.php?category=<?= $resultat->category; ?>" class="color-red-cay font-weight-bold"><?= ucfirst($resultat->category); ?></a></p>

      <hr>

      <!-- Preview Image -->
      <div class="text-center">
        <img class="card-img-top w-75" src="images/<?= $resultat->image; ?>" alt="<?= $resultat->title; ?>">
      </div>
      <hr>

      <!-- Post Content -->
      <p class="lead">
        <?= $resultat->content; ?>
      </p>

      <hr>

      <!-- Comments Form -->
      <div class="card my-4">
        <h5 class="card-header h5">Postez un commentaire</h5>
        <div class="card-body">


          <form action="app/traitement_commentaire.php" method="POST">
            <div class="form-group">
              <textarea class="form-control" rows="3" name="commentaire" id="commentaire"></textarea>
              <input type="hidden" name="user_id" value="<?= ($_SESSION['log_id']); ?>">
              <input type="hidden" name="post_id" value="<?= ($_GET['id']); ?>">
              <button type="submit" class="btn btn-link btn-sm mt-2 bg-red-clay text-white" name="com" value="commentaire_traitement">Envoyer</button>
            </div>
          </form>
        </div>

      </div>


      <!-- Single Comment -->
      <?php if (!empty($_SESSION['success'])) : ?>

        <div class="media mb-4">
          <img class="d-flex mr-3 rounded-circle" src="<?= $_SESSION['log_avatar']; ?>" alt="Avatar">
          <div class="media-body">
            <h5 class="mt-0"><?= $_SESSION['log_firstname'] . ' ' . $_SESSION['log_lastname']; ?></h5>

            <?= $_SESSION['comm'] ?>
          </div>
        </div>
      <?php endif; ?>

      <?php foreach ($res_com_aut as $v) : ?>
        <div class="card gedf-card mb-3">
          <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
              <div class="d-flex justify-content-between align-items-center">
                <div class="mr-2">
                  <img class="rounded-circle" width="45" src="<?= $v->avatar; ?>" alt="Avatar">
                </div>
                <div class="ml-2">
                  <?php
                  $role = $v->role;
                  if ($role === "admin") {
                    $role = '<span class="text-danger h7">(admin)</span>';
                  } else {
                    $role = '<span class="h7">(user)</span>';
                  }
                  ?>
                  <div class="h6 m-0"><?= $v->firstname . ' ' . $v->lastname . ' ' . $role; ?></div>
                </div>
              </div>
              <div>
                <div class="dropdown">
                  <button class="btn btn-link dropdown-toggle" type="button" id="gedf-drop1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-ellipsis-h color-red-cay"></i>
                  </button>
                  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="gedf-drop1">
                    <div class="h6 dropdown-header">Configuration</div>
                    <a class="dropdown-item" href="#">Save</a>
                    <a class="dropdown-item" href="#">Hide</a>
                    <a class="dropdown-item" href="#">Report</a>
                  </div>
                </div>
              </div>
            </div>

          </div>
          <div class="card-body">
            <div class="text-muted h7 mb-2"> <i class="far fa-clock mr-1"></i>posté le <?= $v->created_comment_at; ?></div>

            <p class="card-text">
              <?= $v->commentaire; ?>
            </p>
          </div>
          <div class="card-footer">
            <a href="#" class="card-link color-red-cay"><i class="fa fa-gittip"></i> Like</a>
            <a href="#" class="card-link color-red-cay"><i class="fa fa-comment"></i> Comment</a>
            <a href="#" class="card-link color-red-cay"><i class="fa fa-mail-forward"></i> Share</a>
          </div>
        </div>
      <?php endforeach; ?>

    </div>


    <!-- Sidebar Widgets Column -->
    <div class="col-xl-4">
      <hr class="displayR-xl">

      <!-- lecteur spotify -->
      <div class="card my-4">
        <h5 class="card-header">Lecteur spotify</h5>
        <div class="card-body">
          <?php if (isset($resultat->spotify_URI) && !empty($resultat->spotify_URI)) : ?>
            <iframe src="https://open.spotify.com/embed/album/<?= $resultat->spotify_URI; ?>" width="300" height="80" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>
          <?php else : ?>
            <h6 class="color-red-cay">Pas d'audio dispo sur Spotify</h6>
          <?php endif; ?>
        </div>
      </div>

      <!-- Search Widget -->
      <div class="card my-4">
        <h5 class="card-header">Rechercher</h5>
        <div class="card-body">
          <form action="result_search.php" method="POST">
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Je cherche..." name="search">
              <span class="input-group-btn my-auto">
                <button class="btn btn-dark" type="submit" name="sub_search" value="search_ok">Go!</button>
              </span>
            </div>
          </form>
        </div>
      </div>


      <!-- Categories Widget -->
      <div class="card my-3">
        <h5 class="card-header">Categories</h5>
        <div class="card-body">
          <div class="row">

            <div class="col-lg-6">
              <ul class="list-unstyled mpb0">
                <li>
                  <a href="blog_index.php?category=deep" class="color-red-cay ml-0">Deep Techno</a>
                </li>
                <li>
                  <a href="blog_index.php?category=minimal" class="color-red-cay text-center">Minimal Techno</a>
                </li>
                <li>
                  <a href="blog_index.php?category=hard" class="color-red-cay mr-0">Hard Techno</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- Side Widget -->
      <div class="card my-4">
        <h5 class="card-header">Citation</h5>
        <div class="card-body">

          <?php
          $nb_coms = count($res_quote);
          // echo($nb_posts);exit;

          $index = rand(0, $nb_coms - 1);
          // echo($index);exit;
          if ($nb_coms > 1) {
            $cit = $res_quote[rand(0, $nb_coms - 1)]->id;
          }

          if (isset($cit)) {
            $cit_ok = $dbh->prepare('SELECT * FROM citations WHERE id = :id');
            $cit_ok->bindValue(':id', $cit, PDO::PARAM_INT);
            $cit_ok->execute();

            $res_cit_ok = $cit_ok->fetch(PDO::FETCH_OBJ);
            $txt = $res_cit_ok->quote;
            $dj = $res_cit_ok->dj_name;
          }
          ?>
          <q><?= $txt; ?></q>
          <p class="text-right text-info mpb0"><?= $dj; ?></p>
        </div>
      </div>
      <!-- Side Widget -->
      <div class="card my-4">
        <h5 class="card-header">Suggestions</h5>
        <div class="card-body">
          <?php
          $cat = $resultat->category;
          $suggestion = $dbh->prepare('SELECT * FROM blog_posts WHERE category = :category');
          $suggestion->bindValue(':category', $cat, PDO::PARAM_STR);
          $suggestion->execute();

          $res_sugg = $suggestion->fetchAll(PDO::FETCH_OBJ);
          // dumpPre($res_sugg);exit;

          $nb_posts = count($res_sugg);
          // echo($nb_posts);exit;

          $index = rand(0, $nb_posts - 1);
          // echo($index);exit;
          if ($nb_posts > 1) {
            $post_sug = $res_sugg[rand(0, $nb_posts - 1)]->id;
            if ($post_sug == $_GET['id']) {
              $post_sug = $res_sugg[rand(0, $nb_posts - 1)]->id;
            }
          }

          // echo($post_sug);exit;
          if (isset($post_sug)) {
            $sug_ok = $dbh->prepare('SELECT * FROM blog_posts WHERE id = :id');
            $sug_ok->bindValue(':id', $post_sug, PDO::PARAM_INT);
            $sug_ok->execute();

            $res_sug_ok = $sug_ok->fetch(PDO::FETCH_OBJ);

            // echo($res_sugg[$post_sug]->image);exit;
          ?>

            <a href="article.php?id=<?= $res_sug_ok->id; ?>">
              <h6 class="color-red-cay"><?= $res_sug_ok->title . ' ' . $res_sug_ok->label; ?></h6>
            </a>
            <a href="article.php?id=<?= $res_sug_ok->id; ?>"><img class="card-img-top" src="<?= 'images/miniatures/' . $res_sug_ok->image; ?>" alt="Card image cap"></a>
          <?php
          } else {
          ?>
            <h6 class="color-red-cay">Pas d'autres releases dans cette catégorie</h6>
          <?php
          }
          ?>

        </div>
      </div>
      <!-- tweeter -->
      <div class="card my-4">
        <h5 class="card-header">Info events</h5>
        <div class="card-body">

          <a href="https://kopatik.wixsite.com/kopa/" target="_blank"><img src="images/kopa_logo_bg_transparent.png" alt="Logo KoPaTiK"></a>
        </div>
      </div>


    </div>


    <!-- /.row -->

  </div>
  <!-- /.container -->

  <footer>
    <div class="container">

    </div>
  </footer>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

  </body>

  </html>