<div class="col-10 pt-3">
  <span class="h3 font-weight-bold ml-3">Formulaire d'ajout d'utilisateurs</span>
  <form action="traitement_ajout_user.php" method="POST" class="py-3 pl-5">
    <div class="form-row">
      <div class="form-group col-6">
        <input type="text" class="form-control" id="firstname" placeholder="Prénom" name="firstname">
      </div>
      <div class="form-group col-6">
        <input type="text" class="form-control" id="lastname" placeholder="Nom" name="lastname">
      </div>
    </div>

    <div class="form-row">
      <div class="form-group col-6">
        <input type="email" class="form-control" id="email" placeholder="Email" name="email">
      </div>
      <div class="form-group col-6">
        <div class="form-check">
          <input class="form-check-input" type="radio" name="role" id="role_utilisateur" value="user" checked>
          <label class="form-check-label" for="role_utilisateur">
            Utilisateur
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="role" id="role_admin" value="admin">
          <label class="form-check-label" for="role_administrateur">
            Administrateur
          </label>
        </div>
      </div>
    </div>
    <div class="form-row mt-3">
      <b>Info</b> : un mot de passe et un avatar temporaires seront automatiquement créés lors de l'envoi du formulaire. <br>
      Un mail récapitulatif sera envoyé à ce nouvel utilisateur. <br>
    </div>
    <button type="submit" class="btn btn-dark my-5 w-25 pl-3 ml-3 margin-auto">Envoyer l'ajout</button>
  </form>