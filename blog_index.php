<?php session_start();
    require 'header.php';
    require 'toolbox.php';
?>


<div class="container-fluid mb-0">
    <div class="container">
        <div class="row nav-pills nav-fill m-auto">
            <div class="col-3 nav-item padR-5">
                <a href="blog_index.php" class="nav-link fz-0-8rem padR-pills hov 
                <?php
                    if (isset($_GET['category']) && $_GET['category'] == 'all' || empty($_GET['category'])) {
                        echo 'active link-active_all';
                    }
                ?>
                "><b>All</b></a>
                </div>
                <div class="col-3 nav-item padR-5">
                    <a href="blog_index.php?category=deep" class="nav-link fz-0-8rem padR-pills hov
                    <?php
                    if (isset($_GET['category']) && $_GET['category'] == 'deep') {
                        echo 'active link-active';
                    }
                ?>                        
                    ">Deep</a>
                </div>
                <div class="col-3 nav-item padR-5">
                    <a href="blog_index.php?category=minimal" class="nav-link fz-0-8rem padR-pills hov
                    <?php
                    if (isset($_GET['category']) && $_GET['category'] == 'minimal') {
                        echo 'active link-active';
                    }
                    ?>                        
                    ">Minimal</a>
                </div>
                <div class="col-3 nav-item padR-5">
                    <a href="blog_index.php?category=hard" class="nav-link fz-0-8rem padR-pills hov
                    <?php
                    if (isset($_GET['category']) && $_GET['category'] == 'hard') {
                        echo 'active link-active';
                    }
                    ?>
                    ">Hard</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-0">
        <div class="row">
            <!-- ICI COMMENCE NOTRE PHP -->
            <div class="col-md-12 mt-3">
                <h4 class="text-center mt-3 mb-5">Retrouvez ici les dernières nouveautés de la catégorie : <br>
                <span class="text-center font-weight-bold text-category">
                    <?php 
                        if (isset($_GET["category"]) && !empty($_GET['category'])){
                            echo ucfirst($_GET['category']);
                        } else {
                            echo 'Tous les genres';
                        }
                    ?>
                    </span>
                </h4>
            </div>
        </div>
    </div>
    <div class="container">                    
        <?php require 'app/bdd.php'; ?>
        <?php
            if (isset($_GET) && !empty($_GET['category'])){
                $page = 1;
                if (!empty($_GET['page'])) {
                    $page = $_GET['page'];
                }
                // Nombre de résultats par page
                if(isset($_COOKIE['largeur']) && $_COOKIE['largeur'] != 0){
                    $l = $_COOKIE['largeur'];
                    if ($l<992) {
                        $items_per_page = 2;
                    }else{
                        $items_per_page =3;
                    }   
                }else{
                    $items_per_page = 3;
                }
                
                $offset = ($page - 1) * $items_per_page;
                
                $sql = "SELECT * FROM blog_posts WHERE category = :category ORDER BY id DESC LIMIT :offset, :items_per_page";
                $req = $dbh->prepare($sql);
                $req->bindValue(":offset", $offset, PDO::PARAM_INT);
                $req->bindValue(":items_per_page", $items_per_page, PDO::PARAM_INT);
                $req->bindValue(':category', $_GET['category'], PDO::PARAM_STR);
                $req->execute();
                $resultat = $req->fetchAll(PDO::FETCH_OBJ);
                
                $count = $dbh->prepare("SELECT COUNT(*) FROM blog_posts WHERE category = :category");
                $count->bindValue(':category', $_GET['category'], PDO::PARAM_STR);
                $count->execute();
                $nbr_of_page = $count->fetch();

            }else{
                $page = 1;
                if (!empty($_GET['page'])) {
                    $page = $_GET['page'];
                }
                // Nombre de résultats par page
                if(isset($_COOKIE['largeur']) && $_COOKIE['largeur'] != 0){
                    $l = $_COOKIE['largeur'];
                    if ($l> 574 && $l<992) {
                        $items_per_page = 4;
                    }else if ($l>= 992) {
                        $items_per_page = 3;
                    }else{
                        $items_per_page = 2;
                    }   
                }else{
                    $items_per_page = 3;
                }
                $offset = ($page - 1) * $items_per_page;
                
                $sql = "SELECT * FROM blog_posts ORDER BY id DESC LIMIT :offset, :items_per_page";
                $req = $dbh->prepare($sql);
                $req->bindValue(":offset", $offset, PDO::PARAM_INT);
                $req->bindValue(":items_per_page", $items_per_page, PDO::PARAM_INT);
                $req->execute();
                $resultat = $req->fetchAll(PDO::FETCH_OBJ);
                
                $count = $dbh->prepare("SELECT COUNT(*) FROM blog_posts");
                $count->execute();
                $nbr_of_page = $count->fetch();        
            }                                                
        ?> 
        <div class="row">
            <?php foreach ($resultat as $v) : ?>
            <?php $dateFr=date("d/m/Y"." à "."H:i:s", strtotime($v->created_at)); ?>
            <div class="col-lg-4 col-sm-6 pb-4">
                <div class="card h-100">
                    <div class="text-center">
                        <a href="article.php?id=<?= $v->id; ?>"><img class="card-img-top w-50 pt-3" src="images/miniatures/<?= $v->image; ?>" alt="<?= $v->title; ?>"></a>
                    </div>
                    <div class="card-body btn-haut">
                        <span class="text-muted fz-50 my-0 py-0"><?= "le ".$dateFr; ?></span>
                        <h5 class="card-title mpt-0">
                            <a href="article.php?id=<?= $v->id; ?>" class="text-success my-0"><?= $v->title; ?></a>                                            
                        </h5>
                        <span>Catégorie : </span><span class="color-red-cay"><?= ucfirst($v->category); ?></span>
                        <p class="card-text mt-3"><?= $v->description; ?></p>
                        <br>
                        <div class="card-footer bg-white border-0 mp0">
                            <a href="article.php?id=<?= $v->id; ?>" class="btn btn-text-info btn-sm btn-bas" role="button" aria-pressed="true">Plus d'informations</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    
        <ul class="pagination justify-content-center">                        
            <?php for ($i = 1; $i <= ceil($nbr_of_page[0] / $items_per_page); $i++): ;?>                        
                <li class="page-item">
                    <a class="page-link text-dark" href="?page=
                    <?php
                    if (isset($_GET['category']) && !empty($_GET['category'])){
                        echo $i.'&category='.$_GET['category'];
                    }else{
                        echo $i;
                    }
                    ?>
                    "> <?=$i;?> </a>
                </li>
            <?php endfor;?>
        </ul>
    </div>
</main>
<?php require 'footer.php'; ?>