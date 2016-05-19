<?php ini_set('display_errors', 'On'); ?>
<?php

//this script adds a non-scheduled boat

//connect to sql server with global $conn
require '../dbConnect.php';

//set timezone to west coast
date_default_timezone_set('America/Los_Angeles');

if (isset($_POST['cancelBoatDate'])) {
    $dateChosen = $_POST['cancelBoatDate'];
    $timeChosen = $_POST['cancelBoatTime'];
}
$qry = $conn->prepare("SELECT fromAnacortesCount, fromDecaturCount FROM boatsDNW
  where departDate = '$dateChosen' and departAnacortes = '$timeChosen'");
$qry->execute();
$result = $qry->fetchAll();

//$boatArray = array();

foreach($result as $row) {
    $anaCount = $row['fromAnacortesCount'];
    $decCount= $row['fromDecaturCount'];
}

//$json = json_encode($boatArray);
//echo $json;


//start php session
session_start();
//set php session var
$_SESSION['dateChosen'] = $dateChosen;
$_SESSION['timeChosen'] = $timeChosen;

//close the connection
$conn = null;

?>

<html>
    <head>
        <title>Confirm Boat to Cancel</title>
        <meta charset = "UTF-8"></meta>
        <link rel="stylesheet" type="text/css" href="../css/admin.css"></link>

     </head>
     <body>
       <?php
       if ($anaCount != "0" || $decCount != "0") {
         echo "This boat has passengers booked.";
         echo "<br>";
         echo "<br>";
         echo "There are ";
         echo $anaCount;
         if ($anaCount == "1") {
           echo " passenger booked from Anacortes and ";
         }
         else {
           echo " passengers booked from Anacortes and ";
         }
         echo $decCount;
         if ($decCount == "1") {
         echo " passenger booked from Decatur.";
         }
         else {
         echo " passengers booked from Decatur.";
         }
       }
       else {
         echo "There are no passengers booked on these boats.";
       }

       ?>
       <br/>
       <br/>
       <a>Please confirm that you would like to cancel the following boats:</a>
       <br/>
       <br/>
       <?php
         $newDateChosen = date('l, m/d/Y', strtotime($dateChosen));
         echo $newDateChosen;
       ?>
       <br />
       <br />
       <a>Departing Anacortes at</a>
       <?php
       $newTimeChosen = date('g:ia', strtotime($timeChosen));
       echo $newTimeChosen;
       ?>
       <br />
       <br />
       <a>and the return from DNW to Anacortes at</a>
       <?php
       $dateMod = new DateTime($newTimeChosen);
       $dateMod->add(new DateInterval('PT1H'));
       $returnTimeChosen = $dateMod->format('g:ia');
       echo $returnTimeChosen;
       ?>
       <br />
       <br />
       <p>
       <!--<form id="confirm action="specialBoatAdd.php" method="post">-->
       <form action="cancelBoat.php" method="post">
       <input type="submit" name="submit" value="Confirm and Cancel Boat"></input>
       </form>
       <br/>
       <br/>
       <form action="admin_form.php">
       <input type="submit" name="submit" value="Return without Canceling"></input>
       </form>
       </p>
</body>
</html>
