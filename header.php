<!doctype html>
<html lang="fr">

<head>

  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-160287169-1"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-160287169-1');
  </script>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
  <meta name="generator" content="Jekyll v3.8.5">
  <title>Blog sorties EP Techno</title>

  <link rel="canonical" href="https://getbootstrap.com/docs/4.3/examples/album/">

  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css">
  <link type="image/png" rel="icon" href="images/favicon.png" />
  <link href="https://fonts.googleapis.com/css?family=Noto+Sans|Roboto&display=swap" rel="stylesheet">
  <link rel="stylesheet " href="css/album.css">
  <link rel="stylesheet" href="css/style.css">


  <!-- Custom styles for this template -->
</head>

<body>
  <header>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <button class="navbar-toggler " type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <a class="navbar-brand mr-2" href="https://techno-blog.fgainza.fr/">Actus Techno EP</a>


      <div class="displayRR">
        <?php if (isset($_SESSION['log_firstname']) && isset($_SESSION['log_lastname'])) : ?>
          <div class="nav-item dropdown d-inline">
            <span class="color-grey-navbar mr-1">Bonjour </span><span class="navbar-text text-white"><a href="user_profil.php"><?= $_SESSION['log_firstname'] . ' ' . $_SESSION['log_lastname']; ?></a></span>
            <a class="nav-link d-inline" href="app/disconnect.php"><i class="fas fa-times text-danger"></i></a>
          </div>
        <?php endif; ?>

        <?php if (!isset($_SESSION['log_firstname']) && !isset($_SESSION['log_lastname'])) : ?>
          <div class="nav-item dropdown d-inline">
            <span class="color-grey-navbar mr-1">Bonjour </span><span class="navbar-text text-white">Invité(e)</span>
            <a class="nav-link d-inline" href="inscription.php"><i class="fas fa-user-times text-info"></i></a>
          </div>
        <?php endif; ?>
      </div>


      <div class="collapse navbar-collapse justify-content-center" id="navbarNavDropdown">

        <ul class="navbar-nav justify-content-center">
          <li class="nav-item">
            <a class="nav-link pr-5" href="index.php">Connexion</a>
          </li>
          <li class="nav-item">
            <a class="nav-link pr-5" href="inscription.php">Inscription</a>
          </li>
          <li class="nav-item">
            <a class="nav-link pr-5" href="contact.php">Contact</a>
          </li>
          <li class="nav-item">
            <a class="nav-link pr-5" href="blog_index.php">BLOG</a>
          </li>
          <?php if (isset($_SESSION['admin_access'])) : ?>
            <li class="nav-item">
              <a class="nav-link pr-5" href="admin/admin.php">Admin</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>

      <div class="displayR">
        <?php if (isset($_SESSION['log_firstname']) && isset($_SESSION['log_lastname'])) : ?>
          <div class="nav-item dropdown d-inline">
            <span class="color-grey-navbar mr-1">Bonjour </span><span class="navbar-text text-white"><a href="user_profil.php"><?= $_SESSION['log_firstname'] . ' ' . $_SESSION['log_lastname']; ?></a></span>
            <a class="nav-link d-inline" href="app/disconnect.php"><i class="fas fa-times text-danger"></i></a>
          </div>
        <?php endif; ?>

        <?php if (!isset($_SESSION['log_firstname']) && !isset($_SESSION['log_lastname'])) : ?>
          <div class="nav-item dropdown d-inline">
            <span class="color-grey-navbar mr-1">Bonjour </span><span class="navbar-text text-white">Invité(e)</span>
            <a class="nav-link d-inline" href="inscription.php"><i class="fas fa-user-times text-info"></i></a>
          </div>
        <?php endif; ?>
      </div>


    </nav>
  </header>

  <main role="main">
    <div class="displayR">
      <div class="container-fluid text-center bg-dark-navy text-white py-4">
        <h1 class="display-4">Actualités Techno</h1>
        <p class="lead">Présentation des sorties EP - <b>TECHNO ONLY</b></p>
      </div>
    </div>
    <?php if (isset($_SESSION['errors']) || isset($_SESSION['success'])) : ?>
      <?php $message = isset($_SESSION['errors']) ? $_SESSION['errors'] : (isset($_SESSION['success']) ? $_SESSION['success'] : ''); ?>
      <?php $color = isset($_SESSION['errors']) ? 'danger' : (isset($_SESSION['success']) ? 'success' : ''); ?>
      <div id="alert" class="col-md-6 offset-md-3 alert my-2 py-2 w-80p center alert-dismissible alert-<?= $color; ?> fade show" role="alert">
        <button type="button" class="close" id="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <p><?= $message; ?></p>
      </div>
    <?php
      // Je supprime toute les sessions errors et success
      unset($_SESSION['errors']);
      unset($_SESSION['success']);
    endif;
    ?>
    <?php if (isset($_GET['disconnect']) && $_GET['disconnect'] === 'ok') : ?>
      <?php $message = "Vous avez été déconnecté. A bientôt !!"; ?>
      <div class="alert my-0 center alert-success">
        <p><?= $message; ?></p>
      </div>
      <?php header("Refresh: 3;URL=https://techno-blog.fgainza.fr/"); ?>
    <?php endif; ?>