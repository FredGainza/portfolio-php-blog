<div class="col-12 pt-3">
  <span class="h3 font-weight-bold ml-3">Formulaire d'ajout d'utilisateurs</span>
  <form action="traitement_ajout_user.php" method="POST" class="py-3 pl-2">
    <div class="form-row mr-3">
      <div class="form-group col-lg-6">
        <label for="firstname">Prénom</label>
        <input type="text" class="form-control" id="firstname" placeholder="Prénom" name="firstname">
      </div>
      <div class="form-group col-lg-6">
        <label for="lastname">Nom</label>
        <input type="text" class="form-control" id="lastname" placeholder="Nom" name="lastname">
      </div>
    </div>

    <div class="form-row mr-3">
      <div class="form-group col-lg-6">
        <label for="email">Adresse Email</label>
        <input type="email" class="form-control" id="email" placeholder="Email" name="email">
      </div>
      <div class="form-group col-lg-6">
        <div name="role">
          <label for="role">Grade</label>
        <div class="form-check ml-3">
          <input class="form-check-input" type="radio" name="role" id="role_utilisateur" value="user" checked>
          <label class="form-check-label" for="role_utilisateur">
            Utilisateur
          </label>
        </div>
        <div class="form-check ml-3">
          <input class="form-check-input" type="radio" name="role" id="role_admin" value="admin">
          <label class="form-check-label" for="role_administrateur">
            Administrateur
          </label>
        </div>
      </div>
      </div>
    </div>
    <div class="form-row mr-3">
      <b>Information :</b> 
      <span class="ml-3">Un mot de passe et un avatar temporaires seront automatiquement créés lors de l'envoi du formulaire. <br>
      Un mail récapitulatif sera envoyé à ce nouvel utilisateur.</span><br>
    </div>
    <button type="submit" class="btn btn-dark my-3 with-resp">Ajouter l'utilisateur</button>
  </form>