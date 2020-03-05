<?php
require '../app/bdd.php';

  $select_aut_post = $dbh->prepare('SELECT * FROM users LEFT JOIN blog_posts ON blog_posts.user_id = users.id WHERE user_id IS NOT NULL');

  $select_aut_post->execute();
  $res_aut = $select_aut_post->fetchAll(PDO::FETCH_OBJ);

?>

<?php if(isset($res_aut) || isset($_GET['affiche']) && $_GET['affiche'] == 'ok') : ?>

<div class="col-12 pt-3">
  <div class="text-right">
    <button type="button" class="btn btn-dark mr-0 ml-auto"><a href="?article=ajout" class="ajout" title="Ajout">Ajouter une release</a></button>
  </div>

  <table class="my-3 w-100 table-striped">
    <thead>
      <tr>
        <th class="pl-2">Id</th>
        <th>Image</th>
        <th>Catégorie</th>
        <th>Auteur</th>
        <th>Titre</th>
        <th>Date de sortie</th>
        <th>Label</th>
        <th>Description</th>
        <th>Contenu</th>
        <th>Date création</th>
        <th>Lecture</th>
        <th class="text-center">Actions</th>     
      </tr>
    </thead>

    <tbody class="fz-90p">
      <?php foreach ($res_aut as $value) : ?>

        <?php
        $date_sql = $value->date;
        $seulementDate = date("d-m-Y", strtotime($date_sql));

        $created = date("d-m-Y", strtotime($value->created_at));
        ?>

        <tr>
          <td class="pl-3"><?= $value->id; ?></td>
          <td><a href="../images/<?= $value->image; ?>" target="_blank"><img src="../images/miniatures/<?= $value->image; ?>"></a></td>
          <td><?= $value->category; ?></td>
          <td><?= $value->firstname.' '.$value->lastname; ?></td>
          <td><?= $value->title; ?></td>
          <td><?= $seulementDate; ?></td>
          <td><?= $value->label; ?></td>
          <td><?= $value->description; ?></td>
          <td><?= tronquer_texte($value->content, 200); ?></td>
          <td><?= $created; ?></td>
          <td>
            <?php if (isset($value->spotify_URI) && !empty($value->spotify_URI)) : ?>
            <iframe src="https://open.spotify.com/embed/album/<?= $value->spotify_URI; ?>" width=300" height="80" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>
            <?php else : ?>
              <h6 class="color-red-cay">Pas d'audio dispo</h6>
            <?php endif; ?>
          </td>
          
          <td class="text-center">
            <a href="../article.php?id=<?= $value->id ?>" class="view pr-1" title="view"><i class="material-icons color-green mb-3">visibility</i></a>             
            <a href="?article=edit&id=<?= $value->id ?>" class="edit pr-1" title="Edit"><i class="material-icons color-blue mb-3"></i></a>
            <a href="?article=delete&id=<?= $value->id ?>" class="delete" title="Delete"><i class="material-icons color-red"></i></a>
          </td>
        </tr>
      <?php endforeach ?>
    </tbody>

  </table>
<?php endif; ?>
    
<?php 'require footer_admin.php'; ?>