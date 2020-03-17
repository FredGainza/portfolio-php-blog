<?php
session_start();
require 'header.php';
require 'app/bdd.php';
require 'toolbox.php';
/*---------------------------------------------------------------*/
/*
    Titre : Affichage page par page                                                                                       
                                                                                                                          
    URL   : https://phpsources.net/code_s.php?id=47
    Date édition     : 05 Sept 2004                                                                                       
    Date mise à jour : 26 Sept 2019
                                                                                      
    Rapport de la maj:                                                                                                    
    - refactoring du code en PHP 7                                                                                        
    - fonctionnement du code vérifié                                                                                    
*/
$table = "users";

    $page = isset($_GET['page']) ? $_GET['page'] : ''; 
    $debut = '';
    // Prepare le requete MySql
    $requete = "SELECT * from $table";
    $req = $dbh->prepare($requete);
    $req->execute();
    $ret = $req->fetchAll(PDO::FETCH_OBJ);
    // dumpPre($ret);exit;
    // Variable nombre d'enreg par page
    $limit=5;
    // echo $page;exit;


    if($debut==""){$debut=0;}else{$debut=$page*$limit;}
    
    // Compte le nombre de champ
    $nb_total=$req->rowCount();
    // Requete
    $limite = $dbh->prepare("$requete limit $debut,$limit");


    //Affichage le contenu de votre table
    //avec une limite, dans l'exemple $limit est à 4
    $limit_str = "LIMIT ". $page * $limit .",$limit";

    $result = $dbh->prepare("SELECT * 
                          FROM $table
                          ORDER BY id 
                          ASC $limit_str");

    $result->execute();
    $res = $result->fetchAll(PDO::FETCH_OBJ);

    foreach ($res as $v){
        echo $v->firstname;
        echo '<br>';
    }
?>
<style> 
.pagination > li > a
{
    background-color: white;
    color: #1f2428;
}

.pagination > li > a:focus,
.pagination > li > a:hover,
.pagination > li > span:focus,
.pagination > li > span:hover
{
    color: #1f2428;
    background-color: #eee;
    border-color: #ddd;
}

.pagination > .active > a
{
    color: white;
    background-color: #1f2428 !important;
    border: solid 1px #1f2428 !important;
}

.pagination > .active > a:hover
{
    background-color: #1f2428 !important;
    border: solid 1px #1f2428;
}


</style>
<nav aria-label="Partie Pagination">
  <ul class="pagination">

<?php 
    // Affiche le page par page avec ses liens
    if ($page>0) {
    $precedent=$page-1;
    
    echo "<li class=\"page-item\"><a class=\"page-link\" href=\"".$_SERVER['PHP_SELF']."?page=$precedent\" aria-label=\"Previous\"><span aria-hidden=\"true\">&laquo;</span><span class=\"sr-only\">Previous</span></a></li>";
    // echo "<a href=\"".$_SERVER['PHP_SELF']."?page=$precedent\">PRECEDENT</a>";
    }else{$page=0;}

    $i=0;
    $j=1;

    // if($nb_total>$limit) {
    // while($i<($nb_total/$limit)) {
    // if($i!=$page){echo "<a href=\"".$_SERVER['PHP_SELF']."?page=$i\">$j</a> ";}
    // else { echo "<b>$j</b>";}
    // $i++;$j++;
    // }
    // }

    if($nb_total>$limit) {
    while($i<($nb_total/$limit)) {        
    if($i!=$page){echo "<li class=\"page-item\"><a class=\"page-link\" href=\"".$_SERVER['PHP_SELF']."?page=$i\">$j</a></li>";}
    else {echo "<li class=\"page-item active\"><a class=\"page-link\" href=\"#\">$j<span class=\"sr-only\">(current)</span></a></li>";}
    $i++;$j++;
    }
    }

    if($debut+$limit<$nb_total) {
    $suivant=$page+1;
    echo "<li class=\"page-item\"><a class=\"page-link\" href=\"".$_SERVER['PHP_SELF']."?page=$suivant\" aria-label=\"Next\"><span aria-hidden=\"true\">&raquo;</span><span class=\"sr-only\">Next</span></a></li>";
    // echo "<a href=\"".$_SERVER['PHP_SELF']."?page=$suivant\">SUIVANT</a>";
    }
    echo "</ul></nav>";





// dumpPre($res);exit;




require 'footer.php';