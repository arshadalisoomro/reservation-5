<?php ini_set('display_errors', 'On'); ?>
<?php

//this script returns the passenger count based for a given boat

//connect to sql server with global $conn
require 'dbConnect.php';

$maxBoat = 55;

$date = $_GET['date'];
$time = $_GET['time'];
$departFrom = $_GET['depart'];

//case to anacortes, departing decatur
if($departFrom === 'd'){
    $qry = $conn->prepare("SELECT fromDecaturCount from boatsDNW 
        WHERE departDate = ? AND departDecatur = ?");
    $qry->execute(array($date, $time));
    $res = $qry->fetch();
}
//case to decatur
else{
    $qry = $conn->prepare("SELECT fromAnacortesCount from boatsDNW 
        WHERE departDate = ? AND departAnacortes = ?");
    $qry->execute(array($date, $time));
    $res = $qry->fetch();
}


$json = json_encode($res);
echo $json;

//close connection
$conn = null;

?>