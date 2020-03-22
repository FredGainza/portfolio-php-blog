<?php
session_start();
require 'header.php';
require 'toolbox.php';
// dumpPre($_SESSION);exit;

if (isset($_SESSION['user_access']) || isset($_SESSION['admin_access'])) {
    require 'app/bdd.php';
    $id = !empty($_GET['id']) ? $_GET['id'] : $_SESSION['log_id'];
    $select = $dbh->prepare('SELECT * FROM users WHERE id = :id');
    $select->bindValue(':id', $id, PDO::PARAM_INT);
    $select->execute();

    $res_user = $select->fetch(PDO::FETCH_OBJ);

    $created = $res_user->created_at;
    $created = date("d-m-Y", strtotime($created));

    if ($res_user->role =="admin"){
        $select_post = $dbh->prepare('SELECT user_id, COUNT(user_id) as nb_posts FROM blog_posts LEFT JOIN users ON users.id = blog_posts.user_id WHERE user_id = :user_id');
        $select_post->bindValue(':user_id', $id, PDO::PARAM_INT);
        $select_post->execute();
        $res_admin_nb_posts = $select_post->fetch(PDO::FETCH_OBJ);
        $nb_admin_posts = $res_admin_nb_posts->nb_posts;

        if ($nb_admin_posts > 0){
            $select_admin_posts = $dbh->prepare('SELECT * FROM blog_posts WHERE user_id = :user_id');
            $select_admin_posts->bindValue(':user_id', $id, PDO::PARAM_INT);
            $select_admin_posts->execute();
            $res_admin_posts = $select_admin_posts->fetchAll(PDO::FETCH_OBJ);
            // dumpPre($res_admin_posts);
        }
    }

    $select_com = $dbh->prepare('SELECT user_id, COUNT(user_id) as nb_coms FROM blog_comments WHERE user_id = :user_id');
    $select_com->bindValue(':user_id', $id, PDO::PARAM_INT);
    $select_com->execute();
    $res_user_nb_coms = $select_com->fetch(PDO::FETCH_OBJ);
    $nb_user_coms = $res_user_nb_coms->nb_coms;

    if ($nb_user_coms > 0){
        $select_register_coms = $dbh->prepare('SELECT * FROM blog_comments WHERE user_id = :user_id');
        $select_register_coms->bindValue(':user_id', $id, PDO::PARAM_INT);
        $select_register_coms->execute();
        $res_register_coms = $select_register_coms->fetchAll(PDO::FETCH_OBJ);
       
        $select_post_com = $dbh->prepare('SELECT id, title FROM blog_posts');
        $select_post_com->execute();
        $res_post_com = $select_post_com->fetchAll(PDO::FETCH_OBJ);       
    }

} else {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-lg-4 pb-5">
            <!-- Account Sidebar-->
            <div class="author-card pb-3">
                <div class="author-card-cover" style="background-image: url(https://demo.createx.studio/createx-html/img/widgets/author/cover.jpg);"></div>
                <div class="author-card-profile">
                    <img src="<?= isset($_SESSION['lien_avatar']) && !empty($_SESSION['lien_avatar']) ? $_SESSION['lien_avatar'] : $res_user->avatar; ?>" class="<?= isset($_GET['id']) ? 'w-200p' : 'w-250p'; ?>">
                    
                </div>
                <div class="author-card-details mt-3 pl-4">
                    <h5 class="author-card-name color-red-cay"><b><?= $res_user->firstname . ' ' . $res_user->lastname; ?></b></h5><span class="author-card-position">Membre depuis le <?= $created; ?></span>
                </div>

                <form action="app/refresh_avatar.php" method="GET" class="pt-3">
                    <input type="hidden" name="id_user" value="<?= $_GET['id']; ?>">
                    <select name="type_avatar" id="type_avatar" class="ml-5<?= isset($_GET['id']) ? ' d-none' : ''; ?>">
                        <option value="avataaars">Avataaars</option>
                        <option value="bottts">Bottts</option>
                        <option value="gridy">Gridy</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="human">Human</option>
                        <option value="identicon">Identicon</option>
                        <option value="jdenticon">Jdenticon</option>
                    </select>

                    <button class="btn btn-dark<?= isset($_GET['id']) ? ' d-none' : ''; ?>" id="bouton" name="random">Random</button>
                    <input type="hidden" name="id_user" value="<?= $_GET['id']; ?>">
                </form>
            </div>
        </div>
        <!-- Profile Settings-->
        <div class="col-lg-8 pb-5">
            <form class="row" action="app/traitement_user_profil.php" method="POST">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="account-fn">Prénom</label>
                        <input class="form-control" type="text" id="account-fn" name="firstname" value="<?= $res_user->firstname; ?>" disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="account-ln">Nom</label>
                        <input class="form-control" type="text" id="account-ln" name="lastname" value="<?= $res_user->lastname; ?>" disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="account-email">E-mail</label>
                        <input class="form-control" type="email" id="account-email" name="email2" value="<?= $res_user->email; ?>" <?= isset($_GET['id']) ? 'disabled' : ''; ?>>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="account-phone">Role</label>
                        <input class="form-control" type="text" id="account-phone" name="role" value="<?= $res_user->role; ?>" disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="account-pass">Nouveau password</label>
                        <input class="form-control" type="password" name="password" id="account-pass" <?= isset($_GET['id']) ? 'disabled' : ''; ?>>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="account-confirm-pass">Confirmez le password</label>
                        <input class="form-control" type="password" name="password2" id="account-confirm-pass" <?= isset($_GET['id']) ? 'disabled' : ''; ?>>
                    </div>
                </div>
                <?php if(!isset($_GET['id']) || empty($_GET['id'])) : ?>
                <div class="col-12 mb-5">
                    <hr class="mt-2 mb-3">
                    <div class="d-flex flex-wrap justify-content-between align-items-center">
                        <div class="custom-control custom-checkbox d-block">
                            <input class="custom-control-input" type="checkbox" id="subscribe_me" checked="">
                            <label class="custom-control-label<?= isset($_GET['id']) ? ' d-none' : ''; ?>" for="subscribe_me">Souscrire à la newsletter</label>
                        </div>
                       
                        <input type="hidden" name="id_user" value="<?= $res_user->id; ?>">
                        <input type="hidden" name="post_avatar" value="<?= isset($_SESSION['lien_avatar']) && !empty($_SESSION['lien_avatar']) ? $_SESSION['lien_avatar'] : $res_user->avatar; ?>">
                        <?php $_SESSION['lien_avatar'] = ''; ?>
                        <button class="btn btn-style-1 btn-dark<?= isset($_GET['id']) ? ' d-none' : ''; ?>" type="submit" data-toast="" data-toast-position="topRight" data-toast-type="success" data-toast-icon="fe-icon-check-circle" data-toast-title="Success!" data-toast-message="Your profile updated successfuly.">Update Profile</button>
                    </div>
                </div> 
                <?php endif; ?>
            </form>

    <div class="row mt-2">       
        <?php if($res_user->role == "admin") : ?>
            <div class="col-12 ml-2">
                <hr class="mt-2 mb-3">
                <?= '<p class="fz-110"><span class="text-dark">Nombre de posts</span> : '; ?>
                <?= '<strong>'; ?>
                <?= ($nb_admin_posts == 0) ? '0' : $nb_admin_posts; ?>
                <?= '</strong></p>'; ?>
                <?php if ($nb_admin_posts > 0) : ?>
                    <ul class="list-unstyled ml-3">
                        <?php foreach ($res_admin_posts as $v) : ?>
                            <li><i class="fas fa-caret-right color-green-flash mr-2"></i><a href="article.php?id=<?= $v->id; ?>" class="link-list"><?= $v->title; ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            <div class="col-12 ml-2">
                <hr class="mt-2 mb-3">
            <?= '<p class="fz-110"><span class="text-dark">Nombre de commentaires</span> : '; ?>
            <?= '<strong>'; ?>
            <?= ($nb_user_coms == 0) ? '0' : $nb_user_coms; ?>
            <?= '</strong></p>'; ?>
            <?php if ($nb_user_coms > 0) : ?>
                <ul class="list-unstyled ml-3">
                    <?php foreach ($res_register_coms as $v1) : ?>
                        <?php
                            $created_com = $v1->created_comment_at;
                            $created_com = date("d-m-Y", strtotime($created_com));
                            $post=false;
                        ?>
                        <?php foreach ($res_post_com as $v2) : ?>
                            <?php if ($v1->post_id == $v2->id) : ?>
                                <?php if($v2==true) : ?>
                                    <?php $post = true; ?>
                                    <?php $title = $v2->title; ?>
                                <?php else : ?>
                                    <?php $post = false; ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <li class="mb-2">
                            <i class="fas fa-caret-right color-green-flash mr-2"></i><strong><?= $v1->commentaire; ?></strong>, <span class="fz-95p">le <?= $created_com; ?>.</span> <br> 
                            (Article du commentaire :
                                <?php if ($post) : ?>
                                    <a href="article.php?id=<?= $v1->post_id; ?>" class="link-list"><?= $title; ?></a>
                                <?php else : ?>
                                    <span class="text-danger font-weight-bold"><?= "Article supprimé" ?></a></span>
                                <?php endif; ?>
                            )
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        </div>
    </div>
    </div>
</div>

<?php require 'footer.php'; ?>