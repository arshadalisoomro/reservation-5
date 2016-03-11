<?php ini_set('display_errors', 'On'); ?>
<?php

//sends reservation to server after confirmation.php

//redirect if not post - user arrived by entering url
//set url variables for redirects
$reservations_url = "http://www.flessner.org/-jonTest/reservations.html";

if($_POST){
    //connect to sql server with global $conn
    require 'dbConnect.php';
    //start session to retrieve data
    session_start();
    //put data into local var
    $dataArray = $_SESSION['dataArray'];
    //get total number since will be needed
    $totalNumber = $dataArray['totalNumber'];
    $cost = $dataArray['cost'];    
	$costDisplay = number_format($cost, 2);
    $confCode= $dataArray['confirmationCode'];
    $dataArray['paypal'] = "N";
    
    //calculate cost with paypal fees included
    $paypalTot = $cost;
    //add 30 cent flat fee and 2.9% 
	//this math works since paypal will apply fees to this larger amount
	//but all will be covered by the buyer
    $paypalTot = ($cost + .30) / .971;
	$paypalTotRound = round($paypalTot,2);
	$paypalTotal = number_format($paypalTotRound, 2);

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
    //remaining data in dataArray ready to be sent to server
    //this is originally set in confirmation.php
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
        $codeEmail = "Your confirmation code is: " .
            $dataArray['confirmationCode'];
        //$onboatCost = "Trip cost (if not already paid by PayPal): $" .
		//    $dataArray['cost'];
        $onboatCost = "Trip cost (if not already paid by PayPal): $" .
		    $costDisplay;
        if(!$dataArray['dateToDecatur']){
            $itinerary = "The reservation is one way leaving Decatur on " .
            $AnacortesDate . " at " . $AnacortesTime . ".";
        }
        else if(!$dataArray['dateToAnacortes']){
            $itinerary = "The reservation is one way leaving Anacortes on " .
            $DecaturDate . " at " . $DecaturTime . ".";
        }
        else{
            $itinerary = "Depart Anacortes on " . $DecaturDate . " at " .
            $DecaturTime . ".<br>" . "Depart Decatur on " . $AnacortesDate .
            " at " . $AnacortesTime . ".";
        }
        if($totalNumber < 2){
            $travellers = "There is one passenger on this reservation.";
        }
        else{
            $travellers = "There are " . $totalNumber .
            " passengers on this reservation.";
        }
        $enjoy = "Enjoy your trip!";
		//adding Kathy's message 
		$query = $conn->query("SELECT RespondMsg FROM AppData WHERE ID = 1");	     
		$result = $query->fetch(PDO::FETCH_ASSOC);
		$addtlinfo = $result['RespondMsg'];
        
		        
        $mail = new PHPMailer(); // create a new object
        $mail->isSMTP();
        // debugging: 2 = errors and messages, 1 = messages only
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = true;
        // secure transfer enabled REQUIRED for GMail use ssl or tls
        //$mail->SMTPSecure = 'tls';
        $mail->Host = "webmail.flessner.org";
        // 587 or 465  (or 25 for mail from discount.asp)
        $mail->Port = 25;
        $mail->IsHTML(true);        
		$mail->Username = "reservations@flessner.org";
        //keeping this out of github, replace only when publishing
        $mail->Password = "****";       
        $mail->SetFrom("reservations@flessner.org");
        $mail->Subject = "DNW Boat Reservation";
        $mail->Body = $success . "<br>" . $codeEmail . "<br>" . $itinerary .
            "<br>". $travellers . "<br>".$onboatCost . "<br>". "<br>". $addtlinfo . "<br>" . 
			"<br>" . $enjoy;
        $mail->AddAddress($dataArray['email']);
        if(!$mail->Send()) {
           echo "Mailer Error: " . $mail->ErrorInfo;
        }
       // else {	
       //       header("Location: $reservation_complete_url");
       // }
    }
    //unset session var as no longer needed
    unset($_SESSION['dataArray']);
    $conn = null;
}
//redirect if someone came here by accident/entering url
else{
    header("Location: $reservations_url");
    die();
    }
?>
<html>
    <head>
        <title>Confirm Details</title>
        <meta charset = "UTF-8"></meta>
            <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'></link>
        <link rel="stylesheet" type="text/css" href="boatRes.css"></link>  
	</head>
    <body>
	    <br />
		<br />
        <p>Select payment option:</p>

        <!--  PayPal BUTTON CODE -->
        <p>
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post"> 
            <!-- <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post"> -->
            <input type="hidden" name="cmd" value="_xclick">
            <input type="hidden" name="business" value="paulfle@comcast.net">
            <!--  <input type="hidden" name="business" value="paulfle-facilitator@comcast.net"> -->
            <input type="hidden" name="item_name" value="DNW Boat Reservation"> 
            <input type="hidden" name="amount" value="<?= $paypalTotal ?>"> 
			<input type="hidden" name="custom" value="<?=$confCode ?>">
            <input type="hidden" name="notify_url" value="http://flessner.org/-jonTest/ipn.php">
			<!-- line below not needed because set in paypal account, was giving error here -->
            <!-- <input type="hidden" name="return" value="http://flessner.org/-jonTest/resComplete.html"> -->
            <input type="hidden" name="cancel_return" value="http://flessner.org/-jonTest/resComplete.html">
            <input type="image" name="submit" border="1" style="margin:0px 15px"
                   src="https://www.paypalobjects.com/webstatic/en_US/i/buttons/pp-acceptance-small.png"
                   alt="Check out with PayPal">
            Pay now by PayPal or Credit Card: $ <?= $paypalTotal ?>  <i>(PayPal fee included) </i>
            </form>  

			

        </p>
        <br />

        <!--  Pay Later BUTTON CODE -->
        <p>
            <form id="confirm" action="resComplete.html">
                <input type="submit" name="submit" value="Pay Later"></input>
                 Pay Later with by Check or Homeowner Charge: $ <?= $costDisplay ?>
                 <br />
                 <br />
                 <a style="padding-left:20px">
                     - Checks made out to "DNW Community Association" (No cash payments accepted)
                 </a>
                 <br />
                 <a style="padding-left:20px">
                     - Homeowner Charge (Option only for homeowners with accounts in good standing)
                 </a>
				 
              </form>
        </p>

    </body>
</html>

