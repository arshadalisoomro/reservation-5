<?php ini_set('display_errors', 'On'); ?>
<?php

//this script adds a non-scheduled boat

//set url variables for redirect if no POST or no Confirm
$admin_url="https://www.flessner.org/-jonTest/admin_tools/cancelBoatSelect.php";

//connect to sql server with global $conn
require '../dbConnect.php';

//set timezone to west coast
date_default_timezone_set('America/Los_Angeles');

if (isset($_POST['submit'])) {
   session_start();
   //put data into local var
   $dateChosen = $_SESSION['dateChosen'];
   $timeChosen = $_SESSION['timeChosen'];

   //calculate time to Dectur by adding one hour from time to Anacortes
   $dateMod = new DateTime($timeChosen);
   $dateMod->add(new DateInterval('PT1H'));
   $timeToDec = $dateMod->format('H:i:s');

      //Alter Table makes sure duplicates are not re-entered
   $alterTable = "ALTER TABLE boatsDNW ADD UNIQUE (departDate,departAnacortes,departDecatur)";
   $conn->query($alterTable);

   //update boatsDNW to indicate that the boat is now cancelled
   $cancelBoat = "UPDATE boatsDNW set isCancelled = '1' where departDate = '$dateChosen' and
       departAnacortes = '$timeChosen'";

   $conn->query($cancelBoat);
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
        <title>Cancel Boat</title>
        <meta charset = "UTF-8"></meta>
            <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="../css/admin.css"></link>

     </head>
     <body>

            <a>You have canceled the following boats:</a>
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
            $newTimeChosen = date('g:ia', strtotime($timeChosen));
            echo $newTimeChosen;
            ?>
            <br />
            <br />
            <a>and the return from DNW to Anacortes at</a>
            <?php
            $newTimeChosen = date('g:ia', strtotime($timeToDec));
            echo $newTimeChosen;
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
