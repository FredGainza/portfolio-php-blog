<?php
session_start();
require 'app/bdd.php';
require 'toolbox.php';
require 'header.php';


if (isset($_POST['search']) && $_POST['sub_search'] === 'search_ok') {
    if (isset($_POST['search']) && !empty($_POST['search'])) {
        $search = $_POST['search'];
        $search = strtolower($search);

        $select_title = $dbh->prepare('SELECT * FROM blog_posts WHERE title like :search');
        $select_title->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
        $select_title->execute();

        $res_title = $select_title->fetchAll(PDO::FETCH_OBJ);
        $nb_res_title = count($res_title);

        $select_label = $dbh->prepare('SELECT * FROM blog_posts WHERE label like :search');
        $select_label->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
        $select_label->execute();

        $res_label = $select_label->fetchAll(PDO::FETCH_OBJ);
        $nb_res_label = count($res_label);

        $select_description = $dbh->prepare('SELECT * FROM blog_posts WHERE description like :search');
        $select_description->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
        $select_description->execute();

        $res_description = $select_description->fetchAll(PDO::FETCH_OBJ);
        $nb_res_description = count($res_description);

        $select_content = $dbh->prepare('SELECT * FROM blog_posts WHERE content like :search');
        $select_content->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
        $select_content->execute();

        $res_content = $select_content->fetchAll(PDO::FETCH_OBJ);
        $nb_res_content = count($res_content);

        $res_total =  $res_title + $res_label + $res_description + $res_content;
        $res_total = array_unique($res_total, SORT_REGULAR);
        // dumpPre($res_total);exit;
        $nb_res_total = count($res_total);
    } else {
        $_SESSION['errors'] = 'Vous n\'avez rien renseigné dans le champ de recherche';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}
?>

<div class="container">
    <div class="row nav-pills nav-fill m-auto">
        <div class="col-3 nav-item">
            <a href="blog_index.php" class="nav-link hov 
        <?php
        if (isset($_GET['category']) && $_GET['category'] == 'all' || empty($_GET['category'])) {
            echo 'active link-active_all';
        }
        ?>
        ">All genres</a>
        </div>
        <div class="col-3 nav-item">
            <a href="blog_index.php?category=deep" class="nav-link hov
            <?php
            if (isset($_GET['category']) && $_GET['category'] == 'deep') {
                echo 'active link-active';
            }
            ?>                        
            ">Deep</a>
        </div>
        <div class="col-3 nav-item">
            <a href="blog_index.php?category=minimal" class="nav-link hov
            <?php
            if (isset($_GET['category']) && $_GET['category'] == 'minimal') {
                echo 'active link-active';
            }
            ?>                        
            ">Minimal</a>
        </div>
        <div class="col-3 nav-item">
            <a href="blog_index.php?category=hard" class="nav-link hov
            <?php
            if (isset($_GET['category']) && $_GET['category'] == 'hard') {
                echo 'active link-active';
            }
            ?>
            ">Hard</a>
        </div>
    </div>

    <div class="row pt-5">
        <div class="col-12 col-sm-12 ml-3">
            <?php if (isset($search) && !empty($search)) : ?>
                <h5>Résultat de la recherche de <b>"<?= $search; ?>"</b></h5>
                <hr>


                <?php if ($nb_res_total > 0) : ?>
                    <table class="my-3 w-100 table-striped">
                        <?php foreach ($res_total as $v) : ?>
                            <tr>
                                <td><a href="article.php?id=<?= $v->id ?>" class="text-dark"><b><?= $v->title; ?></b></a></td>
                                <td><?= $v->category; ?></td>
                                <td><?= $v->label; ?></td>
                                <td><?= date("d-m-Y", strtotime($v->date)); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else : ?>
                    <p>Aucune release ne correspond à votre recherche...</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require 'footer.php'; ?>