<?php
include("adminpageheader.php");
$formStyle = "";
$buttonstyle = "style='display: none;'";
?>

<?php
$required = "required";
$name = "";
$filehtml = "";
$fileInputStyle = "";
$form = "";
$back = "";
$title = "Voeg koorlid toe aan het smoelenboek";

if (isset($_GET["id"])) {
    $stmt = $db->prepare("SELECT * FROM facemap WHERE facemapId = :id");
    $stmt->bindParam(':id', $id);
    $id = filter_input(INPUT_GET, "id");
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        $name = $row["facemapName"];

        // file
        if ($row["facemapUrl"] != null) {
            $filehtml = '<p><img src="../'.$row["facemapUrl"].'" id="facemapimg" > </p>';
        }
        else {
            $filehtml = '<p class="text-info">Geen foto toegevoegd</p>';
        }
        $filehtml .= '<p><a class="btn btn-info" href="#" onclick="edit(this,\'fileToUpload\')">Wijzigen</a></p>';
        $FileUrl = $row["facemapUrl"];


        $form = '<button id="uploadButton" class="btn btn-success btn-xl text-uppercase" type="submit" onclick="checkdatalist(1);">Opslaan</button>';
        $form .= ' <a class="btn btn-danger btn-xl" href="facemapoverview.php?id='.$row["facemapId"].'&action=2">Verwijderen</a>';
    }
    $fileInputStyle = "style='display: none;'";
    $back = ' <div class="mailback"><a href="facemapoverview.php"><i class="fa fa-arrow-left" aria-hidden="true"></i> Terug</a></div>';
    $title = "Bewerk gegevens van koorlid";
    $required = "";
    
}



if (isset($_POST['username']) && (isset($_FILES['fileToUpload']) || isset($_GET["id"])) ) {
    // als er een bestand is geupload een naam is ingevuld
    $username = filter_input(INPUT_POST,'username');
    if (isset($_FILES['fileToUpload']) ) {
        $file = $_FILES['fileToUpload'];
        $type = array("jpg","jpeg");

        //Upload functie aanroepen
        $result = fileUpload($file,$type);
        $fileName = $result[0]; // bestandsnaam voor in de database

        if ($result[1] == 5 && !isset($_GET["id"])) {
            message("info", "Er is geen afbeelding bijgevoegd", "");
        }
        elseif (isset($_GET["id"]) && $result[1] != 1) {
            // als er geen nieuw bestand is geupload oude bestand behouden
            $fileName = $FileUrl;
        }
        else {
            $fileName = $result[0];
            if (isset($_GET["id"])) {
                unlink("../".$FileUrl);
            }
        }
    }

    if ($result[1] === 1 || isset($_GET["id"]) ) {
        // check of uploaden is gelukt, zo ja sql query uitvoeren
        if (!isset($_GET["id"])) {
            $statement = $db->prepare("INSERT INTO facemap(facemapUrl, facemapName) VALUES(:facemapUrl, :facemapName)");
            $statement->execute(array(
                'facemapUrl' => $fileName,
                'facemapName' => $username
            ));
            if  ($statement->rowCount() == 1) {
                // als sql query goed is uitgevoerd een melding geven
                message("success",'Succesvol toegevoegd!',"Deze persoon is succesvol toegevoegd");
                $formStyle = "style='display: none'";
                $buttonstyle = "";
            }
            else {
                message("danger",'Database fout',"Deze persoon is niet toegevoegd");         
            }
        }
        else {
            // persoon updaten
            $statement = $db->prepare("UPDATE facemap SET facemapUrl = :facemapUrl, facemapName = :facemapName WHERE facemapId = :id");
            $id = filter_input(INPUT_GET, "id");
            $statement->execute(array(
                'facemapUrl' => $fileName,
                'facemapName' => $username,
                'id' => $id
            ));
            if  ($statement->rowCount() == 1) {
                // als sql query goed is uitgevoerd een melding geven
                message("success",'Succesvol geupdated',"Deze persoon is succesvol gewijzigd");
                $formStyle = "style='display: none'";
                $buttonstyle = "style='display: none'";
            }
            else {
                message("danger",'Database fout',"Deze persoon is niet geupdated");         
            }
        }
    }
} 
else {
}
?>
<section>   
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="section-heading text-uppercase"><?= $title ?> </h2>
                <?= $back ?>
            </div>
        </div>

        <form method="post" enctype="multipart/form-data">
            <div id="musicform" <?= $formStyle ?> >
                <div class="row">
                    <div class="col md6">
                        <h3>Gegevens</h3>
                        <div class="form-group">
                            <label for="username">Voor- en achternaam</label>
                            <input type="text" class="form-control" id="username" placeholder="naam achternaam" name="username" required value="<?= $name ?>">
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlFile1"> Selecteer een plaatje om toe te voegen:</label>
                            <?= $filehtml ?>
                            <input type="file" class="form-control" name="fileToUpload" id="fileToUpload" accept=".jpeg, .jpg" <?= $required ?> <?= $fileInputStyle ?> ><br><br>
                            <input type="submit" value="Persoon toevoegen" name="submit" class="btn btn-primary btn-xl text-uppercase" <?= $fileInputStyle ?> >
                            <?= $form ?>
                        </div>
                    </div>
                    <div class="col invisibleOnPhone"></div>
                </div>
            </div>
        </form>
        <div id="message"></div>
        <br><br>
        <a id="addMore" class="btn btn-primary btn-xl text-uppercase" href="addmembers.php" <?= $buttonstyle ?> >Voeg nog een koorlid toe</a>
    </div>
</section>

<?= $message ?>

<? require_once 'adminpagefooter.php' ?>
