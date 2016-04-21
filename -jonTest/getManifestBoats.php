<?php ini_set('display_errors', 'On'); ?>
<?php

//returns all non-cancelled boats in the boat database for viewing/printing manifests

//connect to sql server with global $conn
require 'dbConnect.php';

date_default_timezone_set('America/Los_Angeles');

$today = date("Y-m-d");
 
$date = new DateTime($today);
$date->modify('+30 day');

$maxDay = $date->format("Y-m-d");

$qry = $conn->prepare("SELECT DISTINCT departDate FROM boatsDNW
      WHERE isCancelled = 0");

$qry->execute(array($today, $maxDay));

$outDate = $qry->fetchAll();

$boatArray = array();

foreach($outDate as $row) {
    $boatArray[] = $row['departDate'];
}

$json = json_encode($boatArray);
echo $json;

//close connection
$conn = null;

?>