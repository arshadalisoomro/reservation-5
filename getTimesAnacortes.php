<?php ini_set('display_errors', 'On'); ?>
<?php

//connect to sql server with global $conn
require 'dbConnect.php';

$maxBoat = 55;

$today = date("Y-m-d");
$date = new DateTime($today);
$date->modify('+1 day');
$tomorrow = $date->format("Y-m-d");

$anaDate = $_POST['toAnacortesDate'];
$time = date("H:i:s");
$false = 0;

if ($anaDate == $tomorrow){
	$qry = $conn->prepare("SELECT DISTINCT departDecatur FROM boatsDNW WHERE departDate = ? AND isCancelled = 0 AND fromDecaturCount < ? AND departDecatur > ? ORDER BY departDecatur ASC");
	$qry->execute(array($anaDate, $time));
}
else {
	$qry = $conn->prepare("SELECT DISTINCT departDecatur FROM boatsDNW WHERE departDate = ? AND isCancelled = 0 AND fromDecaturCount < ? ORDER BY departDecatur ASC");
	$qry->execute(array($anaDate, $maxBoat));
}

$outTime = $qry->fetchAll();

$timeArray = array();

foreach($outTime as $row){
	$timeArray[] = $row['departDecatur'];
}

$json = json_encode($timeArray);
echo $json;

//close connection
$conn = null;
?>