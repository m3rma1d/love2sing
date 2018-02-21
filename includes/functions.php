<?php

session_start();

//DATABASE CONNECT

class DbHelper
{
    var $connect;

    //maakt de DB connectie aan
    public function __construct()
    {
        try {
            $this->connect = new PDO('mysql:host=localhost; dbname=love2sing;');
        } catch (PDOException $Exception) {
            echo '<div  class="alert alert-danger fade in message"><strong>Database error </strong>De database verbinding is mislukt</div>';
            exit;
        }
    }


    //SELECT USER FUNCTION

    function selectUser()
    {
        if (!isset($_SESSION['loginAttempts'])) {
            $_SESSION['loginAttempts'] = 1;
        }

        if (isset($_SESSION["time"])) {
            if ($_SESSION["time"] < date("Hi")) {
                $_SESSION['loginAttempts'] = 1;
                unset($_SESSION["time"]);
            }
        }

        if ($_SESSION['loginAttempts'] < 3) {
            
            // ingevulde gebruikersnaam
            $username = filter_input(INPUT_POST, 'username');
            //hash ingevuld password
            $hash = hash('sha256', filter_input(INPUT_POST, 'password'));

            //select query voor de users
            $create = 'SELECT * FROM user WHERE username=:username AND userPassword=:password';

            //zorgt dat de connectie wordt gestart vanuit de db
            $statement = $this->connect->prepare($create);

            //haalt de gegevens op uit deze rijen
            $statement->bindParam(':username', $username, PDO::PARAM_STR);
            $statement->bindParam(':password', $hash, PDO::PARAM_STR);

            $statement->execute();

            //controleert of alles klopt
            $result = $statement->fetchAll();


            //functie userrights
            if (isset($result) && isset($result[0])) {
                $user = $result[0];
                $_SESSION['logIn'] = 'true';
                $_SESSION['username'] = $user[1];
                $_SESSION['userId'] = $user[0];


                if ($user["userRights"] == 1) {
                    // user is een gebruiker
                    $_SESSION['userRights'] = 'user';
                } elseif ($user["userRights"] == 2) {
                    // user is een admin
                    $_SESSION['userRights'] = 'admin';
                }
                $redirect = "";

                if (isset($_GET["redirect"])) {
                    $redirect = filter_input(INPUT_GET, "redirect");
                    header('Location: ' . $redirect . '');
                    exit;
                } else {
                    header('Location: index.php');
                    exit;
                }
            } else {
                message("danger", "Onjuiste inloggegevens", "U bent niet ingelogd, controleer uw gegevens");
            }
            // laat melding(en) zien
            $_SESSION['loginAttempts']++;

        } else {
            message("danger", "Account voor 15 minuten geblokkeerd!", "U heeft uw wachtwoord meer dan 3 keer verkeerd ingevoerd. Over 15 minuten kunt u het weer proberen.");
            $_SESSION["time"] = date('Hi') + 15;
        }
    }

    function editUserStart($case)
    {
        //select query die de gegevens van de klant ophaald zodat hij/zij deze kan veranderen
        $create = "SELECT * FROM user WHERE userId = :id";

        //deze functie zorgt ervoor dat de gegevens uit de databse worden gehaald
        $statement = $this->connect->prepare($create);

        //zorgt ervoor dat het juiste id wordt gepakt
        $editId = $_SESSION['editPassword'];
        if (isset($_GET["id"])) {
            $editId = filter_input(INPUT_GET, "id");
        }
        $statement->bindParam(':id', $editId, PDO::PARAM_STR);

        $statement->execute();

        $user = $statement->fetchAll();

        $userEdit = $user[0];


        //zorgt ervoor dat wanneer de klant iets vergeet, deze melding in beeld komt
        if (!isset($case)) {
            echo "<p>Er is een fout opgetreden met het wijzigen van uw gegevens. Probeer het opnieuw.</p>";
        } else {
            //zorgt ervoor dat wanneer alles correct is, de gegevens worden doorgestuurd naar de db
            print $userEdit[$case];

        }
    }

    function editUser($editId = null)
    {
        //update query voor de gebruikers
        $edit = "UPDATE user SET  
    userPassword = :password
    WHERE userId = :id";

        //hashed het ingevoerde password
        $hash = hash('sha256', filter_input(INPUT_POST, 'password'));

        //bereid de db voor
        $statement = $this->connect->prepare($edit);

        //de gegevens die gewijzigd mogen worden (het id wordt NIET aangepast)

        //$statement->bindParam(':id', $_SESSION['userId'], PDO::PARAM_STR);
        //$statement->bindParam(':password', $hash, PDO::PARAM_STR);


        if ($editId == null) {
            $editId = $_SESSION['userId'];
        }


        $statement->bindParam(':id', $editId, PDO::PARAM_STR);
        $statement->bindParam(':password', $hash, PDO::PARAM_STR);


        $statement->execute();

        if ($statement->rowCount() == 1) {
            return 1;
        } else {
            return 0;
        }
    }


    function returndb()
    {
        return $this->connect;
    }

    function createUser()
    {
        //password hashen
        if ($_POST['userPassword'] == $_POST['repeatPassword']) {
            $hash = hash('sha256', filter_input(INPUT_POST,'userPassword'));


            //insert query om USERS toe te voegen
            $create = 'INSERT INTO user (username , userEmail ,  userPassword , userRights) VALUES (:username , :userEmail , :userPassword , :rights)';

            $statement = $this->connect->prepare($create);

            //de gegevens die ingevuld moeten worden door de klant
            $statement->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
            $statement->bindParam(':userEmail', $_POST['userEmail'], PDO::PARAM_STR);
            $statement->bindParam(':userPassword', $hash, PDO::PARAM_STR);
            $statement->bindParam(':rights', $_POST['addUseraccount'], PDO::PARAM_STR);
            //$statement->bindParam(':userRights', $_POST['userRights'], PDO::PARAM_STR);

            $statement->execute();
            if ($statement->rowCount() == 1) {
                message("success", "Account toegevoegd", " ");
            } else {
                message("danger", "Account toevoegen mislukt", "");
            }
        } else {
            message("danger", "Herhaling wachtwoord is niet gelijk", "");
        }
    }
}

// bestand uploaden

// code's: 
// 0    technische fout
// 1    gelukt

// 3    bestand is te groot
// 4    bestandstype is verkeerd
// 5    er is geen bestand gekozen of bijgevoegd

// fileUpload($file,$type) genereert automatisch meldingen, zie message($type,$title,$content) voor vereisten om melding weer te geven

// aanroepen:
// fileUpload($_FILES["file_input_name"],"filetype");   //(filetype zonder .) 

// Uitgebreide manier: 
// $result = fileUpload($_FILES["file_input_name"],array("filetype1","filetype2")); //(filetype zonder .) 
// $result[0] foutcodes
// $result[1] opslaglocatie van bestand in database

/**
 * @param $file
 * @param $type
 * @return array
 */
function fileUpload($file, $type)
{
    if ($file["error"] == 4) {
        return array(null, 5);
    } else {
        $target_dir = "uploads/";

        $uploadOk = 1;
        $fileType = pathinfo(basename($file["name"]), PATHINFO_EXTENSION);
        $result = "";


        while (true) {
            $fileName = $fileType . "-" . date("Y-m-d-h-i-s-u") . "-" . rand(1000, 9999);
            $target_file = $target_dir . $fileName . "." . $fileType;
            if (!file_exists($target_file)) {
                break;
            }
        }


        if ($file["size"] > 10000000) {
            $uploadOk = 0;
            $result .= "3";


            message("warning", $file["name"] . " is niet opgeslagen", "Het bestand " . $file["name"] . " is te groot " . $file["size"] / 1000000 . "MB, max 10MB");
            // check of het bestand te groot is, zo ja: foutcode 3
        }
        if (is_array($type)) {
            if (!in_array($fileType, $type)) {
                $uploadOk = 0;
                $result .= "4";
                message("warning", $file["name"] . " is niet opgeslagen", "Het bestand " . $file["name"] . " is geen " . implode(", ", $type));
                // check of het bestand geen $type type is, zo ja: foutcode 4
            }
        } elseif ($type != $fileType) {
            $uploadOk = 0;
            $result .= "4";
            message("warning", $file["name"] . " is niet opgeslagen", "Het bestand " . $file["name"] . " is geen " . $type);
            // check of het bestand geen $type type is, zo ja: foutcode 4
        }

        if ($uploadOk == 0) {
            return array($target_file, $result);
            // er zijn fouten opgetreden, de foutcodes worden gereturnd
        } else {
            if (move_uploaded_file($file["tmp_name"], "../" . $target_file)) {
                message("success", $file["name"] . " is geupload", "Het bestand " . $file["name"] . " is succesvol opgeslagen op de server");
                return array($target_file, 1);
                // bestand is succesvol geupload
            } else {
                message("danger", $file["name"] . " is niet opgeslagen", "Er is een technische fout opgetreden");
                return array($target_file, 0);
                // er is een probleem opgetreden met uploaden
            }
        }
    }
}

// meldingen weergeven

/* 
type = success/info/warning/danger

Plaats <div id="message"></div> waar je de meldingen wilt weergeven
Plaats daaronder <?= $message ?>
*/

$message = null;
function message($type, $title, $content)
{
    global $message;
    $message .= '<script>
    message("' . $type . '","' . $title . '","' . $content . '");
    </script>';
}

// mail versturen

// code's: 
// 0    technische fout
// 1    gelukt

// aanroepen:
// sendMail(onderwerp,bericht,beantwoorden naar) // als er geen beantwoorden naar nodig is null invoeren

// Uitgebreide manier: 
// $var = sendMail(onderwerp,bericht,beantwoorden naar) // foutcode wordt doorgegeven aan $var

//  inloggen op gmail
//  love2singtestmail@gmail.com
//  love2singmail
//  php.ini en sendmail.ini aanpassen, zie trello->programming rules

function sendMail($subject, $message, $replyTo, $to = null)
{
    if ($to == null) {
        $to = "love2singtestmail@gmail.com";
    }
    if ($replyTo == null) {
        $replyTo = "love2singtestmail@gmail.com";
    }
    $headers = 'From: love2singtestmail@gmail.com' . "\r\n" .
        'Reply-To: ' . $replyTo . '' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=\"UTF-8\" \r\n";

    if (mail($to, $subject, $message, $headers)) {
        return 1;
    } else {
        return 0;
    }
}

//EDIT USER FUNCTION


// check of de gebruiker admin rechten heeft
function adminpage()
{
    if (isset($_SESSION['logIn']) && $_SESSION['logIn'] == 'true' && $_SESSION['userRights'] == 'admin') {
        return true;
    } else {
        return false;
    }
}

// check of de gebruiker user rechten heeft
function userpage()
{
    if (isset($_SESSION['logIn']) && $_SESSION['logIn'] == 'true' && $_SESSION['userRights'] == 'user') {
        return true;
    } else {
        return false;
    }
}


// maak $db variabele
$view = new DbHelper();
$db = $view->returndb();

?>
