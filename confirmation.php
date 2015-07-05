<?php ini_set('display_errors', 'On'); ?>
<?php
//if post exists
if($_POST){
	//connect to sql server with global $conn
	require 'dbConnect.php';

	//set variables
	$homeowner = $_POST['homeowner'];
	$guest = $_POST['guest'];
	$email = $_POST['email'];
	$phone = $_POST['phone'];
	$numAdultHomeowner = $_POST['numAdultHomeowner'];
	$numChildHomeowner = $_POST['numChildHomeowner'];
	$numAdultGuest = $_POST['numAdultGuest'];
	$numChildGuest = $_POST['numChildGuest'];
	$toDecaturDate = $_POST['toDecaturDate'];
	$toAnacortesDate = $_POST['toAnacortesDate'];
	$toDecaturTime = $_POST['decaturTime'];
	$toAnacortesTime = $_POST['anacortesTime'];
	$comment = $_POST['comment'];
	if($_POST['checkGuest']){
		$isGuest = 1;
	}
	else{
		$isGuest = 0;
	}

	$totalNumber = $numAdultHomeowner + $numChildHomeowner + $numAdultGuest + $numChildGuest;
	$cost = ($numAdultHomeowner*15) + ($numChildHomeowner*10) + ($numAdultGuest*35) + ($numChildGuest*20);

	if(!$toDecaturDate){
		$toDecaturDate = '';
		$printDecDate = '';
	}
	else{
		$dateObj = new DateTime($toDecaturDate);
		$printDecDate = $dateObj->format('l, F jS');
	}
	if(!$toAnacortesDate){
		$toAnacortesDate = '';
		$printAnaDate = '';
	}
	else{
		$dateObj = new DateTime($toAnacortesDate);
		$printAnaDate = $dateObj->format('l, F jS');
	}
	if(!$toDecaturTime){
		$toDecaturTime = '';
		$printDecTime = '';
	}
	else{
		$dateObj = new DateTime($toDecaturTime);
		$printDecTime = $dateObj->format('g:ia');
	}
	if(!$toAnacortesTime){
		$toAnacortesTime = '';
		$printAnaTime = '';
	}
	else{
		$dateObj = new DateTime($toAnacortesTime);
		$printAnaTime = $dateObj->format('g:ia');
	}

	//array of letters for confirmation code
	//"G" removed purposefully due to its prominent place in multiple 6 letter hate words.
	$charArray = array("A","B","C","D","E","F","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
	//confCode will be six random letters with repeats allowed
	$confLength = 6;
	//the code will loop until a valid conf code (non-duplicate is found)
	$ccDup = 1;
	while ($ccDup){
		$confCode = '';
		while($confLength > 0){
			$confCode .= $charArray[mt_rand(0,24)];
			$confLength--;
		}
		$test = $conn->query("SELECT DISTINCT confirmationCode FROM reservationsDNW WHERE confirmationCode = '".$confCode."'");
		$otherCode = $test->fetch();
		if (!$otherCode){
			$ccDup = 0;
		}
	}

	//start php session
	session_start();

	$allData = array(
		"sessionSet" => 'true',
		"confirmationCode" => $confCode,
		"homeownerName" => $homeowner,
		"guestName" =>$guest,
		"isGuest" => $isGuest,
		"email" => $email,
		"phone" => $phone,
		"numAdultHomeowner" => $numAdultHomeowner,
		"numChildHomeowner" => $numChildHomeowner,
		"numAdultGuest" => $numAdultGuest,
		"numChildGuest" => $numChildGuest,
		"totalNumber" => $totalNumber,
		"dateToDecatur" => $toDecaturDate,
		"dateToAnacortes" => $toAnacortesDate,
		"timeToDecatur" => $toDecaturTime,
		"timeToAnacortes" => $toAnacortesTime,
		"comments" => $comment,
		"paypal" => 'N',
		"cost" => $cost,
		"printDecDate" => $printDecDate,
		"printAnaDate" => $printAnaDate,
		"printDecTime" => $printDecTime,
		"printAnaTime" => $printAnaTime,
	);

	//set php session var
	$_SESSION['dataArray'] = $allData;

	//encode php array as json so it can be passed to javascript and local storage
	$json = json_encode($allData);

	//close connection
	$conn = null;

}
//redirect if someone came here by accident/entering url
else{
	header("Location: http://www.flessner.org/-jonTest/dnwC.html");
	die();
}

?>
<html>
	<head>
		<title>Confirm Details</title>
		<meta charset = "UTF-8">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="boatRes.css">
		<script>
			$(function (){
				//clear any old sessionStorage, no longer needed
				sessionStorage.clear();
				//get php data, set html session storage
				<?php echo "var allInfo = " . $json . ";";?>
				for (var key in allInfo){
					sessionStorage.setItem(key, allInfo[key]);
				}
				//display confirmation data
				$('#homeowner').html("Homeowner Name: " + sessionStorage.getItem('homeownerName'));
				if(sessionStorage.getItem('isGuest') !== '0'){
					$('#guest').html("Guest Name: " + sessionStorage.getItem('guestName'));
				}
				$('#email').html("Email: " + sessionStorage.getItem('email'));
				//display phone with hyphens aaa-ccc-nnnn
				var area = allInfo['phone'].substr(0, 3);
				var co = allInfo['phone'].substr(3, 3);
				var num = allInfo['phone'].substr(6, 4);
				var telStr = '(' + area + ')-' + co + '-' + num;
				$('#phone').html("Phone: " + telStr);
				//display traveler details
				var nAH = allInfo['numAdultHomeowner'];
				var nCH = allInfo['numChildHomeowner'];
				var nAG = allInfo['numAdultGuest'];
				var nCG = allInfo['numChildGuest'];
				var tab = "&nbsp;&nbsp;&nbsp;&nbsp;"
				var travStr = "Travelers: " + allInfo['totalNumber'] + " total<br>";
				var add = '';
				if(nAH > 0){
					if(nAH > 1){
						add = tab + nAH + ' Adult Homeowners<br>';
						travStr = travStr + add;
					}
					else{
						add = tab + nAH + ' Adult Homeowner<br>';
						travStr = travStr + add;
					}
				}
				if(nCH > 0){
					if(nAH > 1){
						add = tab + nCH + ' Child Homeowners<br>';
						travStr = travStr + add;
					}
					else{
						add = tab + nCH + ' Child Homeowner<br>';
						travStr = travStr + add;
					}
				}
				if(nAG > 0){
					if(nAG > 1){
						add = tab + nAG + ' Adult Guests<br>';
						travStr = travStr + add;
					}
					else{
						add = tab + nAG + ' Adult Guest<br>';
						travStr = travStr + add;
					}
				}
				if(nCG > 0){
					if(nCG > 1){
						add = tab + nCG + ' Child Guests<br>';
						travStr = travStr + add;
					}
					else{
						add = tab + nCG + ' Child Guest<br>';
						travStr = travStr + add;
					}
				}
				$('#travellers').html(travStr);
				//display trip details
				var tripStr = '';
				//case for round trip
				var printDecDate = allInfo['printDecDate'];
				var printDecTime = allInfo['printDecTime'];
				var printAnaDate = allInfo['printAnaDate'];
				var printAnaTime = allInfo['printAnaTime'];
				if(printDecDate && printAnaDate){
					tripStr = "Round Trip - <br>&nbsp;&nbsp;&nbsp;&nbsp;Going to Decatur Island on " + printDecDate + " at " + printDecTime + "<br>&nbsp;&nbsp;&nbsp;&nbsp;Going to Anacortes on " + printAnaDate + " at " + printAnaTime;
					$('#trip').html(tripStr);
				}
				//case for one way to Decatur
				else if(printDecDate){
					tripStr = "One way to Decatur Island on " + printDecDate + " at " + printDecTime;
					$('#trip').html(tripStr);
				}
				//case for one way to Anacortes
				else{
					tripStr = "One way to Anacortes on " + printAnaDate + " at " + printAnaTime;
					$('#trip').html(tripStr);
				}
				//print total cost
				$('#cost').html("Total cost: $" + sessionStorage.getItem('cost'));

				//set paypal cost - formula to calculate with %3 fee
				//cast as Number
				var paypalTotal = new Number(sessionStorage.getItem('cost'));
				//add 30 cent flat fee
				paypalTotal += .30;
				// calculate 2.9% paypal fee
				paypalTotal /= .971;
				//round to two decimal places
				paypalTotal = +paypalTotal.toFixed(2); //the plus allows us to return as number - doesn't do addition
				//display to user
				$('#paypalCost').html("Pay now with PayPal: $" + (paypalTotal));
				//reiterate later cost
				$('#laterCost').html("Pay later by check or homeowner charge: $" + sessionStorage.getItem('cost'));

				//on submit
				$('#confirm').on('submit', function(e){
					//clear any error msg
					$('#selectPayError').html("");
					//if no radio button selected
					if(!$('[name="payType"]').is(':checked')){
						//show error msg, prevent default
						$('#selectPayError').html("You must select a payment option.");
						e.preventDefault();
					}
					//payment selected, submit
					$('#confirm').serialize();
				});
			});

		</script>
	</head>
	<body>
		<div id="homeowner"></div>
		<div id="guest"></div>
		<div id="email"></div>
		<div id="phone"></div>
		<div id="travellers"></div>
		<div id="trip"></div>
		<div id="cost"></div>
		<button onclick="window.location.replace('/-jonTest/dnwC.html')">Go Back</button>
		<form id="confirm" action="sendRes.php" method="post">
			<div id="selectPayment">
				<p>Select payment option:</p>
				<p><input type="radio" name="payType" id="paypal" value="P"><div id="paypalCost"></div></p>
				<p><input type="radio" name="payType" id="later" value="N"><div id="laterCost"></div></p>
				<div id="selectPayError"></div>
			</div>
			<p><input id="theSubmit" type="submit" value="Confirm Reservation"><p>
		</form>
	</body>
</html>
