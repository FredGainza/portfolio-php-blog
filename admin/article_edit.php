<?php
require '../app/bdd.php';

$select = $dbh->prepare('SELECT * FROM blog_posts WHERE id = :id');
$select->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
$select->execute();
$res = $select->fetch(PDO::FETCH_OBJ);

?>

<div class="col-12 pt-3">
    <span class="h3 font-weight-bold ml-3">Formulaire d'édition des sorties</span>
    <form action="traitement_edit.php" method="POST" class="py-3 pl-2" enctype="multipart/form-data">
    <div class="form-row mr-3">
            <div class="form-group col-lg-6">
                <label for="title">Titre de la release</label>
                <input type="text" class="form-control" id="title" name="title" value="<?= isset($res) && !empty($res) ? $res->title : ""; ?>">
            </div>
            <div class="form-group col-lg-6">
                <label for="date">Date de sortie</label>
                <input type="date" class="form-control" id="date" name="date" value="<?= isset($res) && !empty($res) ? $res->date : ""; ?>">
            </div>
        </div>

        <div class="form-row mr-3">
            <div class="form-group col-lg-6">
                <label for="label">Label</label>
                <input type="text" class="form-control" id="label" name="label" value="<?= isset($res) && !empty($res) ? $res->label : ""; ?>">
            </div>
            <div class="form-group col-lg-6">
                <label for="category">Catégorie</label>
                <select class="form-control" id="category" name="category" value="<?= isset($res) && !empty($res) ? $res->category : ""; ?>">
                    <option value="deep">Deep Techno</option>
                    <option value="minimal" selected>Minimal Techno</option>
                    <option value="hard">Techno Hard</option>
                </select>
            </div>
        </div>

        <div class="form-row mr-3 mb-3">
            <label for="label" id="">Code URI Spotify</label>

            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">spotify:album:</div>
                </div>
                <input type="text" class="form-control" id="inlineFormInputGroupSpotify" name="spotify_URI"  value="<?= isset($res) && !empty($res) ? $res->spotify_URI : ""; ?>">
            </div>
        </div>

        <div class="form-row mr-3">
            <div class="col-12">
                <div class="form-group">
                    <label for="description">Court résumé de la sortie</label>
                    <textarea name="description" id="description" width="90%" rows="4"><?= $res->description; ?></textarea>
                </div>
            </div>
        </div>

        <div class="form-row mr-3">
            <div class="col-12">
                <div class="form-group">
                    <label for="content">Présentation de la release</label>

                    <textarea name="content" id="content" cols="108" rows="6"><?= isset($res) && !empty($res) ? $res->content : ""; ?></textarea>
                </div>
            </div>
        </div>
        
        <div class="form-row mr-3">
            <div class="form-group custom-file ml-1">
                <input type="file" class="custom-file-input" id="image" name="image">
                <label class="custom-file-label" for="customFile">Importer une image</label>
            </div>
        </div>

        <button type="submit" class="btn btn-dark my-3 with-resp">Mise à jour de l'article</button>

    </form>

</div>