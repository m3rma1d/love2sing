<?php
include("adminpageheader.php"); 

// alleen bij het zojuist toegevoegde bericht de status aanpassen d.m.v. de WHERE
// $_GET, want een $_POST wil niet vanuit de mail
if(isset($_GET['toevoegen']) && isset($_GET['id'])){
    $approve= $db->prepare("UPDATE guestbook SET guestbookRead = 1, guestbookApproved = 1 WHERE guestbookId = ?;");
    $approve->execute(array($_GET['id']));
}             
if(isset($_GET['weigeren']) && isset($_GET['id'])){
    $approve= $db->prepare("UPDATE guestbook SET guestbookRead = 1, guestbookApproved = 0 WHERE guestbookId = ?;");
    $approve->execute(array($_GET['id']));
}

if (isset($_POST["action"]) && isset($_POST["id"])) {
    $status = filter_input(INPUT_POST, "action");
    $id = filter_input(INPUT_POST, "id");
    if ($status == 2) {
        $stmt = $db->prepare("DELETE FROM guestbook WHERE guestbookId = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            message("success","Er is een gastenboekbericht verwijderd","Succesvol verwijderd");
            header('Location: guestbookposts.php');
        }
        else {
            message("danger","Verwijderen mislukt","Er is een technische fout opgetreden");
        }
    }
    else {
        $stmt = $db->prepare("UPDATE guestbook SET guestbookRead = 1, guestbookApproved = :status WHERE guestbookId = :id");
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

$mail = "";
$postedid = null;
$table = " ";

$messagebox = '<div id="message"></div>';

if (isset($_GET["id"])) {
    $id = filter_input(INPUT_GET, "id");
    $messagebox = "";
    $tablestyle = "style = 'display: none'";
    $viewmail = "style = 'display: block'";

    $stmt = $db->prepare("SELECT * FROM guestbook WHERE guestbookId = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    while ($row = $stmt->fetch()) {
        if ($row["guestbookApproved"] == 0 && $row["guestbookRead"] == 0) {
            $approved = '<span class="text-warning">Wacht op actie</span>';
            $buttons = '<button type="submit" name="action" value="1" class="btn btn-success">Goedkeuren</button> ';
            $buttons .= '<button type="submit" name="action" value="0" class="btn btn-warning">Afkeuren</button> ';
            $buttons .= '<button type="submit" name="action" value="2" class="btn btn-danger">Verwijderen</button>';
        }
        elseif ($row["guestbookApproved"] == 1) {
            $approved = '<span class="text-success">Goedgekeurd</span>';
            $buttons = '<button type="submit" name="action" value="0" class="btn btn-warning">Afkeuren</button> ';
            $buttons .= '<button type="submit" name="action" value="2" class="btn btn-danger">Verwijderen</button>';
        }
        else {
            $approved = '<span class="text-danger">Afgekeurd</span>';
            $buttons = '<button type="submit" name="action" value="1" class="btn btn-success">Goedkeuren</button> ';
            $buttons .= '<button type="submit" name="action" value="2" class="btn btn-danger">Verwijderen</button>';
        }
        $mail .= '<div class="mailback" id="backbutton"><a href="guestbookposts.php"><i class="fa fa-arrow-left" aria-hidden="true"></i> Terug</a></div><br>'; 
        $mail .= '<h1>'.$row["guestbookTitle"].'</h1>';
        $mail .= ' <p>';
        $mail .= '<b>Status: </b>'.$approved.'';
        $mail .= '<br>';
        $mail .= '<b>Geschreven op: </b>'.$row["guestbookDate"].'';
        $mail .= '</p>';
        $mail .= '<h2>Bericht:</h2>';
        $mail .= '<p>';
        $mail .= $row["guestbookMessage"];
        $mail .= '</p><br><br><br>';
        $mail .= '<div class="footermail">';
        $mail .= '<div id="message" style="display: table; margin: 0;"></div>';
        $mail .= '<form method="post"><input type="hidden" name="id" value="'.$row["guestbookId"].'">';
        $mail .= $buttons;
        $mail .= "</form></div>";
    }    
}
else {
    $stmt = $db->prepare("SELECT * FROM guestbook ORDER BY guestbookDate DESC");
    $stmt->execute();
    $table = "";
    while ($row = $stmt->fetch()) {
        if ($row["guestbookId"] == $postedid && $row["guestbookRead"] == 0) {
            $table .= '<tr style="font-weight: 800;" onclick="window.location.href = \'guestbookposts.php?id='.$row["guestbookId"].'\'; ">';
        }
        elseif ($row["guestbookRead"] == 0) {
            $table .= '<tr style="font-weight: bold;" onclick="window.location.href = \'guestbookposts.php?id='.$row["guestbookId"].'\'; ">';
        }
        else {
            $table .= '<tr onclick="window.location.href = \'guestbookposts.php?id='.$row["guestbookId"].'\'; ">';
        }
        $messagetext = $row["guestbookMessage"];
        if (strlen($row["guestbookMessage"]) > 250) {
            $messagetext = substr($row["guestbookMessage"], 0, 250). "... <span class='small text-info'>klik voor volledig bericht</span>";
        }

        $table .= '<td>'.$row["guestbookTitle"].'</td>';
        $table .= '<td class="messagetext">'.$messagetext.'</td>';
        $table .= '<td>'.date_format(date_create($row["guestbookDate"]), "d-m-Y H:i:s").'</td>';    
        if ($row["guestbookApproved"] == 0 && $row["guestbookRead"] == 0) {
            $table .= '<td class="status"><p class="text-warning">Wacht op actie</p></td>';
            $table .= '<td>';
            $table .= '<form method="post"><input type="hidden" name="id" value="'.$row["guestbookId"].'">';
            $table .= '<button type="submit" name="action" value="1" class="btn btn-success">Goedkeuren</button> ';
            $table .= '<button type="submit" name="action" value="0" class="btn btn-warning">Afkeuren</button> ';
            $table .= '<button type="submit" name="action" value="2" class="btn btn-danger">Verwijderen</button>';
            $table .= '</form>';
            $table .= '</td>';
        }
        elseif ($row["guestbookApproved"] == 1) {
            $table .= '<td class="status"><p class="text-success">Goedgekeurd</p></td>';
            $table .= '<td>';
            $table .= '<form method="post"><input type="hidden" name="id" value="'.$row["guestbookId"].'">';
            $table .= '<button type="submit" name="action" value="0" class="btn btn-warning">Afkeuren</button> ';
            $table .= '</form>';
            $table .= '</td>';
        }
        else {
            $table .= '<td class="status"><p class="text-danger">Afgekeurd</p></td>';
            $table .= '<td>';
            $table .= '<form method="post"><input type="hidden" name="id" value="'.$row["guestbookId"].'">';
            $table .= '<button type="submit" name="action" value="1" class="btn btn-success">Goedkeuren</button> ';
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
<h2 <?= $tablestyle ?> >Gastenboek</h2>
<?= $messagebox ?>
<div class="table-responsive" <?= $tablestyle ?> >
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Titel</th>
                <th class="messagetext">Bericht</th>
                <th class="date">Datum</th>
                <th class="status">Status</th>
                <th class="actionguestbook">Actie</th>
            </tr>
        </thead>

        <tbody>

            <?= $table ?>

        </tbody>
    </table>
</div>

<div class="container" id="viewmail" <?= $viewmail ?> >
    <br>
    <?= $mail ?>
</div>

<?= $message ?>
<?php
    include("adminpagefooter.php");
?>