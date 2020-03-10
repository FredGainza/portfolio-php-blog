<div class="col-12 pt-3">
    <span class="h3 font-weight-bold ml-3">Formulaire d'ajout de sorties</span>
    <form action="traitement_ajout.php" method="POST" class="py-3 pl-5" enctype="multipart/form-data">
        <div class="form-row mr-3">
            <div class="form-group col-lg-6">
                <label for="title">Titre de la release</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Titre">
            </div>
            <div class="form-group col-lg-6">
                <label for="date">Date de sortie</label>
                <input type="date" class="form-control" id="date" name="date" value="2019/11/25">
            </div>
        </div>

        <div class="form-row mr-3">
            <div class="form-group col-lg-6">
                <label for="label">Label</label>
                <input type="text" class="form-control" id="label" name="label" placeholder="Label">
            </div>
            <div class="form-group col-lg-6">
                <label for="category">Catégorie</label>
                <select class="form-control" id="category" name="category">
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
                <input type="text" class="form-control" id="inlineFormInputGroupSpotify" name="spotify_URI" placeholder="Code URI (Album seulement !!)">
            </div>

        </div>

        <div class="form-row mr-3">
            <div class="col-12">
                <div class="form-group">
                    <label for="description">Court résumé de la sortie</label>
                    <textarea name="description" id="description" width="90%" rows="4"></textarea>
                </div>
            </div>
        </div>

        <div class="form-row mr-3">
            <div class="col-12">
                <div class="form-group">
                    <label for="content">Présentation de la release</label>

                    <textarea name="content" id="content" cols="108" rows="6"></textarea>
                </div>
            </div>
        </div>

        <div class="form-row mr-3">
            <div class="form-group custom-file ml-1">
                <input type="file" class="custom-file-input" id="image" name="image">
                <label class="custom-file-label" for="customFile">Importer une image</label>
            </div>
        </div>

        <button type="submit" class="btn btn-dark my-3 with-resp">Envoyer l'article</button>
    </form>