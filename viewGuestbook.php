<?php
require 'header.php';
?>


<section>

    <h2 class="section-heading text-uppercase text-center">Gastenboek</h2>
    <hr class="my-4">

    <div class="col-lg-8 mx-auto">
        <h3 class="section-subheading text-muted text-center">Welkom bij ons gastenboek! Wilt u ook een bericht achterlaten? Klik dan <a class="page-reference" href="addtoGuestbook.php?gb=true"><b>hier!</b></a></h3>
    </div>

    <?php

    //d.m.v. prepare, veilige query om de benodigde data uit de tabel op te halen
    $stmt= $db->prepare("SELECT * FROM guestbook WHERE guestbookApproved = 1 ORDER BY guestbookDate DESC");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    if($stmt->rowCount() > 0){
        while($row =$stmt->fetch()){
            echo'
<article class="gb-entry">
  <div class="gb-entry-info-box">
    <div class="gb-entry-info-box-row">
    </div>
    <div class="gb-entry-info-box-row">
      <div class="gb-entry-info-box-row-value">
        '.$row["guestbookTitle"].'
      </div>
      <br class="clearfloat">
    </div>
  </div>
  <div class="gb-entry-message-box">
    <div class="gb-entry-message">
      '.$row["guestbookMessage"].'

      <br>
      <br>  
        '.$row["guestbookDate"].'

    </div>
  </div>
</article>
                    ';
        }
    }



    ?>

</section>
<?php
require 'footer.php';
?>