<?php ini_set('display_errors', 'On'); ?>
<?php

//returns all eligible times to decatur/from anacortes for a given date

//connect to sql server with global $conn
require 'dbConnect.php';

date_default_timezone_set('America/Los_Angeles');

$maxBoat = 55;

$today = date("Y-m-d");
$date = new DateTime($today);
$date->modify('+1 day');
$tomorrow = $date->format("Y-m-d");

$decDate = $_POST['toDecaturDate'];
$time = date("H:i:s");

if ($decDate == $tomorrow){
    $qry = $conn->prepare("SELECT DISTINCT departAnacortes FROM boatsDNW
        WHERE departDate = ?
        AND isCancelled = 0
        AND fromAnacortesCount < ?
        AND departAnacortes > ?
        ORDER BY departAnacortes ASC");
    $qry->execute(array($decDate, $maxBoat, $time));
}
else {  
    $qry = $conn->prepare("SELECT DISTINCT departAnacortes FROM boatsDNW
        WHERE departDate = ?
        AND isCancelled = 0
        AND fromAnacortesCount < ?
        ORDER BY departAnacortes ASC");
    $qry->execute(array($decDate, $maxBoat));
}

$outTime = $qry->fetchAll();

$timeArray = array();

foreach($outTime as $row){
    $timeArray[] = $row['departAnacortes'];
}

$json = json_encode($timeArray);
echo $json;

//close connection
$conn = null;

?>