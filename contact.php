<?php session_start();
require 'header.php'; 
?>


      <div class="container">
        <div class="row w100rIndex m-auto">
          <div class="col-lg-12">
            <!-- ICI COMMENCE NOTRE PHP -->
            <h3 class="text-center mt-3 font-weight-bold">CONTACT</h3>
            <p class="text-center">Une question ? N'hésitez pas à nous contacter !!</p>

            <!-- <span class="ml-10p font-italic">Déjà inscrit ? </span> 
            <a class="btn btn-outline-primary btn-sm" href="index.php" role="button">Connexion</a> -->

            <form action="app/traitement_contact.php" method="POST" class="py-3 contact-form">
    <div class="form-row">
        <div class="form-group col-lg-6">
            <label for="firstname">Prénom <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="firstname" placeholder="Indiquez votre prénom" name="firstname">
        </div>
        <div class="form-group col-lg-6">
            <label for="exo1-lastname">Nom  <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="lastname" placeholder="Indiquez votre nom" name="lastname">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-lg-6">
            <label for="email">Email <span class="text-danger">*</span></label>
            <input type="email" class="form-control" id="email" placeholder="Indiquez votre email" name="email">
        </div>
        <div class="form-group col-lg-6">
    <div class="form-group">
    <label for="statut">Statut <span class="text-danger">*</span></label>
    <select class="form-control id="statut" name="statut">
      <option value="invited">Non Inscrit</option>
      <option value="user">Inscrit Utilisateur</option>
      <option value="admin">Inscrit Administrateur</option>
    </select>
  </div>
        </div>
    </div>

  <div class="form-group">
  <label for="message">Votre message <span class="text-danger">*</span></label>
  <textarea class="form-control" rows="5" id="message" name="message"></textarea>
</div> 
    <button type="submit" class="btn btn-dark my-3 w-100">Envoyer</button>
    </form>
          </div>
        </div>
      </div>


  </main>

<?php require 'footer.php'; ?>
