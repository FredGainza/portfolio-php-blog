<?php
require 'app/bdd.php';
function dumpPre($x)
{
    echo '<pre>';
    var_dump($x);
    echo '</pre>';
}

function printPre($x)
{
    echo '<pre>';
    print_r($x);
    echo '</pre>';
}

// $prenom = valid_donnees($_POST["prenom"]);
function valid_donnees($donnees)
{
    $donnees = trim($donnees);
    $donnees = stripslashes($donnees);
    $donnees = htmlspecialchars($donnees);
    return $donnees;
}
//nav>ul>li*5>a{menu$}
// header('Location: ' .$_SERVER['HTTP_REFERER']);

/* Générateur de Slug (Friendly Url) : convertit un titre en une URL conviviale.*/
function slugify($string, $delimiter = '-')
{
    $oldLocale = setlocale(LC_ALL, '0');
    setlocale(LC_ALL, 'en_US.UTF-8');
    $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
    $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
    $clean = strtolower($clean);
    $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
    $clean = trim($clean, $delimiter);
    setlocale(LC_ALL, $oldLocale);
    return $clean;
}

function tronquer_texte($texte, $nbchar)
{
    return (strlen($texte) > $nbchar ? substr(
        substr($texte, 0, $nbchar),
        0,
        strrpos(substr($texte, 0, $nbchar), " ")
    ) . " (...)" : $texte);
}

function paginationDefine($requete, $limit)
{
    $dbh = new PDO('mysql:host=localhost:3306;dbname=techno_blog;charset=utf8', 'root', '');
    $page = isset($_GET['page']) ? $_GET['page'] : 0; 
    $debut = '';
    // Prepare le requete MySql
    // $requete = "SELECT * from $table";
    $req = $dbh->prepare($requete);
    $req->execute();
    $ret = $req->fetchAll(PDO::FETCH_OBJ);
    // dumpPre($ret);exit;
    // Variable nombre d'enreg par page
    // $limit=5;
    // echo $page;exit;


    if($debut==""){$debut=0;}else{$debut=$page*$limit;}
    
    // Compte le nombre de champ
    $nb_total=$req->rowCount();
    // Requete
    $limite = $dbh->prepare("$requete limit $debut,$limit");


    //Affichage le contenu de votre table
    //avec une limite, dans l'exemple $limit est à 4
    $limit_str = "LIMIT ". $page * $limit .",$limit";

    $result = $dbh->prepare("$requete
                          ORDER BY id 
                          ASC $limit_str");

    $result->execute();

    return $res = $result->fetchAll(PDO::FETCH_OBJ);
}

function paginationAffiche()
{
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
}