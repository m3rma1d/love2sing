<?php
require_once 'header.php';
if(!userpage() && !adminpage()) {
    header("location: index.php");
}
$sql = 'SELECT * FROM facemap';
?>


<section>
<h2 class="section-heading text-uppercase text-center">Smoelenboek</h2>
    <hr class="my-4">
    <div class="container">

        <div class="row">
            <?php foreach ($db->query($sql) as $row) { ?>
            <div class="col-md-3 col-sm-12">
                <div class="card" style="margin: 20px 0;">
                    <img class="card-img-top" src="<?= $row['facemapUrl'] ?>" alt="Image" style="height:190px;width:auto;">
                    <div class="card-body">
                        <h4 class="card-title"><?php echo $row['facemapName']; ?></h4>
<!--                        <p class="card-text">Ik ben een descriptie.</p>-->
                       
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</section>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>

<?php
require 'footer.php';
?>