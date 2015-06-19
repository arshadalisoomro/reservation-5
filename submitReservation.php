<?php ini_set('display_errors', 'On'); ?>
<?php
//if post exists
if($_POST){
	//connect to server
	$mysqli = new mysqli("localhost","jonath65","k6KeBVBeYgqN8b4eNcJB","jonath65_dnw");
	if (!$mysqli || $mysqli->connect_errno){
		echo "Connection error" . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	//set variables and escape strings for security
	$homeowner = mysqli_real_escape_string($mysqli, $_POST['homeowner']);
	$guest = mysqli_real_escape_string($mysqli, $_POST['guest']);
	$email = mysqli_real_escape_string($mysqli, $_POST['email']);
	$phone = mysqli_real_escape_string($mysqli, $_POST['phone']);
	$numAdultHomeowner = mysqli_real_escape_string($mysqli, $_POST['numAdultHomeowner']);
	$numChildHomeowner = mysqli_real_escape_string($mysqli, $_POST['numChildHomeowner']);
	$numAdultGuest = mysqli_real_escape_string($mysqli, $_POST['numAdultGuest']);
	$numChildGuest = mysqli_real_escape_string($mysqli, $_POST['numChildGuest']);
	$toDecaturDate = mysqli_real_escape_string($mysqli, $_POST['toDecaturDate']);
	$toAnacortesDate = mysqli_real_escape_string($mysqli, $_POST['toAnacortesDate']);
	$toDecaturTime = mysqli_real_escape_string($mysqli, $_POST['decaturTime']);
	$toAnacortesTime = mysqli_real_escape_string($mysqli, $_POST['anacortesTime']);
	$comment = mysqli_real_escape_string($mysqli, $_POST['comment']);
	if($_POST['checkGuest']){
		$isGuest = 1;
	}
	else{
		$isGuest = 0;
	}

	$totalNumber = $numAdultHomeowner + $numChildHomeowner + $numAdultGuest + $numChildGuest;

	if(!$toDecaturDate){
		$toDecaturDate = null;
	}
	if(!$toAnacortesDate){
		$toAnacortesDate = null;
	}
	if(!$toDecaturTime){
		$toDecaturTime = null;
	}
	if(!$toAnacortesTime){
		$toAnacortesTime = null;
	}

	$charArray = array("A","B","C","D","E","F","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
	//"G" removed purposefully due to its prominent place in multiple 6 letter hate words.
	$confLength = 6;
	$confCode = '';
	while($confLength > 0){
		$confCode .= $charArray[mt_rand(0,24)];
		$confLength--;
	}
	//check for duplicates here

	//insert into mysql database using mysqli with prepared statements
	$insert = $mysqli->prepare("INSERT INTO reservations(homeownerName, guestName, isGuest, email, phone, numAdultHomeowners, numChildHomeowners, numAdultGuests, numChildGuests, dateToDecatur, dateToAnacortes, timeToDecatur, timeToAnacortes, comments, confirmation) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
	$insert->bind_param("ssissiiiissssss", $homeowner, $guest, $isGuest, $email, $phone, $numAdultHomeowner, $numChildHomeowner, $numAdultGuest, $numChildGuest, $toDecaturDate, $toAnacortesDate, $toDecaturTime, $toAnacortesTime, $comment, $confCode);
	if(!$insert->execute())
		echo "Fail; errno(" . $mysqli->errno .") " . $mysqli->error;
	else{
		//format times
		if ($toDecaturDate){
			$timestamp = strtotime($toDecaturDate);
			$DecaturDate = date('l M jS, Y', $timestamp);
			$timestamp = strtotime($toDecaturTime);
			$DecaturTime = date('g:ia', $timestamp);
		}
		if ($toAnacortesDate){
			$timestamp = strtotime($toAnacortesDate);
			$AnacortesDate = date('l M jS, Y', $timestamp);
			$timestamp = strtotime($toAnacortesTime);
			$AnacortesTime = date('g:ia', $timestamp);
		}
		

		//send email here
		$success = "You're reservation to DNW has been successfully made.\r\n";
		$codeEmail = "You're confirmation code is: " . $confCode . "\r\n";
		if(!$toDecaturDate){
			$itinerary = "The reservation is one way leaving Deactur on " . $AnacortesDate . " at " . $AnacortesTime . ".\r\n";
		}
		else if(!$toAnacortesDate){
			$itinerary = "The reservation is one way leaving Anacortes on " . $DecaturDate . " at " . $DecaturTime . ".\r\n";
		}
		else{
			$itinerary = "Depart Anacortes on " . $DecaturDate . " at " . $DecaturTime . ".\r\n" . "Depart Decatur on " . $AnacortesDate . " at " . $AnacortesTime . ".\r\n";
		}
		if($totalNumber < 2){
			$travellers = "There is one traveller for this reservation.\r\n";
		}
		else{
			$travellers = "There are " . $totalNumber . " travellers on this reservation.\r\n";
		}
		$enjoy = "Enjoy your trip!";
		$message = $success . $codeEmail . $itinerary . $travellers . $enjoy;
		$subject = 'DNW Boat Reservation ' . $confCode;
		$headers = 'From: jonathanflessner@gmail.com' . "\r\n";
		mail($email,$subject,$message,$headers);
	}
	


	$jsonCode = json_encode($confCode);
	echo $jsonCode;

	$insert->close();
	$mysqli->close();
}
//redirect if someone came here by accident/entering url
else{
	header("Location: http://jonathanflessner.us");
	die();
}

?>