<?php ini_set('display_errors', 'On'); ?>
<?php

//returns all eligible times to decatur/from anacortes for a given date

//connect to sql server with global $conn
require 'dbConnect.php';

date_default_timezone_set('America/Los_Angeles');


$today = date("Y-m-d");
$date = new DateTime($today);
$date->modify('+1 day');
$tomorrow = $date->format("Y-m-d");

$manDate = $_POST['manifestDate'];

$qry = $conn->prepare("SELECT DISTINCT departAnacortes FROM boatsDNW
    WHERE departDate = ?
    AND isCancelled = 0
    ORDER BY departAnacortes ASC");
 qry->execute(array($manDate));
}

$outTime = $qry->fetchAll();

$timeArray = array();

foreach($outTime as $row){
    $timeArray[] = $row['departAnacortes'];
}

$json = json_encode($timeArray);
echo $json;

//close connection
//$conn = null;

?>