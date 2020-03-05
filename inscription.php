<?php session_start();
require 'header.php';
?>


<div class="container">
  <div class="row w100rIndex m-auto">
    <div class="col-lg-12">
      <!-- ICI COMMENCE NOTRE PHP -->
      <h3 class="text-center mt-3">INSCRIPTION</h3>
      <p class="text-center">Tous les champs sont obligatoires</p>

      <form action="app/traitement_inscription.php" method="POST" class="py-3">
        <div class="form-row">
          <div class="form-group col-lg-6">
            <label for="firstname">Prénom</label>
            <input type="text" class="form-control" id="firstname" placeholder="Indiquez votre prénom" name="firstname">
          </div>
          <div class="form-group col-lg-6">
            <label for="exo1-lastname">Nom</label>
            <input type="text" class="form-control" id="lastname" placeholder="Indiquez votre nom" name="lastname">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-lg-6">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" placeholder="Indiquez votre email" name="email">
          </div>
          <div class="form-group col-lg-6">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" placeholder="Choisissez un mot de passe" name="password">
          </div>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="role" id="role_utilisateur" value="user" checked>
          <label class="form-check-label" for="role_utilisateur">
            Utilisateur (Vous pouvez consulter les articles mais pas les modifier)
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="role" id="role_admin" value="admin">
          <label class="form-check-label" for="role_administrateur">
            Administrateur (vous participez à la modération des articles)
          </label>
        </div>
        <button type="submit" class="btn btn-dark my-3 w-100">Envoyer</button>
        <p class="text-center">Déjà inscrit ? <a href="index.php" class=" link-dark">Connectez-vous</a></p>
        <p class="text-center">Accéder au <a href="blog_index.php" class=" link-dark">blog</a> (sans inscription nécessaire)</p>
      </form>
    </div>
  </div>
</div>


</main>

<?php require 'footer.php'; ?>