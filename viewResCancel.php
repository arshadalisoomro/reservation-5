<?php ini_set('display_errors', 'On'); ?>
<?php

//this script adds a non-scheduled boat

//set url variables for redirect if no POST or no Confirm
$admin_url="https://www.flessner.org/-jonTest/admin_tools/viewResSelect.php";

//connect to sql server with global $conn
require 'dbConnect.php';

//set timezone to west coast
date_default_timezone_set('America/Los_Angeles');

if (isset($_POST['submit'])) {

   session_start();
   //put data into local var
   $confirmationCode = $_SESSION['confirmationCode'];
   $numAdultHomeowners = $_SESSION['numAdultHomeowners'];
   $numChildHomeowners = $_SESSION['numChildHomeowners'];
   $numAdultGuests = $_SESSION['numAdultGuests'];
   $numChildGuests = $_SESSION['numChildGuests'];
   $dateToDecatur = $_SESSION['dateToDecatur'];
   $timeToDecatur = $_SESSION['timeToDecatur'];;
   $dateToAnacortes = $_SESSION['dateToAnacortes'];
   $timeToAnacortes = $_SESSION['timeToAnacortes'];
   $paypal = $_SESSION['paypal'];
   $homeownerName = $_SESSION['homeownerName'];
   $guest = $_SESSION['guestName'];
   $email = $_SESSION['email'];
   $cost = $_SESSION['cost'];

   //calculate number of passengers
   $passengerCount = $numAdultHomeowners + $numChildHomeowners +
                     $numAdultGuests + $numChildGuests;

   //update boatsDNW to subtract passengers based on canceled reservation
   if (isset($dateToDecatur)) {
     $decTime = $timeToDecatur->format("H:i:s");
     $updateAnaPassengers = "UPDATE boatsDNW set fromAnacortesCount =
               fromAnacortesCount - '$passengerCount'
               where departDate = '$dateToDecatur' and
               departAnacortes = '$decTime'";
     $conn->query($updateAnaPassengers);
   }

   if (isset($dateToAnacortes)) {
     $anaTime = $timeToAnacortes->format("H:i:s");
     $updateDecPassengers = "UPDATE boatsDNW set fromDecaturCount =
               fromDecaturCount - '$passengerCount'
               where departDate = '$dateToAnacortes' and
               departDecatur = '$anaTime'";
    $conn->query($updateDecPassengers);
   }

   $deleteRes = "DELETE from reservationsDNW WHERE confirmationCode = '$confirmationCode'";
   $conn->query($deleteRes);


   //send Kathy mail if reservation was paid by paypal
   if ($paypal = "Y") {
     //set cost with 2 decimal places for displaying in email
     $costDisplay = number_format($cost, 2);
     require_once('PHPMailer/PHPMailerAutoload.php');

     $text1 = "A reservation paid by Paypal has been canceled on: " .
       $today = date("m-d-Y");
     $text2 = "The confirmation code is: " .
       $confirmationCode;
     $text3 = "The Homeowner name is: " .
      $homeownerName;
     $text4 = "The Guest name is: " .
      $guestName;
     $text5 = "The email address is: " .
      $email;
     $text6 = "The amount charged is: " .
      $costDisplay;
     $text7 = "You should be contacted regarding a refund request.";

     $mail = new PHPMailer(); // create a new object
     //  $mail->isSMTP();
     // debugging: 2 = errors and messages, 1 = messages only
     $mail->SMTPDebug = 2;
     $mail->IsHTML(true);
     $mail->SetFrom("reservations@flessner.org");
     $mail->Subject = "Paypal reservation canceled";
     $mail->Body = $text1 . "<br>" . $text2 . "<br>" . $text3 .
       "<br>". $text4 . "<br>". $text5 . "<br>". $text6 . "<br>". $text7;
    // replace this with Kathy's email address
     $mail->AddAddress("suefle@hotmail.com");
     if(!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
     }
   }

   //unset session var as no longer needed
   session_unset();
   //close the connection
   $conn = null;
}
else {
  //unset session var because set in previous page
  session_unset();
  //close the connection
  $conn = null;
  //return to cancel res select page
  header("Location: $admin_url");
  die();
}

?>

<html>
    <head>
        <title>Cancel Reservation</title>
        <meta charset = "UTF-8"></meta>
            <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="css/viewRes.css"></link>

     </head>
     <body>
            <br />
            <br />
            <a>The reservation with confirmation code
            <?php
              echo $confirmationCode;
            ?>
            <br />
            has been canceled. </a>
            <br />
            <br />
            <p>
            <form id="confirm" action="viewResSelect.php">
            <input type="submit" name="submit" value="Return"></input>
            </form>
            </p>
    </body>
</html>
