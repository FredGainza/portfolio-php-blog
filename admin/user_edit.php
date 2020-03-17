<?php
require '../app/bdd.php';
// dumpPre($_SESSION);
// dumpPre($_GET);
// exit;
$select = $dbh->prepare('SELECT * FROM users WHERE id = :id');
$select->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
$select->execute();
$res = $select->fetch(PDO::FETCH_OBJ);

$_SESSION['email'] = $res->email;
?>
<h3 class=" font-weight-bold ml-3 my-3">Edition d'utilisateurs</h3>

<form action="refresh_avatar.php" method="GET" class="position-relative">
  <div class="form-group col-lg-12 ml-5">
    <label for="avatar"><b>Modification de l'image de profil</b></label><br>
    <img src="<?= isset($_SESSION['lien_avatar']) && !empty($_SESSION['lien_avatar']) ? $_SESSION['lien_avatar'] : $res->avatar; ?>" class="w-200p">
    <div class="mt-3">
      <input type="hidden" name="id_user" value="<?= $_GET['id']; ?>">
        <select name="type_avatar" id="type_avatar" class="ml-4">
          <option value="avataaars" selected="selected">Avataaars</option>
          <option value="bottts">Bottts</option>
          <option value="gridy">Gridy</option>
          <option value="male">Male</option>
          <option value="female">Female</option>
          <option value="human">Human</option>
          <option value="identicon">Identicon</option>
          <option value="jdenticon">Jdenticon</option>
        </select>
        <input type="hidden" name="id_user" value="<?= $_GET['id']; ?>">
        <button class="btn btn-dark btn-random btn-sm ml-1" id="bouton" name="random">Random</button>
      </div>

  </div>
</form>

<div class="col-12 pt-3">

  <form action="traitement_edit_user.php" method="POST" class="py-3 pad-bloc">
    <div class="form-row mr-3">
      <div class="form-group col-lg-6">
        <label for="firstname">Prénom</label>
        <input type="text" class="form-control" id="firstname" placeholder="Prénom" name="firstname" value="<?= isset($res) && !empty($res) ? $res->firstname : ""; ?>">
      </div>
      <div class="form-group col-lg-6">
        <label for="lastname">Nom</label>
        <input type="text" class="form-control" id="lastname" placeholder="Nom" name="lastname" value="<?= isset($res) && !empty($res) ? $res->lastname : ""; ?>">
      </div>
    </div>


    <div class="form-row mr-3">
      <div class="form-group col-lg-6">
        <label for="email">Adresse Email</label>
        <input type="email" class="form-control" id="email" placeholder="Email" name="email" value="<?= isset($res) && !empty($res) ? $res->email : ""; ?>">
      </div>
      <div class="form-group col-lg-6">
        <div name="role">
          <label for="role">Grade</label>
          <div class="form-check ml-3">
            <input class="form-check-input" type="radio" name="role" id="role_utilisateur" value="<?= ($res->role === 'user') ? 'user" checked>' : ''; ?>
          <label class=" form-check-label" for="role_utilisateur">
            Utilisateur
            </label>
          </div>
          <div class="form-check ml-3">
            <input class="form-check-input" type="radio" name="role" id="role_admin" value="<?= ($res->role === 'admin') ? 'admin" checked>' : ''; ?>
          <label class=" form-check-label" for="role_administrateur">
            Administrateur
            </label>
          </div>
        </div>
      </div>
    </div>


    <div class="form-row mr-3">
      <div class="form-group col-lg-12 form-check">
        <label class="form-check-label" for="reset_pwd">
          <b>Réinitialiser le password</b>
        </label>
        <input class="form-check-input-pwd" type="checkbox" name="reset_pwd" id="reset_pwd" value="valid">
      </div>
      <div class="form-group col-lg-12">

        <?php if(isset($_SESSION['lien_avatar']) && $_SESSION['lien_avatar'] != $res->avatar) : ?>
          <input type="hidden" name="avatar" value="<?= $_SESSION['lien_avatar']; ?>">
        <?php endif; ?>
        <?php if(!isset($_SESSION['lien_avatar']) || $_SESSION['lien_avatar'] == $res->avatar) : ?>
          <input type="hidden" name="avatar" value="<?= $res->avatar; ?>">
        <?php endif; ?>

        <input type="hidden" name="id_user" value="<?= $_GET['id']; ?>">
        <button type="submit" class="btn btn-dark my-3 with-resp" name="valider">Mettre à jour</button>
      </div>
    </div>
  </div>
</form>