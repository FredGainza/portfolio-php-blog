<?php
// Connexion à la base de données
// Nous utiliserons aussi le gestionnaire d'erreurs
// c'est une bonne pratique d'"utiliser le try et catch

$dbname = '';
$user = 'root';
$pass = '';

try {
    $dbh = new PDO('mysql:host=localhost:3306;dbname=techno_blog;charset=utf8', $user, $pass);
} catch (PDOException $e) {
    print "Erreur ! " . $e-> getMessage() . "<br>";
    die();
}

// $dbname = '';
// $user = 'kopa';
// $pass = 'gccC6!04';

// try {
//     $dbh = new PDO('mysql:host=localhost:3306;dbname=techno_blog;charset=utf8', $user, $pass);
// } catch (PDOException $e) {
//     print "Erreur ! " . $e-> getMessage() . "<br>";
//     die();
// }