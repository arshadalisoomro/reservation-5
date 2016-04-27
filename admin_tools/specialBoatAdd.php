<?php ini_set('display_errors', 'On'); ?>
<?php

//this script adds a non-scheduled boat

//set url variables for redirect if no POST or no Confirm
$admin_url="https://www.flessner.org/-jonTest/admin_tools/specialBoatSelect.php";

//connect to sql server with global $conn
require '../dbConnect.php';

//set timezone to west coast
date_default_timezone_set('America/Los_Angeles');

if (isset($_POST['submit'])) {
  //  $dateChosen = $_POST['specBoatDate'];
  //  $timeToAna = $_POST['time'];
  session_start();
  //put data into local var
  $dateChosen = $_SESSION['dateChosen'];
  $timeToDec = $_SESSION['timeToDec'];
  $timeToAna = $_SESSION['timeToAna'];
//$dateDB = $dateChosen->format("Y-m-d");
//$dateMod = new DateTime($timeToAna);
//$dateMod->sub(new DateInterval('PT1H'));
//$timeToDec = $dateMod->format('H:i:s');

//insert special boat into database

$specialBoat = "INSERT INTO
        boatsDNW(departDate, departAnacortes, departDecatur)
        VALUES('".$dateChosen."', '$timeToDec', '$timeToAna')";

$conn->query($specialBoat);
}
else {
  header("Location: $admin_url");
  die();
}
//unset session var as no longer needed
unset($_SESSION['dataArray']);

//close the connection
$conn = null;

?>

<html>
    <head>
        <title>Add non-scheduled Boat</title>
        <meta charset = "UTF-8"></meta>
            <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'></link>
        <link rel="stylesheet" type="text/css" href="../css/boatRes.css"></link>

     </head>
     <body>

            <a>You have added the following non-scheduled boats:</a>
            <br/>
            <br/>
            <a>Date - </a>
                <?php
                  $newDateChosen = date('l, m/d/Y', strtotime($dateChosen));
                  echo $newDateChosen;
                ?>
            <br />
            <br />
            <a>Time from Skyline to DNW -</a>
            <?php
            $newTimeToDec = date('g:ia', strtotime($timeToDec));
            echo $newTimeToDec;
            ?>
            <br />
            <br />
            <a>Time from DNW to Skyline -</a>
            <?php
            $newTimeToAna = date('g:ia', strtotime($timeToAna));
            echo $newTimeToAna;
            ?>
            <br />
            <br />
            <p>
            <form id="confirm" action="admin_form.php">
            <input type="submit" name="submit" value="Return"></input>
            </form>
            </p>
    </body>
</html>
