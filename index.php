<?php
$isHomepage = true;
require 'header.php';
?>
<?php
// tekst wijzigen als beheerder is ingelogd en gewijzigde tekst verstuurd
if (isset($_POST) && adminpage()) {
    foreach ($_POST as $key => $value) {
        $content = $value;
        $stmt = $db->prepare("UPDATE text SET text = :text WHERE id = :id");
        $stmt->bindParam(':text',$content);
        $stmt->bindParam(':id', $key);
        $stmt->execute();
    }
}

// tekst voor de pagina ophalen h=uit de database
$stmt = $db->prepare("SELECT * FROM text");
$stmt->execute();

// pagina bewerken voor admin
$script = "";
while ($row = $stmt->fetch())
{
    if (adminpage() && isset($_GET["edit"])) {
        // voor elke tekst op pagina een textarea maken
        $height = ceil(strlen($row[1]) / 55) * 18 + 15;
        $text[$row[0]] = "<form method='post'><textarea style='height: ".$height."px;' class='edittext' name='".$row[0]."'>".$row[1]."</textarea><br><button class='btn btn-secondary btn-md'  type='submit'>Oplslaan</button></form>";
        $script = '<script>$("a").removeAttr("href"); $("a").removeAttr("onclick"); $("#sendMessageButton").attr("type","button");
</script>';
        
    }
    else {
        // tekst voorzien van <br> i.p.v. \n en \l
        $text[$row[0]] = nl2br($row[1]);
    }
}
?>
<!-- Navigation -->

<header class="masthead text-center text-white d-flex">
    <div class="container my-auto">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <h1 class="text-uppercase">
                    <strong><?= $text[14] ?></strong>
                </h1>
                <hr>
            </div>
            <div class="col-lg-8 mx-auto">
                <a class="btn btn-primary btn-xl js-scroll-trigger" href="#about"><?= $text[15] ?></a>
            </div>
        </div>
    </div>
</header>

<section class="bg-primary" id="about">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h2 class="section-heading text-white  text-center"><?= $text[2] ?></h2>
                <hr class="light my-4">
                <p class="text-faded mb-4"><?= $text[3] ?>
                </p>

                <h2 class="section-heading text-white  text-center"><?= $text[4] ?></h2>
                <hr class="light my-4">
                <p class="text-faded mb-4"><?= $text[5] ?>
                </p>
            </div>
        </div>
    </div>
</section>

<section id="agenda">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2 class="section-heading text-uppercase"><?= $text[6] ?></h2>
                <hr class="my-4">
            </div>
        </div>
    </div>

    <div class="container" style="text-align:center;">
        <iframe id="googleCalendarIframe" src="https://calendar.google.com/calendar/embed?showTitle=0&amp;showPrint=0&amp;showTabs=0&amp;showCalendars=0&amp;showTz=0&amp;height=450&amp;wkst=1&amp;hl=nl&amp;bgcolor=%23ffffff&amp;src=kmhu140epkhr7rq6fb2i5t837c%40group.calendar.google.com&amp;color=%23691426&amp;ctz=Europe%2FAmsterdam" style="border-width:0" frameborder="0" scrolling="no"></iframe>
    </div>
</section>

<section class="bg-dark text-white">
    <div class="container text-center">
        <h2 class="mb-4"><?= $text[7] ?></h2>
        <a class="btn btn-light btn-xl sr-button" href="photoalbum.php"><?= $text[8] ?></a>
    </div>
</section>

<!-- scripts voor contactformulier -->


<script>
    function contactForm(buttonText, pointerStyle, loading, buttonid) {
        sendButton(buttonText, loading, buttonid);
        document.getElementById('name').style.pointerEvents = pointerStyle;
        document.getElementById('email').style.pointerEvents = pointerStyle;
        document.getElementById('contactMessage').style.pointerEvents = pointerStyle;
    }

    var firstTime = true;

    function mail() {
        if (!firstTime) {
            var iframeContent = document.getElementById('contactIframe').contentWindow.document.body.innerHTML;
            if (iframeContent == "1") { // 1: mail met contactgegevens is verzonden
                document.getElementById("contactForm").style.display = "none"; // contact formulier onzichtbaar maken
                message("success", "Bericht verzonden", "Uw bericht is succesvol verzonden, wij reageren z.s.m.");
            } else if (iframeContent == "0") { // 0: mail met contactgegevens is niet verzonden
                sendButton('Verstuur bericht opnieuw', 'auto',false);
                message("warning", "Bericht verzenden mislukt", "Probeer het opnieuw");
            } else {
                sendButton('Verstuur bericht opnieuw', 'auto', false);
                message("danger", "Bericht verzenden mislukt", "Er is een technishe fout opgetreden");
            }
        } else {
            firstTime = false;
        }
    }    
</script>

<section id="contact">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h2 class="section-heading text-uppercase"><?= $text[9] ?></h2>
                <hr class="my-4">
                <h3 class="section-subheading text-muted"><?= $text[10] ?><br><br></h3>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <form id="contactForm" name="sentMessage" method="post" action="mail.php" target="contact" onsubmit="contactForm('Bericht versturen...','none',true,'sendMessageButton')">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input class="form-control feedback-input" id="name" name="contactName" type="text" placeholder="Uw naam" required data-validation-required-message="Vul a.u.b een naam in">
                                <p class="help-block text-danger"></p>
                            </div>
                            <div class="form-group">
                                <input class="form-control feedback-input" id="email" name="contactEmail" type="email" placeholder="E-mailadres" required data-validation-required-message="Vul a.u.b een e-mailadres in">

                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <textarea class="form-control feedback-input contacttextarea" name="contactMessage" id="comment" placeholder="Uw bericht" required data-validation-required-message="Vul a.u.b een bericht in"></textarea>

                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-lg-12 text-center">
                            <div id="success"></div>
                            <button id="sendMessageButton" class="btn btn-primary btn-xl text-uppercase" type="submit"><?= $text[11] ?></button>
                        </div>
                    </div>
                </form>

                <div id="message"></div>

                <iframe name="contact" src="mail.php" id="contactIframe" onload="mail();"></iframe>
                <div class="col-lg-12 text-center">
                    <h3 class="section-subheading text-muted"><?= $text[12] ?><br></h3>
                </div>
                <div class="row">
                    <div class="col align-self-center">
                        <p class="text-center"><?= $text[13] ?></p>

                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2437.8307614435207!2d5.60690031531079!3d52.33721625751214!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c6316035c49a25%3A0x253fdf15a0417bbd!2sMultifunctioneel+zalen-+en+vergadercentrum+&#39;de+Roef&#39;!5e0!3m2!1sen!2snl!4v1511981850025"  frameborder="0" id="googleMapsIframe" style="border:0" allowfullscreen></iframe>
                    </div>
                </div>



            </div>
        </div>
    </div>
</section>


<?php
    require 'footer.php';
?>
<?= $script ?>