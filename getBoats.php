<?php ini_set('display_errors', 'On'); ?>
<?php

//connect to sql server with global $conn
require 'dbConnect.php';

$maxBoat = 55;

$today = date("Y-m-d");

$date = new DateTime($today);
$date->modify('+30 day');

$maxDay = $date->format("Y-m-d");

$qry = $conn->prepare("SELECT DISTINCT departDate FROM boatsDNW WHERE departDate > ? AND departDate <= ? AND isCancelled = 0 AND (fromAnacortesCount < ? OR fromDecaturCount < ?)");
$qry->execute(array($today, $maxDay, $maxBoat, $maxBoat));

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