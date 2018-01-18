<?php
require '../includes/functions.php';
// controleer of gebruiker admin rechten heeft, stuur hem anders terug naar login pagina
if (!adminpage()) {
    header('Location: ../login.php?redirect='.$_SERVER[REQUEST_URI].'');
}
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Beheer</title>
        <!-- Bootstrap core CSS-->
        <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <!-- Custom fonts for this template-->
        <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <!-- Page level plugin CSS-->
        <link href="../vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
        <!-- Custom styles for this template-->
        <link href="../css/sb-admin.css" rel="stylesheet">
        <link href="../css/editPassword.css" rel="stylesheet">
        <link href="../css/style.css" rel="stylesheet">
        <!-- Bootstrap core JavaScript-->
        <script src="../vendor/jquery/jquery.min.js"></script>
        <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- Core plugin JavaScript-->
        <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
        <script src="../js/functions.js"></script>

    </head>

    <body class="fixed-nav sticky-footer bg-dark" id="page-top">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
            <a class="navbar-brand" href="#">Love2Sing</a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation" id="menuButton"></button>

            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">
                        <a class="nav-link" href="home.php" target="iframe" onClick="viewName(this);">
                            <i class="fa fa-fw fa-home"></i>
                            <span class="nav-link-text">Home</span>
                        </a>
                    </li>
                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Charts">
                        <a class="nav-link" href="https://calendar.google.com" target="_blank" >
                            <i class="fa fa-fw fa fa-calendar"></i>
                            <span class="nav-link-text">Kalender</span>
                            <i class="fa fa-external-link floatright" aria-hidden="true"></i>
                        </a>
                    </li>
                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Components">
                        <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseComponents" data-parent="#exampleAccordion" id="componentsParent">
                            <i class="fa fa-fw fa-wrench"></i>
                            <span class="nav-link-text">Onderdelen</span>
                        </a>
                        <ul class="sidenav-second-level collapse" id="collapseComponents">
                            <li>
                                <a href="musicupload.php" target="iframe" onClick="viewName(this,'componentsParent');">Uploaden muziek</a>
                            </li>
                            <li>
                                <a href="addmembers.php" target="iframe" onClick="viewName(this,'componentsParent');">Persoon toevoegen smoelenboek</a>
                            </li>
                            <li>
                                <a href="uploadfoto.php" target="iframe" onClick="viewName(this,'componentsParent');">Foto's toevoegen fotoalbum</a>
                            </li>
                            <li>
                                <a href="register.php" target="iframe" onClick="viewName(this,'componentsParent');">Gebruikersaccount toevoegen</a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Components">
                        <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseOverview" data-parent="#exampleAccordion" id="overviewParent">
                            <i class="fa fa-fw fa-table"></i>
                            <span class="nav-link-text">Overzichten</span>
                        </a>
                        <ul class="sidenav-second-level collapse" id="collapseOverview">
                            <li>
                                <a href="musicuploads.php" target="iframe" onClick="viewName(this,'overviewParent');">Muziek</a>
                            </li>
                            <li>
                                <a href="facemapoverview.php" target="iframe" onClick="viewName(this,'overviewParent');">Smoelenboek</a>
                            </li>
                            <li>
                                <a href="photoalbumoverview.php" target="iframe" onClick="viewName(this,'overviewParent');">Fotoalbum</a>

                            </li>
                            <li>
                                <a href="users.php" target="iframe" onClick="viewName(this,'overviewParent');">Gebruikers</a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Components">
                        <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseMessages" data-parent="#exampleAccordion" id="messageParent">
                            <i class="fa fa-fw fa-envelope"></i>
                            <span class="nav-link-text">Berichten</span>
                        </a>
                        <ul class="sidenav-second-level collapse" id="collapseMessages">
                            <li>
                                <a href="guestbookposts.php" target="iframe" onClick="viewName(this,'messageParent');" id="guestbook">Gastenboek</a>
                            </li>
                            <li>
                                <a href="contactformposts.php" target="iframe" onClick="viewName(this,'messageParent');" id="contactform">Contact formulier</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Components">
                        <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseText" data-parent="#exampleAccordion" id="textParent">
                            <i class="fa fa-fw fa-pencil"></i>
                            <span class="nav-link-text">Tekst wijzigen</span>
                        </a>
                        <ul class="sidenav-second-level collapse" id="collapseText">
                            <li>
                                <a href="../index.php?edit=true" target="iframe" onClick="viewName(this,'textParent');">Homepagina</a>
                            </li>

                        </ul>
                    </li>

                </ul>
                <?php
                // 3 recentste meldingen ophalen
                $stmt = $db->prepare("SELECT * FROM ( (SELECT c.contactid AS id, c.email AS title, c.message AS content, c.date AS date, 'contact' as tableName, c.name AS name FROM contact c WHERE c.contactRead = 0) UNION ALL (SELECT g.guestbookId AS id, g.guestbookTitle AS title, g.guestbookMessage AS content, g.guestbookDate AS date, 'gastenboek' as tableName , 'null' AS name FROM guestbook g WHERE g.guestbookRead = 0) ) results ORDER BY date DESC ");
                $stmt->execute();

                $content = '';
                $i = 0;
                while($row = $stmt->fetch()){
                    if ($i < 3) {
                        if (strlen($row["content"]) > 250) {
                            $row["content"] = substr($row["content"], 0, 250). "... <span class='small text-info'>klik voor volledig bericht</span>";
                        }
                        if ($row["tableName"] == "gastenboek") {
                            $content .= '<a class="dropdown-item" href="guestbookposts.php?id='.$row["id"].'" target="iframe" onClick="viewName(document.getElementById(\'guestbook\'),\'messageParent\'); notification(-1); this.remove();">
                                <span class="text-warning">
                                    <strong>
                                        Nieuw '.$row["tableName"].' bericht</strong>
                                </span>
                                <span class="small float-right text-muted">'.$row["date"].'</span>
                                <div class="dropdown-message small"><h5>'.$row["title"].'</h5>'.$row["content"].'</div>
                            </a>';

                        }
                        else {
                            $content .= '<a class="dropdown-item" href="contactformposts.php?id='.$row["id"].'" target="iframe" onClick="viewName(document.getElementById(\'contactform\'),\'messageParent\'); notification(-1); this.remove();">
                                <span class="text-warning">
                                    <strong>
                                        Nieuw '.$row["tableName"].' bericht</strong>
                                </span>
                                <span class="small float-right text-muted">'.$row["date"].'</span>
                                <div class="dropdown-message small"><h5>Van:  '.$row["name"].'</h5><b>Bericht:</b><br>'.$row["content"].'<br>
                                <b>Email adres: </b>'.$row["title"].'</div>
                            </a>';

                        }
                        $i++;
                    }
                }

                $count = $stmt->rowCount(); 
                if ($count == 0) {
                    $count = "";
                }
                ?>
                <ul class="navbar-nav ml-auto">

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle mr-lg-2" id="alertsDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <i class="fa fa-fw fa-bell"></i>

                            <?php 
                            if ($count != 0) {
                                echo '<span class="d-lg-none" id="notifications">Meldingen ';
                                echo '<span class="badge badge-pill badge-warning">'. $count.' berichten</span>';   
                                echo '</span>';
                            }
                            else {
                                echo '<span class="d-lg-none" id="notifications">Meldingen ';
                                echo '<span class="badge badge-pill badge-info">Geen nieuwe berichten</span>';   
                                echo '</span>';
                                $count = 0;
                            }
                            ?>


                            <span class="indicator text-warning d-none d-lg-block" id="messagecount">
                                <?= $count ?>
                            </span>
                        </a>
                        <div class="dropdown-menu  messageview" aria-labelledby="alertsDropdown">
                            <?php 
    if ($count != 0) {
        echo '<h6 class="dropdown-header">Nieuwe berichten:</h6>';
        echo '<div class="dropdown-divider"></div>';
        echo  $content;
    }
                                else {
                                    echo '<h6 class="dropdown-header">Geen nieuwe berichten</h6>';
                                }
                            ?>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="modal" data-target="#exampleModal">
                            <i class="fa fa-fw fa-sign-out"></i>Terug naar website</a>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="content-wrapper">
            <div class="container-fluid">
                <!-- Breadcrumbs-->
                <ol class="breadcrumb" id="pageName">
                    <li class="breadcrumb-item"><a href="home.php" target="iframe" onClick="viewName();" >Love2Sing</a></li>                    
                    <li class="breadcrumb-item active" ><i class="fa fa-fw fa-home"></i><span class="nav-link-text">Home</span></li>
                </ol>

                <script>
                    function viewName(element = null, parentid = null) {
                        var content = '<li class="breadcrumb-item"><a href="home.php" target="iframe"  onClick="viewName();" >Love2Sing</a></li>';
                        if (parentid !== null) {
                            var parent = document.getElementById(parentid);
                            content += "<li class='breadcrumb-item'>" + parent.innerHTML + "</li>";
                        }
                        if (element !== null) {
                            content += "<li class='breadcrumb-item'>" + element.innerHTML + "</li>";
                        }
                        else {
                            content += '<li class="breadcrumb-item"><i class="fa fa-fw fa-home"></i><span class="nav-link-text">Home</span></li>';
                        }


                        document.getElementById("pageName").innerHTML = content;
                        if ($(window).width() < 992) {
                            $("#menuButton").click();
                            $('.tooltip').remove();
                        }                    
                    }

                    var number = <?= $count ?>;
                    function notification(change) {
                        var notification = document.getElementById("notifications");
                        var notificationpc = document.getElementById("messagecount");
                        var status = "";  

                        number += change;
                        if (number > 0) {
                            status = "warning";
                            if (notificationpc.classList.contains('text-info')) {
                                notificationpc.classList.remove('text-info');
                                notificationpc.classList.add('text-warning');
                            }
                        }
                        else {
                            status = "info";
                            number = 0;
                            if (notificationpc.classList.contains('text-warning')) {
                                notificationpc.classList.add('text-info');
                                notificationpc.classList.remove('text-warning');
                            }
                        }
                        notification.innerHTML = 'Meldingen <span class="badge badge-pill badge-' + status + '">'+ number + ' berichten</span>';
                        notificationpc.innerHTML = number;
                    }
                </script>

                <?php 
                    if (isset($_GET["url"])) {
                        $url = filter_input(INPUT_GET, "url");
                    }
                    else {
                        $url = "home.php";        
                    }
                ?>
                <iframe src="<?= $url ?>" id="adminiframe" name="iframe"></iframe>

                <footer class="sticky-footer">
                    <div class="container">
                        <div class="text-center">
                            <small>Copyright © Great Minds&#153; 2017</small>
                        </div>
                    </div>
                </footer>
                <!-- Scroll to Top Button-->
                <a class="scroll-to-top rounded" href="#page-top">
                    <i class="fa fa-angle-up"></i>
                </a>
                <!-- Logout Modal-->
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Terug naar de home page</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">Klik op "keer terug" om weer naar de website te gaan.</div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Annuleren</button>
                                <a class="btn btn-primary" href="../">Keer terug</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

</html>
