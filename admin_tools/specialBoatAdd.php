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
   session_start();
   //put data into local var
   $dateChosen = $_SESSION['dateChosen'];
   $timeToDec = $_SESSION['timeToDec'];
   $timeToAna = $_SESSION['timeToAna'];
   //insert special boat into database
   //Alter Table makes sure duplicates are not re-entered
   $alterTable = "ALTER TABLE boatsDNW ADD UNIQUE (departDate,departAnacortes,departDecatur)";
   $conn->query($alterTable);
   // check to see if the boat was previously cancelled and if so, remove cancel
   $checkBoat = "Update boatsDNW set isCancelled = '0' where departDate = '$dateChosen' And
                 departAnacortes = '$timeToDec' and departDecatur = '$timeToAna'";
   $conn->query($checkBoat);
   //insert special boat into database
   $specialBoat = "INSERT INTO boatsDNW(departDate, departAnacortes, departDecatur)
                   VALUES('$dateChosen', '$timeToDec', '$timeToAna')";
   $conn->query($specialBoat);
   //unset session var as no longer needed
   unset($_SESSION['dataArray']);
   //close the connection
   $conn = null;
}
else {
  //close the connection
  $conn = null;
  //return to special boat select page
  header("Location: $admin_url");
  die();
}

?>

<html>
    <head>
        <title>Add non-scheduled Boat</title>
        <meta charset = "UTF-8"></meta>
      <!--  <script src="../libraries/jquery-2.2.3.min.js"></script> -->
        <link rel="stylesheet" type="text/css" href="../css/boatRes.css"></link>

     </head>
     <body>

            <a>You have added the following non-scheduled boats:</a>
            <br/>
            <br/>
            <?php
              $newDateChosen = date('l, m/d/Y', strtotime($dateChosen));
              echo $newDateChosen;
            ?>
            <br />
            <br />
            <a>From Anacortes to DNW at</a>
            <?php
            $newTimeToDec = date('g:ia', strtotime($timeToDec));
            echo $newTimeToDec;
            ?>
            <br />
            <br />
            <a>From DNW to Anacortes at</a>
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
