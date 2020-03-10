<?php
session_start();
header("Refresh: 3;URL=../index.php");

// Détruit toutes les variables de session
$_SESSION = array();

// Finalement, on détruit la session.
session_destroy();
header('Location:../index.php?disconnect=ok');
exit;
