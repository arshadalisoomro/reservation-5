<?php ini_set('display_errors', 'On'); ?>
<?php

//returns all eligible times to decatur/from anacortes for a given date

//connect to sql server with global $conn
require 'dbConnect.php';

$manDate = $_POST['manifestDate'];

//do we want her to be able to see cancelled boats?
$qry = $conn->prepare("SELECT DISTINCT departAnacortes, departDecatur FROM boatsDNW
    WHERE departDate = ?
    AND isCancelled = 0
    ORDER BY departAnacortes ASC");

$qry->execute(array($manDate));

$outTime = $qry->fetchAll();

$timeArray = array();

foreach($outTime as $row){
    $timeArray[] = $row['departAnacortes'];
    $timeArray[] = $row['departDecatur'];
}

$json = json_encode($timeArray);
echo $json;

//close connection
$conn = null;

?>
