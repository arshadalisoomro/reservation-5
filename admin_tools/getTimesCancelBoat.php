<?php ini_set('display_errors', 'On'); ?>
<?php

//returns all eligible times to decatur/from anacortes for a given date

//connect to sql server with global $conn
require '../dbConnect.php';

$cancelDate = $_POST['cancelBoatDate'];

// only need to get times from one direction - using from Anacortes
$qry = $conn->prepare("SELECT DISTINCT departAnacortes FROM boatsDNW
        WHERE departDate = ?
        AND isCancelled = 0
        ORDER BY departAnacortes ASC");
$qry->execute(array($cancelDate));

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
