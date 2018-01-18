<?php
require 'header.php';
?>
<section>
    <div class="container">
        <h2 class="section-heading text-uppercase text-center">Schrijf in het gastenboek</h2>
        <hr class="my-4">
        <div id="form-div">
            <form class="form" id="form1" method="POST">

                <p class="name">
                    <input name="titel" type="text"
                           class="validate[required,custom[onlyLetter],length[0,100]] feedback-input"
                           placeholder="Titel" id="name" required/>
                </p>

                <p class="text">
                        <textarea name="bericht" class="validate[required,length[6,300]] feedback-input" id="comment"
                                  placeholder="Bericht" required></textarea>
                </p>


                <div class="submit">
                    <button type="submit" name="verzenden"
                            onclick="sendButton('Verzenden..', true, 'button-purple');" id="button-purple">Verzend
                        bericht
                    </button>
                    <div class="ease"></div>
                </div>
            </form>
            <div id="message"></div>
        </div>
    </div>
</section>

<?php
//script beveiligd tegen XSS injecties dmv htmlentities in combinatie met ENT_QUOTES
//database is al geconnect in functions.php

if (isset($_POST['titel']) && isset($_POST["bericht"])) {
    $valid = true;
    $title = htmlentities(trim($_POST['titel'], ENT_QUOTES));
    //checken of er een titel is ingevuld
    if (empty($title)) {
        $valid = false;
    }
    $gbmessage = htmlentities(trim($_POST['bericht'], ENT_QUOTES));
    //checken of er een bericht is ingevuld
    if (empty($gbmessage)) {
        $valid = false;
    }
    $date = date("Y-m-d H:i:s");
    //automatisch eerste letter hoofdletter maken
    $title = ucfirst(strtolower($title));
    $gbmessage = ucfirst(strtolower($gbmessage));

    //als de velden gecheckt zijn de data in de database gooien
    if ($valid == true) {


        //veilige insert in de tabel dmv prepare en bindParam, daardoor geen string escape meer nodig
        $stmt = $db->prepare("INSERT INTO guestbook (guestbookTitle, guestbookMessage, guestbookDate) VALUES(:title, :message, :date)");
        $stmt->bindParam(":title", $title);
        $stmt->bindParam(":message", $gbmessage);
        $stmt->bindParam(":date", $date);
        $stmt->execute();


//verstuur mail voor het goedkeuren van een gastenboekbericht

        //echo "<div class='guestbook-text'>Uw verzoek om een bericht te plaatsen in het gastenboek is verstuurd! Als deze wordt geaccepteerd, zal uw bericht in het gastenboek verschijnen.</div>";


        //verstuur mail voor het goedkeuren van een gastenboekbericht


        $id = $db->lastInsertId(); // krijg het id van het zojuist geinserte gastenboek item

        $subject = "Nieuw gastenboek bericht";
        $emailmessage = "
<!DOCTYPE html>
<html lang='en'>

    <body>
        <p>Er is een nieuw verzoek voor een bericht in het gastenboek:</p>
        <h3>" . $title . "</h3>
        <p>" . $gbmessage . " <br>
        " . $date . "</p>
        <p>Wilt u dit bericht toevoegen aan het gastenboek of weigeren? Als u kiest voor weigeren, wordt het bericht niet in het gastenboek geplaats.</p>

        <a href='http://alex-dehaan.nl/KBS/love2sing/admin/?url=guestbookposts.php?toevoegen=true%26id=" . $id . "' id='button-purple'>Toevoegen</a>
        <a href='http://alex-dehaan.nl/KBS/love2sing/admin/?url=guestbookposts.php?weigeren=true%26id=" . $id . "' id='button-purple'>Weigeren</a>

    </body>

</html>
";
        $replyTo = null; //persoon heeft geen mailadres moeten invoeren en krijgt dus ook geen bericht van toevoeging of weigering


        // Als het bericht inserted is én de mail is verstuurd, goedmelding geven   
        if (sendMail($subject, $emailmessage, $replyTo) == 1) {
            message("success", "Uw verzoek om een bericht te plaatsen in het gastenboek is verstuurd!", "Als deze wordt geaccepteerd, zal uw bericht in het gastenboek verschijnen.");
            // Als er iets fout is gegaan met het inserten of het versturen van de mail, foutmelding geven
        } else {
            message("danger", "Er is iets fout gegaan!", "Probeer het opnieuw.");
        }

    }

}

?><?= $message ?>

<?php

require 'footer.php';
?>
