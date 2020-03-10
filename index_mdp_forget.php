<?php session_start();
require 'header.php';
?>
<div class="container mt-3">
  <div class="row w100respMdp m-auto">
    <div class="col-12 ">
      <!-- ICI COMMENCE NOTRE PHP -->
      <h3 class="text-center mt-3">Mot de passe oublié</h3>
      <form action="app/forget_pwd.php" method="POST" class="py-3">
        <div class="form-group mail">
          <label for="exampleInputEmail1">Entrez votre adresse email</label>
          <input type="email" class="form-control text-info bold-focus pl-40" id="email" aria-describedby="emailHelp" name="email" value="<?php
                                                                                                                                          if (isset($_COOKIE['remember'])) {
                                                                                                                                            echo $_COOKIE['remember'];
                                                                                                                                          } else {
                                                                                                                                            echo '';
                                                                                                                                          }
                                                                                                                                          ?>
              " placeholder="Sasisir l'email utilisée lors de votre inscription">
          <i class="fa fa-user"></i>
        </div>
        <p class="text-justify">Si cette adresse email a bien été utilisée pour s'inscrire sur ce site, nous vous enverrons par mail un nouveau mot de passe.</p>
        <button type="submit" class="btn btn-dark mt-2 mb-3 w-100">Envoyer</button>
        <p class="text-center">Pas encore inscrit ? <a href="inscription.php" class=" link-dark">Cliquez ici</a></p>
        <p class="text-center">Accéder au <a href="blog_index.php" class=" link-dark">blog</a> (sans inscription nécessaire)</p>
      </form>
    </div>
  </div>
</div>


</main>

<?php require 'footer.php'; ?>