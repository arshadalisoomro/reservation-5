<?php ini_set('display_errors', 'On'); ?>
<?php
//redirect if not post - user arrived by entering url
if($_POST){
	//connect to sql server with global $conn
	require 'dbConnect.php';
	//start session to retrieve data
	session_start();
	//put data into local var
	$dataArray = $_SESSION['dataArray'];
	//unset session var as no longer needed
	unset($_SESSION['dataArray']);
	//get total number since will be needed
	$totalNumber = $dataArray['totalNumber'];
	//set payment option from confirmation page choice
	$dataArray['paypal'] = $_POST['payType'];

	//remove data from array that will not be inserted into sql
	unset($dataArray['sessionSet'], $dataArray['totalNumber'], $dataArray['printDecDate'], $dataArray['printAnaDate'], $dataArray['printDecTime'], $dataArray['printAnaTime']);

	//alter paypal if needed from post var
	foreach ($dataArray as $key => $val){
		if(strlen($val) === 0 && $key !== 'guestName' && $key !== 'comments'){
			$dataArray[$key] = null;
		}
	}
	unset($key, $val); //unsetting $key and $val so they don't persist

	//change associative array into numerical array for execute
	$insertArray = array_values($dataArray);

	//NEEDS BETTER ERROR HANDLING
	//update passenger count on boatsDNW
	if($dataArray['dateToDecatur']){
		$decBoatCount = $conn->prepare("UPDATE boatsDNW SET fromAnacortesCount = fromAnacortesCount + ? WHERE departDate = ? AND departAnacortes = ?");
		$decBoatCount->execute(array($totalNumber, $dataArray['dateToDecatur'], $dataArray['timeToDecatur']));
	}
	if($dataArray['dateToAnacortes']){
		$anaBoatCount = $conn->prepare("UPDATE boatsDNW SET fromDecaturCount = fromDecaturCount + ? WHERE departDate = ? AND departDecatur = ?");
		$anaBoatCount->execute(array($totalNumber, $dataArray['dateToAnacortes'], $dataArray['timeToAnacortes']));
	}
	
	//insert data into table
	$qry = $conn->prepare("INSERT INTO reservationsDNW(confirmationCode, homeownerName, guestName, isGuest, email, phone, numAdultHomeowners, numChildHomeowners, numAdultGuests, numChildGuests, dateToDecatur, dateToAnacortes, timeToDecatur, timeToAnacortes, comments, paypal, cost) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
	//remaining data in dataArray ready to be sent to server, originally set in confirmation.php
	if(!$qry->execute($insertArray)){
		echo "\ninsert failed\n";
		echo "\nPDO::errorInfo():\n";
		print_r($qry->errorInfo());
		die();

	}
	else{
		echo 'insert complete';
	}

	$conn = null;

	//send on to completed page (or paypal if needed)
	if($dataArray['paypal'] === "P"){
        echo 'now we would redirect to paypal';
        //header("Location: http://www.flessner.org/-jonTest/paypal.html");
	}
	else{
		header("Location: http://www.flessner.org/-jonTest/resComplete.html");
	}
	

}
//redirect if someone came here by accident/entering url
else{
	header("Location: http://www.flessner.org/-jonTest/dnwC.html");
	die();
}

?>
