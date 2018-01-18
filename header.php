<?php
require 'includes/functions.php';
?>


<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Love2Sing</title>

        <!-- Bootstrap core CSS -->
        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom fonts for this template -->
        <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>

        <!-- Plugin CSS -->
        <link href="vendor/magnific-popup/magnific-popup.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="css/creative.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">    
        <link href="css/index.css" rel="stylesheet">

        <script src="js/functions.js"></script>

    </head>

    <body id="page-top">

        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
            <div class="container">
                <a class="navbar-brand js-scroll-trigger" href="index.php">Love2Sing</a>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ml-auto">
                        <?php
                        if (!isset($isHomepage)) {
                            $pageUrl = "index.php";
                        }
                        else {
                            $pageUrl = "";
                        }
                        ?>

                        <li class="nav-item">
                            <a class="nav-link js-scroll-trigger" href="<?= $pageUrl ?>#about">Over ons</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link js-scroll-trigger" href="<?= $pageUrl ?>#agenda">Agenda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link js-scroll-trigger" href="photoalbum.php">Fotoalbum</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link js-scroll-trigger" href="<?= $pageUrl ?>#contact">Contact</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link js-scroll-trigger" href="viewGuestbook.php">Gastenboek</a>
                        </li>
                        <?php 

    //dit gedeelte kunnen alleen de gebruikers zien            
    if(userpage()){
        echo '<li class="nav-item">
                        <a class="nav-link js-scroll-trigger" href="music.php">Muziek</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link js-scroll-trigger" href="facemap.php">Smoelenboek</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link js-scroll-trigger" href="logout.php">Log uit</a>
                    </li>';
    }
                               //dit gedeelte kan alleen de beheerder zien
                               elseif(adminpage()){
                                   echo '<li class="nav-item">
                        <a class="nav-link js-scroll-trigger" href="music.php">Muziek</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link js-scroll-trigger" href="facemap.php">Smoelenboek</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link js-scroll-trigger" href="admin/">Beheer</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link js-scroll-trigger" href="logout.php">Log uit</a>
                    </li>';
                               }
                               else{
                                   echo '<li class="nav-item">
                        <a class="nav-link js-scroll-trigger" href="login.php">Login</a>
                    </li>';
                               }
                        ?>

                    </ul>
                </div>
            </div>
        </nav>
