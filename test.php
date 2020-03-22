<?php

/*---------------------------------------------------------------*/
/*
    Système de pagination (avec ou sans bootstrap)                                                                                 
                                                                                                                          
    Méthode : 
        - remplacer 

*/
$table = "users";
$requete = 
$limit=5;


    $page = isset($_GET['page']) ? $_GET['page'] : ''; 
    $debut = '';

    // Prepare le requete MySql    
    $req = $dbh->prepare("SELECT * from $table");
    $req->execute();
    $resultat_temp = $req->fetchAll(PDO::FETCH_OBJ);

    $debut=="" ? 0 : $page*$limit;
    
    // Compte le nombre de champ
    $nb_total=$req->rowCount();
    // Requete
    $limite = $dbh->prepare("$requete limit $debut,$limit");


    //Affichage le contenu de votre table
    //avec une limite, dans l'exemple $limit est à 4
    $limit_str = "LIMIT ". $page * $limit .",$limit";

    $req_order = $dbh->prepare("SELECT * FROM $table ORDER BY id ASC $limit_str");
    $req_order->execute();

    $resultat = $req_order->fetchAll(PDO::FETCH_OBJ);

    foreach ($resultat as $v){
        echo $v->firstname;
        echo '<br>';
    }
?>
<style> 
.pagination > li > a
{
    background-color: white;
    color: #1f2428 !important;
}

.pagination > li > a:focus,
.pagination > li > a:hover,
.pagination > li > span:focus,
.pagination > li > span:hover
{
    color: #1f2428 !important;
    background-color: #eee !important;
    border-color: #ddd !important;
}

.pagination > .active > a
{
    color: white !important;
    background-color: #1f2428 !important;
    border: solid 1px #1f2428 !important;
}

.pagination > .active > a:hover
{
    background-color: #1f2428 !important;
    border: solid 1px #1f2428 !important;
}


</style>
<nav aria-label="Partie Pagination">
  <ul class="pagination">

<?php 
    // Affiche le page par page avec ses liens
    if ($page>0) {
    $precedent=$page-1;
    
    echo "<li class=\"page-item\">
            <a class=\"page-link\" href=\"".$_SERVER['PHP_SELF']."?page=$precedent\" aria-label=\"Previous\">
                <span aria-hidden=\"true\">&laquo;</span><span class=\"sr-only\">
                    Previous
                </span>
            </a>
        </li>";

    }else{
        $page=0;
    }

    $i=0;
    $j=1;

    if ($nb_total>$limit) {
        while ($i<($nb_total/$limit)) {        
            if ($i!=$page){
                echo "<li class=\"page-item\">
                        <a class=\"page-link\" href=\"".$_SERVER['PHP_SELF']."?page=$i\">
                            $j 
                        </a>
                    </li>";}
            else {
                echo "<li class=\"page-item active\">
                        <a class=\"page-link\" href=\"#\">
                            $j<span class=\"sr-only\">(current)</span>
                        </a>
                    </li>";}
            $i++;
            $j++;
        }
    }

    if ($debut+$limit<$nb_total) {
        $suivant = $page+1;
        echo "<li class=\"page-item\">
            <a class=\"page-link\" href=\"".$_SERVER['PHP_SELF']."?page=$suivant\" aria-label=\"Next\">
                <span aria-hidden=\"true\">
                    &raquo;
                </span>
                <span class=\"sr-only\">
                    Next
                </span>
            </a>
        </li>";
    }
    echo "</ul></nav>";
?>



<nav aria-label="Partie Pagination">
  <ul class="pagination">

<?php 
    // Affiche le page par page avec ses liens
    if ($page>0) {
        $precedent = $page - 1;
        echo "<a href=\"".$_SERVER['PHP_SELF']."?page=$precedent\">Précédent</a>";
    }else{
        $page=0;
    }

    $i=0;
    $j=1;

    if ($nb_total>$limit) {
        while ($i<($nb_total/$limit)) {
            if ($i!=$page) {
                echo "<a href=\"".$_SERVER['PHP_SELF']."?page=$i\">$j</a>";}
            else {
                echo "<b>$j</b>";
            }
            $i++;
            $j++;
        }
    }

    if($debut+$limit<$nb_total) {
        $suivant = $page + 1;
        echo "<a href=\"".$_SERVER['PHP_SELF']."?page=$suivant\">Suivant</a>";
    }
    echo "</ul></nav>";

require 'footer.php';