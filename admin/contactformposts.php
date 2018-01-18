<?php
include("adminpageheader.php"); 
$messagefield = '<div id="message"></div>';
if (isset($_POST["action"]) && isset($_POST["id"])) {
    $status = filter_input(INPUT_POST, "action");
    $id = filter_input(INPUT_POST, "id");
    if ($status == 2) {
        $stmt = $db->prepare("DELETE FROM contact WHERE contactid = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            message("success","Er is een contact bericht verwijderd","Succesvol verwijderd");
            header('Location: contactformposts.php');
        }
        else {
            message("danger","Verwijderen mislukt","Er is een technische fout opgetreden");
        }
    }
    else {
        $stmt = $db->prepare("UPDATE contact SET contactRead = :status WHERE contactid = :id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            message("success","De status van dit bericht is succesvol bijgewerkt","");
        }
        else {
            message("danger","Updaten mislukt","Er is een technische fout opgetreden");
        }
    }
}



$tablestyle = "";
$viewmail = "";
$table = "";
$mail = "";
$postedid = null;

if (isset($_GET["id"])) {
    $id = filter_input(INPUT_GET, "id");
    $tablestyle = "style = 'display: none'";
    $viewmail = "style = 'display: block'";
    $messagefield = "";
    $stmt = $db->prepare("SELECT * FROM contact WHERE contactid = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    while ($row = $stmt->fetch()) {
        $mail .= '<div class="mailback"><a href="contactformposts.php"><i class="fa fa-arrow-left" aria-hidden="true"></i> Terug</a></div><br>'; 
        $mail .= '<div id="message"></div>';
        $mail .= '<h1>Van '.$row["name"].'</h1>';
        $mail .= ' <p>';
        $mail .= '<b>Antwoord naar: </b>'.$row["email"].'';
        $mail .= '<br>';
        $mail .= '<b>Ontvangen op: </b>'.$row["date"].'';
        $mail .= '</p>';
        $mail .= '<h2>Bericht:</h2>';
        $mail .= '<p>';
        $mail .= $row["message"];
        $mail .= '</p><br>';
        $mail .= '<form method="post">';     
        $mail .= '<h2 id="react">Reageer:</h2>';
        $mail .= 'Titel: <br><input type="text" name="title" class="form-control" value="Reactie op bericht in contact formulier van love2sing"><br>';     
        $mail .= 'Tekst: <br><textarea class="mailtextarea form-control" name="mail" rows="10">';     
        $mail .= '
Beste '.$row["name"].', 

Bedankt voor het invullen van het contact formulier.
Wij zien u graag een keer langskomen bij een repetitie of uitvoering.

Met vriendelijke groeten,

Saskia van \'t Hul';
        $mail .= '</textarea>';
        $mail .= '<button type="submit" class="btn btn-info">Versturen</button><br><br><br> ';

        $mail .= '<div class="footermail">';
        $mail .= '<form method="post"><input type="hidden" name="id" value="'.$row["contactid"].'">';


        $mail .= '<button type="submit" name="action" value="2" class="btn btn-danger">Verwijderen</button>';
        $mail .= "</form></div>";
        
        $to = $row["email"];
    }

    if (isset($_POST["mail"]) && isset($_POST["title"])) {
        $content = filter_input(INPUT_POST, "mail");
        $title = filter_input(INPUT_POST, "title");
        $result = sendMail($title,nl2br($content),null,$to);
        if ($result == 1) {
            message("success","Mail verstuurd","De mail is succesvol verstuurd");
        }
        else {
            message("danger", "De mail is niet verstuurd", "Er is een technische fout opgetreden"); 
        }
    }

    $stmt = $db->prepare("UPDATE contact SET contactRead = 1 WHERE contactid = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}
else {
    $stmt = $db->prepare("SELECT * FROM contact ORDER BY date DESC");
    $stmt->execute();
    $table = "";
    while ($row = $stmt->fetch()) {
        if ($row["contactid"] == $postedid && $row["contactRead"] == 0) {
            $table .= '<tr style="font-weight: 800;" onclick="window.location.href = \'contactformposts.php?id='.$row["contactid"].'\'; ">';
        }
        elseif ($row["contactRead"] == 0) {
            $table .= '<tr style="font-weight: bold;" onclick="window.location.href = \'contactformposts.php?id='.$row["contactid"].'\';">';
        }
        else {
            $table .= '<tr onclick="window.location.href = \'contactformposts.php?id='.$row["contactid"].'\';">';
        }

        $messagetext = $row["message"];
        if (strlen($row["message"]) > 250) {
            $messagetext = substr($row["message"], 0, 250). "... <span class='small text-info'>klik voor volledig bericht</span>";
        }
        $table .= '<td>'.$row["name"].'</td>';
        $table .= '<td class="messagetext">'.$messagetext.'</td>';
        $table .= '<td class="date">'.date_format(date_create($row["date"]), "d-m-Y H:i:s").'</td>';   
        $table .= '<td class="email">'.$row["email"].'</td>';

        if ($row["contactRead"] == 0) {
            $table .= '<td class="actioncontact">';
            $table .= '<form method="post"><input type="hidden" name="id" value="'.$row["contactid"].'">';
            $table .= '<a href="contactformposts.php?id='.$row["contactid"].'#react" class="btn btn-info">Reageer</a> ';
            $table .= '<button type="submit" name="action" value="1" class="btn btn-success">Gelezen</button> ';
            $table .= '<button type="submit" name="action" value="2" class="btn btn-danger">Verwijderen</button>';
            $table .= '</form>';
            $table .= '</td>';
        }
        else {
            $table .= '<td class="actioncontact">';
            $table .= '<form method="post"><input type="hidden" name="id" value="'.$row["contactid"].'">';
            $table .= '<a href="contactformposts.php?id='.$row["contactid"].'#react" class="btn btn-info">Reageer</a> ';
            $table .= '<button type="submit" name="action" value="0" class="btn btn-warning">Ongelezen</button> ';
            $table .= '<button type="submit" name="action" value="2" class="btn btn-danger">Verwijderen</button>';
            $table .= '</form>';
            $table .= '</td>';
        }
        $table .= '</tr>';
    }
}
?>
<style>
    .btn {
        margin-bottom: 5px;
    }
    tr {
        cursor: pointer;
        transition: .2s all;
    }
    tr:hover {
        background: #f4f5f5;
    }
</style>
<h2 <?= $tablestyle ?> >Contact formulier</h2>
<?= $messagefield ?>
<div class="table-responsive" id="contactTable" <?= $tablestyle ?> >
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th class="from">Van</th>
                <th class="messagetext">Bericht</th>
                <th class="date">Datum</th>
                <th class="email">E-mailadres</th>
                <th class="actioncontact">Actie</th>
            </tr>
        </thead>

        <tbody>

            <?= $table ?>

        </tbody>
    </table>
</div>
<div class="container" id="viewmail" <?= $viewmail ?> >
    <?= $mail ?>
</div>

<?= $message ?>

<?php
    include("adminpagefooter.php");
?>