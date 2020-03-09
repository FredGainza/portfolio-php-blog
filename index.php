<?php 
session_start();
require 'header.php'; 
?>
  <div class="container mt-3">
    <div class="row w100rIndexZ m-auto">
      <div class="col-12 ">
        <!-- ICI COMMENCE NOTRE PHP -->
        <h3 class="text-center mt-3">CONNEXION</h3>
        
        <form action="app/traitement_connexion.php" method="POST" class="py-3">
          <div class="form-group mail">
            <label for="exampleInputEmail1">Adresse email</label>
            <input type="email" class="form-control text-info bold-focus pl-40" id="email" aria-describedby="emailHelp" name="email" value="<?php 
            if (isset($_COOKIE['remember'])){
              echo $_COOKIE['remember'];
            } else {
              echo '';
            }
            ?>
              " placeholder="Saisir votre email">
            <i class="fa fa-user"></i>
          </div>
          <div class="form-group pwd">
            <label for="exampleInputPassword1">Password</label>
            <input type="password" class="form-control pl-40" id="password" name="password" placeholder="Entrez votre mot de passe">
            <i class="fa fa-lock"></i>
            <div class="help-block"><a href="index_mdp_forget.php" class="link-dark">Mot de passe oublié ?</a></div>
          </div>
          <div class="form-check">
            <input type="checkbox" class="form-check-input" id="remember" name="remember" value="ok">
            <label class="form-check-label" for="remember">Rester connecté</label>
          </div>
          <button type="submit" class="btn btn-dark my-3 w-100">Envoyer</button>
          <p class="text-center">Pas encore inscrit ? <a href="inscription.php" class=" link-dark">Cliquez ici</a></p>
          <p class="text-center">Accéder au <a href="blog_index.php" class=" link-dark">blog</a> (sans inscription nécessaire)</p>
        </form>
      </div>
    </div>
  </div>

</main>

<?php require 'footer.php'; ?>