<?php ini_set('display_errors', 'On'); ?>
<?php
//redirect if not post - user arrived by entering url
//set url variables for redirects
$reservations_url = "http://www.flessner.org/-jonTest/reservations.html";
$reservation_complete_url = "http://www.flessner.org/-jonTest/resComplete.html";
$paypal_url = "http://www.flessner.org/-jonTest/paypal.html";

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
	unset($dataArray['sessionSet'],
		$dataArray['totalNumber'],
		$dataArray['printDecDate'],
		$dataArray['printAnaDate'],
		$dataArray['printDecTime'],
		$dataArray['printAnaTime']);

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
		$decBoatCount = $conn->prepare("UPDATE boatsDNW
			SET fromAnacortesCount = fromAnacortesCount + ?
			WHERE departDate = ? AND departAnacortes = ?");
		$decBoatCount->execute(array($totalNumber,
			$dataArray['dateToDecatur'],
			$dataArray['timeToDecatur']));
	}
	if($dataArray['dateToAnacortes']){
		$anaBoatCount = $conn->prepare("UPDATE boatsDNW
			SET fromDecaturCount = fromDecaturCount + ?
            WHERE departDate = ? AND departDecatur = ?");
		$anaBoatCount->execute(array($totalNumber,
			$dataArray['dateToAnacortes'],
			$dataArray['timeToAnacortes']));
	}
	
	//insert data into table
    $qry = $conn->prepare("INSERT INTO reservationsDNW(
        confirmationCode,
        homeownerName,
        guestName,
        isGuest,
        email,
        phone,
        numAdultHomeowners,
        numChildHomeowners,
        numAdultGuests,
        numChildGuests,
        dateToDecatur,
        dateToAnacortes,
        timeToDecatur,
        timeToAnacortes,
        comments,
        paypal,
        cost,
        timestamp)
        VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
	//remaining data in dataArray ready to be sent to server, originally set in confirmation.php
	if(!$qry->execute($insertArray)){
		echo "\ninsert failed\n";
		echo "\nPDO::errorInfo():\n";
		print_r($qry->errorInfo());
		die();

	}
	else{
		//echo 'insert complete';
		//format times
		if ($dataArray['dateToDecatur']){
			$tmp_time = strtotime($dataArray['dateToDecatur']);
			$DecaturDate = date('l M jS, Y', $tmp_time);
			$tmp_time = strtotime($dataArray['timeToDecatur']);
			$DecaturTime = date('g:ia', $tmp_time);
		}
		if ($dataArray['dateToAnacortes']){
			$tmp_time = strtotime($dataArray['dateToAnacortes']);
			$AnacortesDate = date('l M jS, Y', $tmp_time);
			$tmp_time = strtotime($dataArray['timeToAnacortes']);
			$AnacortesTime = date('g:ia', $tmp_time);
		}
        
        //code to send email using PHPMailer
        
        require_once('PHPMailer/PHPMailerAutoload.php');
                
        $success = "Your reservation to DNW has been successfully made.";
        $codeEmail = "Your confirmation code is: " . $dataArray['confirmationCode'];
		if(!$dataArray['dateToDecatur']){
            $itinerary = "The reservation is one way leaving Decatur on " . $AnacortesDate . " at " . $AnacortesTime . ".";
		}
		else if(!$dataArray['dateToAnacortes']){
			$itinerary = "The reservation is one way leaving Anacortes on " . $DecaturDate . " at " . $DecaturTime . ".";
		}
		else{
			$itinerary = "Depart Anacortes on " . $DecaturDate . " at " . $DecaturTime . ".<br>" . "Depart Decatur on " . $AnacortesDate . " at " . $AnacortesTime . ".";
		}
		if($totalNumber < 2){
			$travellers = "There is one passenger on this reservation.";
		}
		else{
			$travellers = "There are " . $totalNumber . " passengers on this reservation.";
		}
		$enjoy = "Enjoy your trip!";
        
        $mail = new PHPMailer(); // create a new object
        $mail->isSMTP(); // enable SMTP
        $mail->SMTPDebug = 0; // debugging: 2 = errors and messages, 1 = messages only
        $mail->SMTPAuth = true; // authentication enabled
        $mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for GMail use ssl or tls
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587; // 587 or 465
        $mail->IsHTML(true);
        $mail->Username = "sueflessner@gmail.com";
        $mail->Password = "***";  //keeping this out of github, replace only when publishing
        $mail->SetFrom("sueflessner@gmail.com");
        $mail->Subject = "DNW Boat Reservation";
        $mail->Body = $success . "<br>". $codeEmail . "<br>". $itinerary . "<br>". $travellers . "<br>". $enjoy;
        $mail->AddAddress($dataArray['email']);
        if(!$mail->Send()) {
           echo "Mailer Error: " . $mail->ErrorInfo;
        }
        else {
           if($dataArray['paypal'] === "P"){
               echo 'now we would redirect to paypal';
               header("Location: $paypal_url");
           }
	       else{
		       header("Location: $reservation_complete_url");
	       }
        }
    }
	$conn = null;
}
//redirect if someone came here by accident/entering url
else{
	header("Location: $reservations_url");
	die();
    }
?>
