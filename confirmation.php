<?php ini_set('display_errors', 'On'); ?>
<?php

//this page displays the information the user set for their reservation
//so they can determine if it is all correct, it then has the user
//select how they would like to pay after calculating the cost

//set url variables for redirect if no POST
$reservations_url="http://www.flessner.org/-jonTest/reservations.html";
//if post exists
if($_POST){
    //connect to sql server with global $conn
    require 'dbConnect.php';
    //timezone is always west coast
    date_default_timezone_set('America/Los_Angeles');

    // ========== set variables ===========
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
    //set binary guest variable
    if($_POST['checkGuest']){
        $isGuest = 1;
    }
    else{
        $isGuest = 0;
    }
    //calculate total number travelers
	 
    $totalNumber = $numAdultHomeowner +
                   $numChildHomeowner +
                   $numAdultGuest +
                   $numChildGuest;


       //calculate total cost
	   //see if one way to Anacortes; if so, cost is half price
    if(!$toDecaturDate){
	   $cost = ($numAdultHomeowner*7.5) +
               ($numChildHomeowner*5) +
               ($numAdultGuest*17.5) +
               ($numChildGuest*10);
    }
	else{  
       $cost = ($numAdultHomeowner*15) +
               ($numChildHomeowner*10) +
               ($numAdultGuest*35) +
               ($numChildGuest*20);
    }

    // use Tcost for testing on live PayPal account
	// change it in dataArray below for in place of $cost
    $Tcost = .1;

          
    //decatur date
    if(!$toDecaturDate){
        $toDecaturDate = '';
        $printDecDate = '';
    }
    else{
        $dateObj = new DateTime($toDecaturDate);
        $printDecDate = $dateObj->format('l, F jS');
    }
    //anacortes date
    if(!$toAnacortesDate){
        $toAnacortesDate = '';
        $printAnaDate = '';
    }
    else{
        $dateObj = new DateTime($toAnacortesDate);
        $printAnaDate = $dateObj->format('l, F jS');
    }
    //decatur time
    if(!$toDecaturTime){
        $toDecaturTime = '';
        $printDecTime = '';
    }
    else{
        $dateObj = new DateTime($toDecaturTime);
        $printDecTime = $dateObj->format('g:ia');
    }
    //anacortes time
    if(!$toAnacortesTime){
        $toAnacortesTime = '';
        $printAnaTime = '';
    }
    else{
        $dateObj = new DateTime($toAnacortesTime);
        $printAnaTime = $dateObj->format('g:ia');
    }

    //array of letters for confirmation code
    //"G" removed purposefully due to its prominent place
    //in multiple 6 letter hate words.
    $charArray = array("A","B","C","D","E","F","H","I","J","K","L","M","N",
                           "O","P","Q","R","S","T","U","V","W","X","Y","Z");
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
                $test = $conn->query("SELECT DISTINCT confirmationCode
                                      FROM reservationsDNW
                                      WHERE confirmationCode =
                                      '".$confCode."'");

        $otherCode = $test->fetch();
        if (!$otherCode){
            $ccDup = 0;
        }
    }

    // we have default timezone set as America/Los_Angeles
    $timestamp = date("Y-m-d h:i:s");

    // ============ all variables set ==========

    //start php session
    session_start();

    //put variables in php array
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
        "timestamp" => $timestamp,
    );
    
    //set php session var
    $_SESSION['dataArray'] = $allData;

    //encode php array as json so it can be passed to
    //javascript and local storage
    $json = json_encode($allData);

    //close connection
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
    <meta charset = "UTF-8">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="css/boatRes.css">
    <!-- echo php info into js var -->
    <script><?php echo "var allInfo = " . $json . ";";?></script>
    <script src="confirmation.js"></script>    
</head>
<body>
    <div id="homeowner"></div>
    <div id="guest"></div>
    <div id="email"></div>
    <div id="phone"></div>
    <div id="travellers"></div>
    <div id="trip"></div>
	<div id="comments"></div>
    <p><button onclick="window.location.replace('/-jonTest/reservations.html')">
        Go Back
    </button></p>
	<br />   

		   	 
    <p>	
	<form id="confirm" action="paymentOptions.php" method="post"> 
	<input type="submit" name="submit" value="Confirm and Display Payment Options"> 	   
	</form>
    </p>
  
</body>
</html>
