<?php
    session_start();
    unset($_SESSION['log_id']);
    unset($_SESSION['log_firstname']);
    unset($_SESSION['log_lastname']);
    unset($_SESSION['user_access']);
    unset($_SESSION['admin_access']);
    unset($_SESSION['avatar']);
    header('Location: ../index.php');