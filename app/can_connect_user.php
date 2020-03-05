<?php
    if (!isset($_SESSION) || $_SESSION['user_access'] == false){
        $_SESSION['errors'] = "Vous n'êtes pas autorisé à accéder au blog.";
        header('Location: index.php');
    }
