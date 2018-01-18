<?php
// laad functies in, en start sessie
require '../includes/functions.php';
// controleer of gebruiker admin rechten heeft, stuur hem anders terug naar login
if (!adminpage()) {
    header('Location: ../login.php?redirect='.$_SERVER[REQUEST_URI].'');
}
?>

<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Love2Sing</title>

        <!-- Bootstrap core CSS -->
        <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom fonts for this template -->
        <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>

        <!-- Plugin CSS -->
        <link href="../vendor/magnific-popup/magnific-popup.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="../css/creative.min.css" rel="stylesheet">
        <link href="../css/style.css" rel="stylesheet">    
        <link href="../css/index.css" rel="stylesheet">
        
        <script src="../js/functions.js"></script>

        <style>
            @media only screen and (max-width: 760px){
                section {
                    padding: 0px;
                }
            }
        </style>
    </head>
    <body class="adminbody" id="page-top">
    <nav id="mainNav" style="display: none;"></nav>
