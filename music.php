<!---Start Includes--->
<?php
include "header.php";
if(!userpage() && !adminpage()) {
    header("location: index.php");
}
?>
<!---End Includes--->


<style>
    .row {
        margin: 0px;
    }
</style>


<section>
    <!---Start Main--->
    <!---Start Search Bar--->
    <h2 class="section-heading text-uppercase text-center">Muziekbibliotheek</h2>
    <hr class="my-4">
    <div class="searchBar">
        <form class="form-wrapper cf" method="POST">
            <div class="row">
                <div class="col-lg-6 col-sm-12">
                    <?php
                    if (isset($_POST['q'])) {
                        $value = $_POST['q'];
                    } else {
                        $value = "";
                    }
                    ?>
                    <input class="form-control" value="<?= $value ?>" type="text" name="q"
                           placeholder="Zoek op muzieknaam of componist">
                </div>
                <div class="col-lg-3 col-sm-12">

                    <select class="form-control" name="column">
                        <?php
                        $selected0 = "";
                        $selected1 = "";
                        if (isset($_POST['column'])) {
                            if ($_POST['column'] == 0) {
                                $selected0 = "selected";
                            }
                            if ($_POST['column'] == 1) {
                                $selected1 = "selected";
                            }
                        }
                        ?>

                        <option class="option" value="0" <?= $selected0 ?> >Muzieknaam</option>
                        <option class="option" value="1" <?= $selected1 ?> >Componist</option>
                    </select>
                </div>
                <div class="col-lg-3 col-sm-12">
                    <input class="search btn btn-primary text-uppercase" type="submit" name="submit" value="Zoeken">
                </div>
            </div>
        </form>
    </div>

    <br><br>

    <!---End Search Bar--->

    <!---Start Box--->
    <div class="results">
        <div class="row">
            <?php
            if (isset($_POST['submit'])) {
                $columnnaam = $_POST['column'];
                $zoekopdracht = $_POST['q'];
                if ($columnnaam == '0') {
                    $stmt = $db->prepare("SELECT * FROM music JOIN componist ON componist.componistId = music.componistId WHERE musicName LIKE '%$zoekopdracht%'");

                } elseif ($columnnaam == '1') {
                    $stmt = $db->prepare("SELECT * FROM music JOIN componist ON componist.componistId = music.componistId WHERE componistName LIKE '%$zoekopdracht%'");

                }
                $stmt->execute(array($zoekopdracht));

            } else {
                $stmt = $db->prepare("SELECT * FROM music JOIN componist ON music.componistId=componist.componistId");
                $stmt->execute();
            }
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            while ($row = $stmt->fetch()) {
                echo '<div class="col-lg-4 col-sm-12">';
                echo '<div class="card" style="margin: 20px 0;">';
                echo '<div class="card-body">';
                echo '<h1>';
                echo $row['musicName'];
                echo '</h1>';
                echo '</h2> <p><b>Componist: </b>';
                echo $row['componistName'];
                echo '<p><b>Geboortedatum componist: </b>';
                echo $row['componistYearOfBirth'];
                echo '</p><p><b>Pitch: </b>';
                echo $row['musicPitch'];
                echo '</p><p>';
                if ($row['musicMp3'] != null) {
                    echo '<a href="' . $row['musicMp3'] . '" download><img src="img/mp3.jpg" alt="Download mp3" title="Download mp3" height=100px></a>';
                }
                else {
                    echo '<a class="disabled"><img src="img/mp3.jpg" alt="Geen mp3 beschikbaar" title="Geen mp3 beschikbaar" height=100px></a>';
                }
                if ($row['musicPdf'] != null) {
                    echo '<a href="' . $row['musicPdf'] . '" download class="float-right" ><img src="img/pdf.jpg" alt="Download pdf" title="Download pdf" height=100px></a>';
                }
                else {
                    echo '<a class="float-right disabled"><img src="img/pdf.jpg" alt="Geen pdf beschikbaar" title="Geen pdf beschikbaar" height=100px></a>';
                }
                echo '</p>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            if ($stmt->rowCount() == 0) {
                message("warning", "Geen resultaten gevonden.","Probeer een andere zoekterm");
            }

            ?>
            <div class="col"><div id="message"></div></div>
        </div>

    </div>
    <!---End Box--->

    <!---End Main--->
</section>
<?= $message ?>
<?php
require 'footer.php';
?>