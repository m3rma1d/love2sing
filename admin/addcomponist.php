<?php
    require 'header.php';
?>

    <!-- scripts voor uploadformulier -->

    <script src="js/functions.js"></script>

    <?php

$formStyle = "";
$message = "";
$uploadMoreStyle = 'style="display: none;"';
$addMusicStyle = 'style="display: none;"';
$name = "";
$sectionStyle = "";

if (isset($_GET["name"])) {
    $name = $_GET["name"];
    $sectionStyle = 'style="padding: 0px;"';
}


if (isset($_POST["componistName"]) && isset($_POST["componistYearOfBirth"])) {
    $message = "<script>";

    $user= "root";
    $password= "";
    $db = new PDO('mysql:host=localhost;dbname=love2sing', $user, $password);
    
    $stmt = $db->prepare("INSERT INTO componist (componistName, componistYearOfBirth) VALUES(:componistName, :componistYearOfBirth)");
        
    $stmt->bindParam(':componistName', $componistName);
    $stmt->bindParam(':componistYearOfBirth', $componistYearOfBirth);

    $componistName = $_POST["componistName"];
    $componistYearOfBirth = date("Y-m-d",strtotime($_POST["componistYearOfBirth"]));
    
    
    $stmt->execute();
    
    
    if($stmt->rowCount() == 1) {
        $message .= 'message("success", "Componist opgeslagen", "'.$componistName.' is succesvol toegevoegd");';
        
        $formStyle = ' style="display: none;" ';
        $uploadMoreStyle = '';
        
        if (isset($_GET["name"])) {
            $addMusicStyle = "";
        }
    }
    else {
        $message .= 'message("danger", "Componist is niet opgeslagen", "Er is een technishe fout opgetreden");';
    }
    
    
    $message .= "</script>";
    
}
?>










        <section id="componistadd" <?= $sectionStyle ?> >
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="section-heading text-uppercase">Componist toevoegen</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <form id="componistForm" name="addComponist" method="post" onsubmit="sendButton('Componist toevoegen...',true,'addComponist')">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3>Informatie Componist</h3>
                                    <div id="musicform" <?= $formStyle ?> >
                                        <div class="form-group">
                                            Naam
                                            <input class="form-control" id="componistName" name="componistName" type="text" placeholder="Componist naam" required data-validation-required-message="Vul a.u.b een naam in" value="<?= $name ?>">
                                            <p class="help-block text-danger"></p>
                                        </div>
                                        <div class="form-group">
                                            Geboortedatum
                                            <input class="form-control" id="componistYearOfBirth" name="componistYearOfBirth" type="text" placeholder="dd-mm-yyyy" required data-validation-required-message="Vul a.u.b een naam in">
                                            <p class="help-block text-danger"></p>
                                        </div>
                                        
                                        <div class="clearfix"></div>
                                        <div id="success"></div>
                                        <button id="addComponist" class="btn btn-primary btn-xl text-uppercase" type="submit">Voeg componist toe</button>
                                    </div>

                                    <div id="message"></div>
                                    <a id="uploadMore" class="btn btn-primary btn-xl text-uppercase" href="musicupload.php" <?= $uploadMoreStyle ?> >Voeg nog een componist toe</a>
                                    <br><br>
                                    <a id="addMuic" class="btn btn-primary btn-xl text-uppercase" href="javascript:window.top.location.reload();" <?= $addMusicStyle ?> >Muziekstuk toevoegen afronden</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <?= $message ?>


            <?php
    require 'footer.php';
?>
