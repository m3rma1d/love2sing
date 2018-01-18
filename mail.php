<?php
// contact formulier versturen
require("includes/functions.php");
if (isset($_POST['contactName']) && isset($_POST['contactEmail']) && isset($_POST['contactMessage'])) {   
    $contactName = filter_input(INPUT_POST, 'contactName');
    $contactEmail = filter_input(INPUT_POST, 'contactEmail');
    $contactMessage = htmlentities(trim(nl2br(filter_input(INPUT_POST, 'contactMessage'))));

    // mail content genereren
    $message = "
    <h1 style='font-size: 22px;'>Het contact formulier is ingevuld door ".$contactName."</h1>
    
    <h2 style='font-size: 18px;'>Het volgende bericht is gestuurd:</h2>
    
    <p style='font-size: 14px;'>". $contactMessage."</p>
    <div style='font-size: 14px;'>
        <h2 style='font-size: 18px;'>Gegevens:</h2>
        <b>Naam: </b>".$contactName."<br>
        <b>E-mail: </b>".$contactEmail."<br>
        <b>Verstuurd op: </b>".date("d-m-Y H:i:s")."
    </div>"; 
    $result = sendMail("Contactformulier van koor",$message,$contactEmail);
    
    if ($result == 1) {
        // als mail verstuurd is de gegevens in de db zetten
        $stmt = $db->prepare("INSERT INTO contact (email, message, name, date) VALUES (:email, :message, :name, :date)");
        $stmt->bindParam(':email',$contactEmail);
        $stmt->bindParam(':message', $contactMessage);
        $stmt->bindParam(':name', $contactName);
        $stmt->bindParam(':date', $date2);
        $date2 = date("Y-m-d H:i:s");
        $stmt->execute();
                
        if ($stmt->rowCount() == 1) {
             $result = 1;
        }
        else {
            $result = 0;
        }
    }
    
    echo $result;
}
else {
    echo "2";
}
// de index.php checkt of er 0/1/2 wordt weergegeven, 1: succes, 2: faal, 0: database fail
?>