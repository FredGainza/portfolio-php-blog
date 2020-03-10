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
                    <img src="<?= isset($_SESSION['lien_avatar']) && !empty($_SESSION['lien_avatar']) ? $_SESSION['lien_avatar'] : $res_user->avatar; ?>" class="w-250p">
                </div>
                <div class="author-card-details mt-3 pl-4">
                    <h5 class="author-card-name color-red-cay"><b><?= $res_user->firstname . ' ' . $res_user->lastname; ?></b></h5><span class="author-card-position">Membre depuis le <?= $created; ?></span>
                </div>

                <form action="app/refresh_avatar.php" method="GET" class="pt-3">
                    <input type="hidden" name="id_user" value="<?= $_GET['id']; ?>">
                    <select name="type_avatar" id="type_avatar" class="ml-5">
                        <option value="avataaars">Avataaars</option>
                        <option value="bottts">Bottts</option>
                        <option value="gridy">Gridy</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="human">Human</option>
                        <option value="identicon">Identicon</option>
                        <option value="jdenticon">Jdenticon</option>
                    </select>

                    <button class="btn btn-dark" id="bouton" name="random">Random</button>
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
                        <input class="form-control" type="email" id="account-email" name="email2" value="<?= $res_user->email; ?>">
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
                        <input class="form-control" type="password" name="password" id="account-pass">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="account-confirm-pass">confirmez le password</label>
                        <input class="form-control" type="password" name="password2" id="account-confirm-pass">
                    </div>
                </div>
                <div class="col-12">
                    <hr class="mt-2 mb-3">
                    <div class="d-flex flex-wrap justify-content-between align-items-center">
                        <div class="custom-control custom-checkbox d-block">
                            <input class="custom-control-input" type="checkbox" id="subscribe_me" checked="">
                            <label class="custom-control-label" for="subscribe_me">Souscrire à la newsletter</label>
                        </div>
                        <input type="hidden" name="id_user" value="<?= $res_user->id; ?>">
                        <input type="hidden" name="post_avatar" value="<?= isset($_SESSION['lien_avatar']) && !empty($_SESSION['lien_avatar']) ? $_SESSION['lien_avatar'] : $res_user->avatar; ?>">
                        <button class="btn btn-style-1 btn-dark" type="submit" data-toast="" data-toast-position="topRight" data-toast-type="success" data-toast-icon="fe-icon-check-circle" data-toast-title="Success!" data-toast-message="Your profile updated successfuly.">Update Profile</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require 'footer.php'; ?>