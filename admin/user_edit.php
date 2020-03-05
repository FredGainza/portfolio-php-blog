<?php
require '../app/bdd.php';

$select = $dbh->prepare('SELECT * FROM users WHERE id = :id');
$select->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
$select->execute();
$res = $select->fetch(PDO::FETCH_OBJ);

$_SESSION['email'] = $res->email;
?>
<div class="col-10 pt-3">
  <span class="h3 font-weight-bold ml-3">Edition d'utilisateurs</span>
  <form action="traitement_edit_user.php" method="POST" class="py-3 pl-5">
    <div class="form-row">
      <div class="form-group col-6">
        <input type="text" class="form-control" id="firstname" placeholder="Prénom" name="firstname" value="<?= isset($res) && !empty($res) ? $res->firstname : ""; ?>">
      </div>
      <div class="form-group col-6">
        <input type="text" class="form-control" id="lastname" placeholder="Nom" name="lastname" value="<?= isset($res) && !empty($res) ? $res->lastname : ""; ?>">
      </div>
    </div>

    <div class="form-row">
      <div class="form-group col-6">
        <input type="email" class="form-control" id="email" placeholder="Email" name="email" value="<?= isset($res) && !empty($res) ? $res->email : ""; ?>">
      </div>
      <div class="form-group col-6">
        <div class="form-check">
          <input class="form-check-input" type="radio" name="role" id="role_utilisateur" value="<?php
            if ($res->role === 'user') {
                echo 'user" checked>'; 
            }
            ?>
          <label class="form-check-label" for="role_utilisateur">
            Utilisateur
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="role" id="role_admin" value="<?php
          if ($res->role === 'admin') {
            echo 'admin" checked>'; 
          }
          ?>
          <label class="form-check-label" for="role_administrateur">
            Administrateur
          </label>
        </div>
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-6">
          <label for="avatar"><b>Modification de l'image de profil</b></label>

          <input type="text" value="<?= isset($_SESSION['lien_avatar']) && !empty($_SESSION['lien_avatar']) ? $_SESSION['lien_avatar'] : $res->avatar; ?>" id="avatar" name ="avatar" alt="avatar" class="w-100">
          <div class="form-check mt-3">
            <input class="form-check-input" type="checkbox" name="reset_pwd" id="reset_pwd" value="valid">
            <label class="form-check-label" for="reset_pwd">
                <b>Réinitialiser le password</b>
            </label>
        </div> 
      </div>

    </div>
    <input type="hidden" name="id_user" value="<?= $_GET['id']; ?>">
    <button type="submit" class="btn btn-dark my-5 w-25 pl-3 ml-3 margin-auto" name="valider">Mettre à jour</button>
  </form>      
  
  <form action="refresh_avatar.php" method="GET" class="position-relative">
      <div class="form-group col-md-6 offset-md-6 logo">
        <img src="<?= isset($_SESSION['lien_avatar']) && !empty($_SESSION['lien_avatar']) ? $_SESSION['lien_avatar'] : $res->avatar; ?>" class="w-200p">
      <input type="hidden" name="id_user" value="<?= $_GET['id']; ?>">
      <select name="type_avatar" id="type_avatar" class="ml-5">
        <option value="avataaars" selected="selected">Avataaars</option>
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
        
                     
      </div>
      </form>