<?php
include("adminpageheader.php");
$view = new DbHelper();
$formstyle = "";
$buttonstyle = "style='display: none;'";
$username = "huidige account";
$back = "";


if (isset($_GET["id"])) {
    $id = filter_input(INPUT_GET, "id");
    $stmt = $db->prepare("SELECT username FROM user WHERE userId = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    while ($row = $stmt->fetch())
    {
        $username = $row["username"];
    }
    
    $back = ' <div class="mailback"><a href="users.php"><i class="fa fa-arrow-left" aria-hidden="true"></i> Terug</a></div>';
    
}
else {
    $id = null;
}
if(isset ($_REQUEST['editPassword'])){
    $formstyle = "style='display: none;'";
    $buttonstyle = "";
    if($_POST['password'] == $_POST['repeatPassword']){
        $result = $view-> editUser($id);
        if($result == 1) {
            message("success","Uw wachtwoord is gewijzigd","Het nieuwe wachtwoord is opgeslagen");
        }
        else {
            message("danger","Er ging iets verkeerd.","Het wachtwoord is niet gewijzigd");
        }
    }else
    { 
        message("danger","Herhaalde wachtwoord is niet gelijk aan nieuwe wachtwoord","Het wachtwoord is niet gewijzigd");
    }
}
?>    
<section id="login">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="section-heading text-uppercase">Wachtwoord veranderen</h2>
                <?= $back ?>
            </div>
        </div>

        <form method="post">
            <div class="row" <?= $formstyle ?> >
                <div class="col md6" >
                    <h3>Het wachtwoord van <?= $username ?> wijzigen</h3>
                    <p>
                        Nieuw wachtwoord: 
                        <br>
                        <input type="password" class="form-control" name="password" placeholder="Nieuw wachtwoord" required="" autofocus="">
                    </p>
                    <p>
                        Herhaal nieuwe wachtwoord: 
                        <br>
                        <input type="password" class="form-control" name="repeatPassword" placeholder="Herhaal wachtwoord" required="">
                    </p>
                    <p><button type="submit" class="btn btn-primary btn-xl text-uppercase" name="editPassword" >Verander wachtwoord</button></p>
                </div>
                <div  class="col md6 invisibleOnPhone"></div> 
            </div>
            <div class="row">
                <div class="col">     
                    <div id="message"></div>
                    <?= $message ?>
                    <a id="uploadMore" class="btn btn-primary btn-xl text-uppercase" href="editPassword.php" <?= $buttonstyle ?> >Wijzig wachtwoord opnieuw</a>
                </div>
            </div>
        </form>
    </div>
</section>
<?php
    include("adminpagefooter.php");
?>
