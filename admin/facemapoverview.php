<?php
include("adminpageheader.php"); 

// verwijderen
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
        $stmt = $db->prepare("SELECT facemapUrl FROM facemap WHERE facemapId = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        while ($row = $stmt->fetch()) {
            $delete = "../".$row["facemapUrl"];
        }
        
        $stmt = $db->prepare("DELETE FROM facemap WHERE facemapId = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        if ($stmt->rowCount() == 1) {
            unlink($delete);
            
            message("success","Er is een koorlid verwijderd","Succesvol verwijderd");
            header('Location: facemapoverview.php');
        }
        else {
            message("danger","Verwijderen mislukt","Er is een technische fout opgetreden");
        }
    }
}

$table = "";
// gegevens uit db halen
$stmt = $db->prepare("SELECT * FROM facemap ORDER BY facemapId DESC");
$stmt->execute();
$table = "";
while ($row = $stmt->fetch()) {
    $table .= '<tr onclick="window.location.href = \'addmembers.php?id='.$row["facemapId"].'\';">';
    
    $table .= '<td>'.$row["facemapName"].'</td>';
    $table .= '<td><img src="../'.$row["facemapUrl"].'" height="100" ></td>';
    $table .= '<td>';
    $table .= '<form method="post"><input type="hidden" name="id" value="'.$row["facemapId"].'">';
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
<h2>Smoelenboek</h2>
<div id="message"></div>
<div class="table-responsive" id="contactTable">
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Naam</th>
                <th>Foto</th>
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