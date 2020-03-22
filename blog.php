<!doctype html>
<html lang="en">

<head>
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
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans|Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet " href="album.css">
    <link rel="stylesheet" href="css/style.min.css">


    <!-- Custom styles for this template -->
</head>

<body>
    <header>

        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="#">Actus Techno EP</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarNavDropdown">
                <ul class="navbar-nav justify-content-center">
                    <li class="nav-item">
                        <a class="nav-link pr-5" href="index.php">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pr-5" href="inscription.php">Inscription</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link pr-5" href="contact.php">Contact</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle pr-5" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Catégories
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="blog_index.php?category=deep">Deep</a>
                            <a class="dropdown-item" href="blog_index.php?category=minimal">Minimal</a>
                            <a class="dropdown-item" href="blog_index.php?category=hard">Hard</a>
                        </div>
                    </li>
            </div>
            <?php if (isset($_SESSION['log_firstname']) && isset($_SESSION['log_lastname'])) : ?>
                <li class="nav-item dropdown d-inline">
                    <span class="text-white">Bonjour</span>
                    <a class="nav-link dropdown-toggle d-inline " href="#" id="disconnect_user" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?= $_SESSION['log_firstname'] . ' ' . $_SESSION['log_lastname']; ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="disconnect_user">
                        <a class="dropdown-item" href="app/disconnect.php">DISCONNECT</a>
                    </div>
                </li>
            <?php endif; ?>
            </ul>

        </nav>
    </header>

    <main role="main">


        <div class="container-fluid text-center bg-dark-navy text-white py-4">
            <h1 class="display-4">Actualités Techno</h1>
            <p class="lead">Présentation des sorties EP - <b>TECHNO ONLY</b></p>
        </div>
        <?php if (isset($_SESSION['errors']) || isset($_SESSION['success'])) : ?>
            <?php $message = isset($_SESSION['errors']) ? $_SESSION['errors'] : (isset($_SESSION['success']) ? $_SESSION['success'] : ''); ?>
            <?php $color = isset($_SESSION['errors']) ? 'danger' : (isset($_SESSION['success']) ? 'success' : ''); ?>
            <div class="alert my-0 center alert-<?= $color; ?>">
                <p><?= $message; ?></p>
            </div>
        <?php
            // Je supprime toute les sessions errors et success
            unset($_SESSION['errors']);
            unset($_SESSION['success']);
        endif;
        ?>

        <?php session_start();
        require 'header.php';
        require 'toolbox.php';
        require 'app/can_connect_user.php';
        ?>


        <div class="container-fluid head-bar mb-0">
            <div class="container">
                <div class="row">
                    <div class="col-3">
                        <a href="page_web?" class="btn-sm btn-dark">All genres</a>
                    </div>
                    <div class="col-3">
                        <a href="app/disconnect.php" class="btn-sm btn-dark">Deep</a>
                    </div>
                    <div class="col-3">
                        <a href="app/disconnect.php" class="btn-sm btn-dark">Minimal</a>
                    </div>
                    <div class="col-3">
                        <a href="app/disconnect.php" class="btn-sm btn-dark">Hard</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container mt-0">
            <div class="row">

                <!-- ICI COMMENCE NOTRE PHP -->
                <div class="col-md-12 mt-3">
                    <h4>Articles du blog</h4>
                    <h5>Retrouvez ici les derniers articles du blog</h5>
                    <div class="container">

                        <!-- Page Heading -->
                        <h1 class="my-4">Page Heading
                            <small>Secondary Text</small>
                        </h1>

                        <div class="row">
                            <?php require 'app/bdd.php'; ?>
                            <?php $articles = $dbh->prepare('SELECT * FROM blog_posts');
                            $articles->execute();
                            $resultat = $articles->fetchAll(PDO::FETCH_OBJ);
                            ?>
                            <?php foreach ($resultat as $v) : ?>
                                <div class="col-lg-4 col-sm-6 mb-4">
                                    <div class="card h-100">

                                        <a href="#"><img class="card-img-top" src="images/miniatures/<?= $v->image; ?>" alt="<?= $v->title; ?>"></a>
                                        <div class="card-body">
                                            <p class="card-text">
                                                <h4 class="card-title">
                                                    <a href="#"><?= $v->title; ?></a>
                                                </h4>
                                                <p class="card-text"><?= $v->description; ?></p>
                                                <small class="text-muted"><?= $v->created_at; ?></small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>




                            <!-- /.row -->

                            <!-- Pagination -->
                            <ul class="pagination justify-content-center">
                                <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">1</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">2</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">3</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </li>
                            </ul>

                            <!-- /.container --
      </div>
        <!-- <div class="col-md-3 bg-secondary text-center mt-3 py-3">
            <br>
            <span class="text-white h5">Bienvenue<br></span>
            <span class="text-white h4"><?= $_SESSION['log_firstname'] . ' ' . $_SESSION['log_lastname']; ?></span>
            <ul class="list-group pt-4">
  <a href="blog.php?category=deep" class="list-group-item bg-dark links_perso">DEEP</a>
  <a href="blog.php?category=minimal" class="list-group-item bg-dark links_perso">MINIMAL</a>
  <a href="blog.php?category=hard" class="list-group-item bg-dark links_perso">HARD</a>
  <a href="blog.php" class="list-group-item bg-dark links_perso">All categories</a>
</ul>


      </div> -->
                            <!-- </div>
  </div> -->


    </main>

    <footer>
        <div class="container">

        </div>
    </footer>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>

</html>