<?php
  session_start();
  require '../app/can_connect_admin.php';
  require '../toolbox.php';
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
  <meta name="generator" content="Jekyll v3.8.5">
  <title>Blog sorties EP Techno</title>

  <link rel="canonical" href="https://getbootstrap.com/docs/4.3/examples/album/">

  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css">
  <link type="image/png" rel="icon" href="../images/favicon.png" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Noto+Sans|Roboto&display=swap" rel="stylesheet">
  <link rel="stylesheet " href="../css/album.css">
  <link rel="stylesheet" href="../css/style.min.css">
  <link rel="stylesheet" href="../css/slide.css">
  <link rel="stylesheet" href="../css/slidebar.css">
  <script src="https://kit.fontawesome.com/a076d05399.js"></script>
  <script src="https://cdn.ckeditor.com/4.13.0/standard/ckeditor.js"></script>
</head>
<body>
   
    
    <input type="checkbox" id="check">
    <label for="check">
      <i class="fas fa-bars" id="btn"></i>
      <i class="fas fa-times" id="cancel"></i>
    </label>
    <div class="sidebar">
      <header>Admin Panel</header>
      <ul>
        <li style="height: 50px;"></li>
        <li><a href="?article=table"><i class="fas fa-qrcode"></i>Articles</a></li>
        <li style="height: 20px;"></li>
        <li><a href="?comment=table"><i class="fas fa-link"></i>Commentaires</a></li>
        <li style="height: 20px;"></li>
        <li><a href="?user=table"><i class="fas fa-stream"></i>Utilisateurs</a></li>
        <li style="height: 50px;"></li>
        <li><a href="../blog_index.php"><i class="fas fa-calendar-week"></i>Blog</a></li>
      </ul>
</div>
 <section>

    <div class="row-top-25 bg-bleu text-right text-nowrap">
      <span class="mr-3 ml-auto navbar-text text-gris">Bonjour </span><span class="navbar-text text-white">
        <a href="../user_profil.php"><?= $_SESSION['log_firstname'] . ' ' . $_SESSION['log_lastname']; ?></a>
      
        <a class="nav-link d-inline" href="disconnect.php"><i class="fas fa-times text-danger mr-2"></i></a></span>
      </div>


 
 <!-- Page Content -->
 
 <?php if (isset($_SESSION['errors_admin']) || isset($_SESSION['success_admin'])) :  ?>
 <?php $message = isset($_SESSION['errors_admin']) && !empty($_SESSION['errors_admin']) ? $_SESSION['errors_admin'] : (isset($_SESSION['success_admin']) ? $_SESSION['success_admin'] : ''); ?>
 <?php $color = isset($_SESSION['errors_admin'])  && ($_SESSION['errors_admin'] != "") ? 'danger' : (isset($_SESSION['success_admin']) ? 'success' : ''); ?>
 <div class="alert my-0 ml-0 center alert-<?= $color; ?>">
   <p><?= $message; ?></p>
 </div>
 <?php
 // Je supprime toute les sessions errors et success
 unset($_SESSION['errors_admin']);
 unset($_SESSION['success_admin']);
endif;

if (isset($_GET['article']) && $_GET['article'] == "table") {
 require 'article_table.php';
}

if (isset($_GET['article']) && $_GET['article'] == "ajout") {
 require 'article_ajout.php';
}

if (isset($_GET['article']) && $_GET['article'] == "edit" && isset($_GET['id']) && !empty($_GET['id'])) {
 require 'article_edit.php';
}

if (isset($_GET['article']) && $_GET['article'] == "delete" && isset($_GET['id']) && !empty($_GET['id'])) {
 require 'article_delete.php';
}

if (isset($_GET['user']) && $_GET['user'] == "table") {
 require 'user_table.php';
}

if (isset($_GET['user']) && $_GET['user'] == "ajout") {
 require 'user_ajout.php';
}

if (isset($_GET['user']) && $_GET['user'] == "view" && isset($_GET['id']) && !empty($_GET['id'])) {
 require '../user_profil.php';
}

if (isset($_GET['user']) && $_GET['user'] == "edit" && isset($_GET['id']) && !empty($_GET['id'])) {
 require 'user_edit.php';
}

if (isset($_GET['user']) && $_GET['user'] == "delete" && isset($_GET['id']) && !empty($_GET['id'])) {
 require 'user_delete.php';
}

if (isset($_GET['comment']) && $_GET['comment'] == "table") {
 require 'comment_table.php';
}

if (isset($_GET['comment']) && $_GET['comment'] == "edit" && isset($_GET['id']) && !empty($_GET['id'])) {
 require 'comment_edit.php';
}

if (isset($_GET['comment']) && $_GET['comment'] == "delete" && isset($_GET['id']) && !empty($_GET['id'])) {
 require 'comment_delete.php';
}

if (null == $_GET || empty($_GET)) {
 
 require '../app/bdd.php';

 $select_aut_post = $dbh->prepare('SELECT * FROM users LEFT JOIN blog_posts ON blog_posts.user_id = users.id ORDER BY blog_posts.id DESC LIMIT 3');
 $select_aut_post->execute();
 $res_aut = $select_aut_post->fetchAll(PDO::FETCH_OBJ);
?>

<?php if (isset($res_aut) || isset($_GET['affiche']) && $_GET['affiche'] == 'ok') : ?>
 <div class="col-12 pt-3 fz-80">
   <h5>Derniers enregistrements de posts</h5>
   <table class="my-3 w-100 table-striped">
     <thead>
       <tr>
         <th>Id</th>
         <th class="w-70p">Image</th>
         <th>Catégorie</th>
         <th>Auteur</th>
         <th>Titre</th>
         <th>Date de sortie</th>
         <th>Label</th>
         <th>Description</th>
         <th class="w-400p">Contenu</th>
         <th>Date création</th>
       </tr>
     </thead>

     <tbody>

     <?php foreach ($res_aut as $value) : ?>
       <?php
         $date_sql = $value->date;
         $seulementDate = date("d-m-Y", strtotime($date_sql));

         $created = date("d-m-Y", strtotime($value->created_at));
       ?>
       <tr>
         <td><?= $value->id; ?></td>
         <td><a href="../images/<?= $value->image; ?>" target="_blank"><img src="../images/miniatures/<?= $value->image; ?>"></a></td>
         <td><?= $value->category; ?></td>
         <td><?= $value->firstname . ' ' . $value->lastname; ?></td>
         <td><?= $value->title; ?></td>
         <td><?= $seulementDate; ?></td>
         <td><?= $value->label; ?></td>
         <td><?= $value->description; ?></td>
         <td><?= tronquer_texte($value->content, 150); ?></td>
         <td><?= $created; ?></td>
       </tr>
     <?php endforeach ?>
   </tbody>
 </table>

 <?php endif; ?>
 <p class="text-right"><a href="?article=table" class="black">Voir plus</a></p>

 <hr>
 <hr>
 <h5 class="pt-3">Derniers enregistrements de commentaires</h5>
 </div>
 <?php
   $select_comment = $dbh->prepare('SELECT * FROM blog_comments ORDER BY id DESC LIMIT 3');
   $select_comment->execute();
   $res_comment = $select_comment->fetchAll(PDO::FETCH_OBJ);
   // dumpPre($res_comment);
   // exit;
 ?>
 <?php if (isset($res_comment) || isset($_GET['comment']) && $_GET['comment'] == "table") : ?>
   <div class="col-12 fz-80">
     <table class="my-3 w-100 table-striped">
       <thead>
         <tr>
           <th>ID</th>
           <th>Post ID</th>
           <th>Commentaire</th>
           <th>User_ID</th>
           <th>Date</th>
         </tr>
       </thead>

       <tbody>
         <?php foreach ($res_comment as $v) : ?>
           <?php
             $created = date("d-m-Y", strtotime($v->created_comment_at));
           ?>
           <tr>
             <td><?= $v->id; ?></td>
             <td><a href="../article.php?id=<?= $v->post_id; ?>" class="text-primary"><?= $v->post_id; ?></td></a>
             <td><?= $v->commentaire; ?></td>
             <td><a href="?user=table&id=<?= $v->user_id; ?>" class="text-primary"><?= $v->user_id; ?></td></a>
             <td><?= $created; ?></td>
           </tr>
         <?php endforeach ?>
       </tbody>

     </table>
     <?php endif; ?>
     <p class="text-right"><a href="?comment=table" class="black">Voir plus</a></p>
     <hr>
     <hr>
     <h5 class="pt-3">Derniers enregistrements d'utilisateurs</h5>
   </div>

   <?php
     $select_post = $dbh->prepare('SELECT user_id, COUNT(user_id) as nb_posts FROM blog_posts LEFT JOIN users ON users.id = blog_posts.user_id GROUP BY user_id');
     $select_post->execute();
     $res_user_nb_posts = $select_post->fetchAll(PDO::FETCH_OBJ);

     $select_com = $dbh->prepare('SELECT user_id, COUNT(user_id) as nb_coms FROM blog_comments GROUP BY user_id');
     $select_com->execute();
     $res_user_nb_coms = $select_com->fetchAll(PDO::FETCH_OBJ);

     $select = $dbh->prepare('SELECT * FROM users  ORDER BY id DESC LIMIT 3');
     $select->execute();
     $res = $select->fetchAll(PDO::FETCH_OBJ);

   ?>
   <?php if (isset($res) || isset($_GET['users']) && $_GET['users'] == "table") : ?>
     <div class="col-12 fz-80">
       <table class="my-3 w-100 table-striped">
         <thead>
           <tr>
             <th>Id</th>
             <th>Email</th>
             <th class="w-50p">Avatar</th>
             <th>Role</th>
             <th>Prenom</th>
             <th>Nom</th>
             <th>Nb de POST</th>
             <th>Nb de COM</th>
             <th>Date membre</th>
           </tr>
         </thead>
         <tbody>
           <?php foreach ($res as $v) : ?>
             <?php
               $created = date("d-m-Y", strtotime($v->created_at));
             ?>
             <tr>
               <td><?= $v->id; ?></td>
               <td><?= $v->email; ?></td>
               <td><img src="<?= $v->avatar; ?>"></td>
               <td><?= $v->role; ?></td>
               <td><?= $v->firstname; ?></td>
               <td><?= $v->lastname; ?></td>
               <td class="text-center">
                 <?php
                   foreach ($res_user_nb_posts as $v1) {
                     $id = $v1->user_id;
                     $nb_posts = $v1->nb_posts;
                     if ($v->id == $id) {
                       echo $nb_posts;
                     }
                   }
                   ?>
               </td>
               <td class="text-center">
                 <?php
                   foreach ($res_user_nb_coms as $v2) {
                     $id = $v2->user_id;
                     $nb_coms = $v2->nb_coms;
                     if ($v->id == $id) {
                       echo $nb_coms;
                     }
                   }
                 ?>
               </td>
               <td><?= $created; ?></td>
             </tr>
           <?php endforeach ?>
         </tbody>
       </table>
     <?php endif; ?>
     <p class="text-right"><a href="?user=table" class="black">Voir plus</a></p>
   <?php
 }
?>
</div>
</section>
<footer>
<div class="container">
</div>
</footer>
<!-- /#page-content-wrapper -->

</div>
</div>
<!-- /#wrapper -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script>
CKEDITOR.replace('description');
</script>
<script>
CKEDITOR.replace('content');
</script>
  </body>
</html>



      
