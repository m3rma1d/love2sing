<?php
include("adminpageheader.php"); 

// verwijderen van muziek
if ((isset($_POST["action"]) || isset($_GET["action"])) && (isset($_POST["id"]) || isset($_GET["id"]))) {
    if (isset($_POST["action"])) {
        $status = filter_input(INPUT_POST, "action");
    }
    else {
        $status = filter_input(INPUT_GET, "action");
    }
    if (isset($_POST["id"])) {
        $id = filter_input(INPUT_POST, "id");
    }
    else {
        $id = filter_input(INPUT_GET, "id");
    }  
    if ($status == 2) {
        $stmt = $db->prepare("SELECT musicPdf, musicMp3 FROM music WHERE musicId = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        while ($row = $stmt->fetch()) {
            $deletePdf = "../".$row["musicPdf"];
            $deleteMp3 = "../".$row["musicMp3"];
        }
        
        $stmt = $db->prepare("DELETE FROM music WHERE musicId = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            if(file_exists($deletePdf)) {
                unlink($deletePdf);
            }
            if(file_exists($deleteMp3)) {
                unlink($deleteMp3);
            }

            message("success","Er is een muziekstuk verwijderd","Succesvol verwijderd");
            header('Location: musicuploads.php');
        }
        else {
            message("danger","Verwijderen mislukt","Er is een technische fout opgetreden");
        }
    }
}

$table = "";
// muziek ophalen uit db
$stmt = $db->prepare("SELECT * FROM music m JOIN componist c ON m.componistId = c.componistId ORDER BY musicId DESC");
$stmt->execute();
$table = "";
while ($row = $stmt->fetch()) {
    $table .= '<tr onclick="window.location.href = \'musicupload.php?id='.$row["musicId"].'\';">';
    $table .= '<td>'.$row["musicName"].'</td>';
    $table .= '<td>'.$row["componistName"].'</td>';
    $table .= '<td class="invisibleOnPhone" >'.$row["musicPitch"].'</td>';  
    if ($row["musicMp3"] != null ) {
        $table .= '<td class="invisibleOnPhone"><a class="text-info" href="../'.$row["musicMp3"].'" download>Download</td>';
    }
    else {
        $table .= '<td class="invisibleOnPhone"><p class="text-warning small">geen</p></td>';
    }

    if ($row["musicPdf"] != null ) {
        $table .= '<td class="invisibleOnPhone"><a class="text-info" href="../'.$row["musicPdf"].'" download>Download</td>';
    }
    else {
        $table .= '<td class="invisibleOnPhone"><p class="text-warning small">geen</p></td>';
    }

    $table .= '<td>';
    $table .= '<form method="post"><input type="hidden" name="id" value="'.$row["musicId"].'">';
    $table .= '<button type="submit" name="action" value="2" class="btn btn-danger">Verwijderen</button>';
    $table .= '</form>';
    $table .= '</td>';

    $table .= '</tr>';
}

?>
<style>
    .btn {
        margin-bottom: 10px;
    }
    tr {
        cursor: pointer;
        transition: .2s all;
    }
    tr:hover {
        background: #f4f5f5;
    }
</style>
<h2>Geuploade muziek</h2>
<div id="message"></div>
<div class="table-responsive" id="contactTable">
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Titel</th>
                <th>Componist</th>
                <th class="invisibleOnPhone">Pitch</th>
                <th class="invisibleOnPhone">MP3</th>
                <th class="invisibleOnPhone">PDF</th>
                <th>Actie</th>
            </tr>
        </thead>

        <tbody>

            <?= $table ?>

        </tbody>
    </table>
</div>

<?= $message ?>

<?php
    include("adminpagefooter.php");
?>