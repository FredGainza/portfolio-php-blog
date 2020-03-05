<?php
    if (!isset($_SESSION) || !$_SESSION['admin_access']){
        $_SESSION['errors'] = "Vous n'êtes pas autorisé à accéder à cette partie du site.";
        header('Location: ../index.php');
    }
