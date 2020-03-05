<?php
 // Base de données
 /* 
    Dans ce fichier nous allons encoder un mot de passe avec la 
    fonction password_hash()
    https://www.php.net/manual/fr/function.password-hash.php
    On utilisera l'algorithme BCRYPT pour encoder le mot de passe 
    dans une chaine de 60 caractères
 */

 // J'ai un utilisateur
 $user = ['email' => 'j@ime.php', 'password' => 'PhpCfun'];

 // J'encode le mot de passe
 // J'effectue un var_dump

 // var_dump(password_hash($user['password'], PASSWORD_BCRYPT));
password_hash($user['password'], PASSWORD_BCRYPT);
// $2y$10$mq4wLA8XFofK3z33Nu/jmutP5Si03AF45ydGc6Ynci50I0UycZ8Ae

// Je stocke le nouveau mot de passe dans l'utilisateur
$user['password'] = '$2y$10$mq4wLA8XFofK3z33Nu/jmutP5Si03AF45ydGc6Ynci50I0UycZ8Ae';
var_dump($user);

// var_dump($user);
/* 
 Le tableau user est désormais comme suit :
 $user
    email       = j@ime.php
    password    = $2y$10$mq4wLA.XFofK3z33Nu/jmutP5Si03AF45ydGc6Ynci50I0UycZ8Ae
Attention cette clé ne sera pas la même que celle ci si on le refait
*/
