<?php
include("adminpageheader.php"); 
//als de knop word ingedrukt
$formstyle = "";
$buttonstyle = "style='display: none;'";
if (isset($_POST['text'])) {
    //Upload functie aanroepen
    $file = $_FILES['jpeg'];
    $type =  array ("jpg", "jpeg");
    $result = fileUpload($file,$type);
    $imgurl = $result[0];

    $formstyle = "style='display: none;'";
    $buttonstyle = "";
    //Beschrijving foto
    $text = $_POST['text'];
    if ($result[1] == 1) {
        // voeg foto toe aan db als foto geupload is
        $sql = "INSERT INTO photoalbum(photoalbumUrl, photoalbumDescription) VALUES (:imgurl, :text)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":imgurl", $imgurl);
        $stmt->bindParam(":text", $text);
        $stmt->execute();
        if ($stmt->rowCount()== 1) {
            message("Success","Opgeslagen in database", ""); 
        }
        else {
            message("danger","Niet opgeslagen in database", "");
        }
    }
    else {
        message("danger","Niet opgeslagen in database", "");
    }
}
?>
<section id="musicupload">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="section-heading text-uppercase">Upload foto</h2>
            </div>
        </div>
        
        <form name="uploadPhoto" method="post" enctype="multipart/form-data">
            <div class="row" <?= $formstyle ?>>
                <div class="col md6" >
                    <h3>Informatie foto</h3>
                    <p>
                        Titel
                        <br>
                        <input class="form-control" id="title" name="text" type="text" placeholder="Beschrijving van de foto" required data-validation-required-message="Vul a.u.b een beschrijving in">
                    </p>
                    <p class="help-block text-danger"></p>

                    <h3>Foto</h3>
                    <p>
                        Foto
                        <br>
                        <input class="form-control" id="jpeg" name="jpeg" type="file" placeholder="Beschrijving van de foto" accept=".jpeg, .jpg">
                    </p>
                    <p class="help-block text-danger"></p>
                    <p>
                        <button type="submit" class="btn btn-primary btn-xl text-uppercase" name="editPassword" >Uploaden</button>
                    </p>    

                </div>
                <div  class="col md6 invisibleOnPhone"></div>
            </div>
            <div class="row">
                <div class="col">     
                    <div id="message"></div>
                    <?= $message ?>
                    <a id="uploadMore" class="btn btn-primary btn-xl text-uppercase" href="uploadfoto.php" <?= $buttonstyle ?> >Upload nog een foto</a>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- melingen weergeven -->


<?php
    include("adminpagefooter.php");
?>