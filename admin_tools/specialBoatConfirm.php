<?php ini_set('display_errors', 'On'); ?>
<?php

//this script adds a non-scheduled boat

//connect to sql server with global $conn
require '../dbConnect.php';

//set timezone to west coast
date_default_timezone_set('America/Los_Angeles');

if (isset($_POST['specBoatDate'])) {
    $dateChosen = $_POST['specBoatDate'];
    $timeToAna = $_POST['time'];
}

//$dateDB = $dateChosen->format("Y-m-d");
$dateMod = new DateTime($timeToAna);
$dateMod->sub(new DateInterval('PT1H'));
//echo $dateMod->format('H:i:s');
$timeToDec = $dateMod->format('H:i:s');
//echo $timeToDec;
//echo $dateChosen;
//echo $timeToAna;
//start php session
session_start();
//set php session var
$_SESSION['dateChosen'] = $dateChosen;
$_SESSION['timeToAna'] = $timeToAna;
$_SESSION['timeToDec'] = $timeToDec;

//close the connection
$conn = null;

?>

<html>
    <head>
        <title>Confirm non-scheduled Boat</title>
        <meta charset = "UTF-8"></meta>
            <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'></link>
        <link rel="stylesheet" type="text/css" href="../css/boatRes.css"></link>

     </head>
     <body>

            <a>Please confirm that you would like to add the following non-scheduled boat:</a>
            <br/>
            <br/>
            <a>Date - </a>
                <?php
                  $newDateChosen = date('l, m/d/Y', strtotime($dateChosen));
                  echo $newDateChosen;
                //  echo $dateChosen;
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
            <!--<form id="confirm action="specialBoatAdd.php" method="post">-->
            <form action="specialBoatAdd.php" method="post">
            <input type="submit" name="submit" value="Confirm"></input>
            </form>
            <form action="admin_form.php">
            <input type="submit" name="submit" value="Return"></input>
            </form>
            </p>
    </body>
</html>
