<?php
include("adminpageheader.php");
$units = explode(' ', 'B KB MB GB TB PB');
function foldersize($path)
{
    $total_size = 0;
    $files = scandir($path);
    $cleanPath = rtrim($path, '/') . '/';

    foreach ($files as $t) {
        if ($t <> "." && $t <> "..") {
            $currentFile = $cleanPath . $t;
            if (is_dir($currentFile)) {
                $size = foldersize($currentFile);
                $total_size += $size;
            } else {
                $size = filesize($currentFile);
                $total_size += $size;
            }
        }
    }

    return $total_size;
}

function format_size($size)
{
    global $units;

    $mod = 1024;

    for ($i = 0; $size > $mod; $i++) {
        $size /= $mod;
    }

    $endIndex = strpos($size, ".") + 3;

    return substr($size, 0, $endIndex) . ' ' . $units[$i];
}

$usedSpaceBytes = foldersize("../uploads");
$usedSpace = format_size($usedSpaceBytes);
$totalSpaceBytes = 1000000000;
$totalSpace = format_size($totalSpaceBytes);
if ($usedSpaceBytes > $totalSpaceBytes * .9) {
    $spaceType = "danger";
} elseif ($usedSpaceBytes > $totalSpaceBytes * .8) {
    $spaceType = "warning";
} else {
    $spaceType = "success";
}

$percentUsed = round($usedSpaceBytes / $totalSpaceBytes * 100, 1);

// aantallen selecteren

$stmt = $db->prepare("SELECT COUNT(*) FROM guestbook WHERE guestbookRead = 0");
$stmt->execute();

while ($row = $stmt->fetch()) {
    $unreadGuestbookPosts = $row[0];
}
if ($unreadGuestbookPosts == 0) {
    $guestbookType = "success";
} else {
    $guestbookType = "warning";
}


$stmt = $db->prepare("SELECT COUNT(*) FROM contact WHERE contactRead = 0");
$stmt->execute();

while ($row = $stmt->fetch()) {
    $unreadContactPosts = $row[0];
}

if ($unreadContactPosts == 0) {
    $contactType = "success";
} else {
    $contactType = "warning";
}


?>
    <section>
        <div class="container">
            <h2 class="section-heading text-uppercase">Admin paneel</h2>
            <h3>Hier kunt u de website bewerken, reacties inzien en muziek toevoegen</h3>
            <div class="row">
                <div class="col-xl-4 col-sm-6 mb-4">
                    <div class="card text-white bg-<?= $guestbookType ?> o-hidden ">
                        <div class="card-body">
                            <div class="card-body-icon">
                                <i class="fa fa-fw fa-comments"></i>
                            </div>
                            <div class="mr-5">
                                <?php
                                echo $unreadGuestbookPosts . " ";
                                if ($unreadGuestbookPosts != 1) {
                                    echo "nieuwe gastenboek berichten";
                                } else {
                                    echo "nieuw gastenboek bericht";
                                }
                                ?>
                            </div>
                        </div>
                        <a class="card-footer text-white clearfix small z-1" href="guestbookposts.php">
                            <span class="float-left">Overzicht</span>
                            <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
                        </a>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6 mb-4">
                    <div class="card text-white bg-<?= $contactType ?> o-hidden ">
                        <div class="card-body">
                            <div class="card-body-icon">
                                <i class="fa fa-fw fa-envelope"></i>
                            </div>
                            <div class="mr-5">
                                <?php
                                echo $unreadContactPosts . " ";
                                if ($unreadContactPosts != 1) {
                                    echo "nieuwe contact berichten";
                                } else {
                                    echo "nieuw contact bericht";
                                }
                                ?>
                            </div>
                        </div>
                        <a class="card-footer text-white clearfix small z-1" href="contactformposts.php">
                            <span class="float-left">Overzicht</span>
                            <span class="float-right">
                <i class="fa fa-angle-right"></i>
              </span>
                        </a>
                    </div>
                </div>

                <div class="col-xl-4 col-sm-6 mb-4">
                    <div class="card text-white bg-<?= $spaceType ?> o-hidden">
                        <div class="card-body">
                            <div class="card-body-icon">
                                <i class="fa fa-fw fa-cloud"></i>
                            </div>
                            <div class="mr-5">Gebruikte opslagruimte: <?= $usedSpace ?> van de <?= $totalSpace ?></div>
                        </div>
                        <a class="card-footer text-white clearfix small z-1">
                            <span class="float-left">Gebruikt: <?= $percentUsed ?>%</span>
                            <span class="float-right">
              </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
include("adminpagefooter.php");
?>