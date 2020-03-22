<?php session_start(); ?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v3.8.5">
    <title>Blog sorties EP Techno</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.3/examples/album/">

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css">
    <link type="image/png" rel="icon" href="../images/favicon.png"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans|Roboto&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet"> 
    
    <link rel="stylesheet" href="../css/style.min.css">
    <link rel="stylesheet" href="../css/navbar.css">
    

    <style>
        .iimage{
            width: 100px;
            max-width: 100%;
            height: auto;
        }
    </style>

    <!-- Custom styles for this template -->
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <span class="navbar-brand color-anis mb-0 h1">Actu Techno EP</span>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                <span class="text-light h3 mr-40p navbar-text">Panel d'administration</span>
                <ul class="navbar-nav justify-content-end">
                    <li class="nav-item dropdown">

                        <a class="nav-link dropdown-toggle pr-5" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Accès au blog
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="../blog_index.php?category=deep">Deep</a>
                            <a class="dropdown-item" href="../blog_index.php?category=minimal">Minimal</a>
                            <a class="dropdown-item" href="../blog_index.php?category=hard">Hard</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <div class="row">
        <div class="col-2">
            <div id="navig" class="bg-dark">
                <nav class="navbar navbar-dark pt-3">
                    <ul class="nav nav-tabs flex-column">
                        <li class="nav-item dropdown ">
                            <a class="nav-link dropdown-toggle links_perso" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Articles</a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="?article=ajout">Ajout</a>
                                <a class="dropdown-item" href="#">Suppression</a>
                                <a class="dropdown-item" href="#">Edition</a>
                                <a class="dropdown-item" href="#">Voir</a>
                            </div>
                        </li>
                        <div class="dropdown-divider"></div>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle links_perso" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Commentaires</a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#">Ajout</a>
                                <a class="dropdown-item" href="#">Suppression</a>
                                <a class="dropdown-item" href="#">Edition</a>
                                <a class="dropdown-item" href="#">Voir</a>
                            </div>
                        </li>

                        <div class="dropdown-divider"></div>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle links_perso" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Utilisateurs</a>
                            <div class="dropdown-menu">

                                <a class="dropdown-item" href="#">Ajout</a>
                                <a class="dropdown-item" href="#">Suppression</a>
                                <a class="dropdown-item" href="#">Edition</a>
                                <a class="dropdown-item" href="#">Voir</a>
                            </div>
                        </li>

                        <div class="dropdown-divider"></div>
                        <li class="nav-item">
                            <a class="nav-link links_perso" href="#">Link</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
