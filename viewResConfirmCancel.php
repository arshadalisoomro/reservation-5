<?php ini_set('display_errors', 'On'); ?>
<?php

//this script confirms the canceling of a reservation
//after verifying that it is eligible to be canceled.

//set url variables for redirect if no POST or no Confirm
$admin_url="https://www.flessner.org/-jonTest/viewResSelect.php";

//set timezone to west coast
date_default_timezone_set('America/Los_Angeles');

if (isset($_POST['submit'])) {
   session_start();
   //put data into local var
   $confirmationCode = $_SESSION['confirmationCode'];
   $homeownerName = $_SESSION['homeownerName'];
   $guest = $_SESSION['guestName'];
   $email = $_SESSION['email'];
   $phone = $_SESSION['phone'];
   $numAdultHomeowners = $_SESSION['numAdultHomeowners'];
   $numChildHomeowners = $_SESSION['numChildHomeowners'];
   $numAdultGuests = $_SESSION['numAdultGuests'];
   $numChildGuests = $_SESSION['numChildGuests'];
   $dateToDecatur = $_SESSION['dateToDecatur'];
   $timeToDecatur = $_SESSION['timeToDecatur'];
   $dateToAnacortes = $_SESSION['dateToAnacortes'];
   $timeToAnacortes = $_SESSION['timeToAnacortes'];
   $paypal = $_SESSION['paypal'];
   $cost = $_SESSION['cost'];
   $comments = $_SESSION['comments'];


}
else {
  //return to select reservation page
  header("Location: $admin_url");
  die();
}

// set variables for today, tomorrow and current time
// used for comparing to reservation dates and times for checking 24 hour rule
$today = date("Y-m-d");
$date = new DateTime($today);
$date->modify('+1 day');
$tomorrow = $date->format("Y-m-d");
$time = date("H:i:s");
?>

<html>
    <head>
        <title>Confirm cancel reservation</title>
        <meta charset = "UTF-8"></meta>
    <!--    <script src="../libraries/jquery-2.2.3.min.js"></script> -->
        <link rel="stylesheet" type="text/css" href="css/viewRes.css"></link>

     </head>
     <body>
       <?php

      //if both dates are in the past the reservation cannot be deleted
      //this code works for one way trips also because Null dates equate to year 1969
       if ($dateToDecatur < $today && $dateToAnacortes < $today){
          echo "<br>";
          echo "Reservations with departure dates in the past cannot be canceled.";
          echo "<br>";
          echo "<br>";
          echo "<a id=link href=$admin_url>Return</a>";
          die();
        }
        //if either date is in the past (for round trips)
        //the reservation cannot be canceled online.
         if ((($dateToDecatur < $today && $dateToAnacortes > $today) &&
            (isset($dateToDecatur))) ||
            (($dateToAnacortes < $today && $dateToDecatur > $today) &&
            (isset($dateToAnacortes))))
             {
            echo "<br>";
            echo "Reservations with any leg in the past may not be canceled online.";
            echo "<br>";
            echo "Please contact Kathy to cancel the remaining portion of this reservation.";
            echo "<br>";
            echo "<br>";
            echo "<a id=link href=$admin_url>Return</a>";
            die();
            }

       // see if either date is today - if so, breaks the 24 hour rule
        if ($dateToDecatur == $today || $dateToAnacortes == $today) {
          echo "This reservation is within 24 hours of departure.";
          echo "<br>";
          echo "It cannot be canceled online.  Please contact Kathy.";
          echo "<br>";
          echo "<a id=link href=$admin_url>Return</a>";
          die();
        }
        // if to Decatur not null set time compare variable,
        // then check 24 hour rule if date equal tomorrow
        if ($dateToDecatur != "") {
            $decTime = $timeToDecatur->format("H:i:s");
        if($dateToDecatur == $tomorrow && $decTime < $time) {
           echo "This reservation is within 24 hours of departure.";
           echo "<br>";
           echo "It cannot be canceled online.  Please contact Kathy.";
           echo "<br>";
           echo "<a id=link href=$admin_url>Return</a>";
           die();
          }
        }
        // if to Anacortes not null set time compare variable,
        // then check 24 hour rule if date equal tomorrow
       if ($dateToAnacortes != "") {
           $anaTime = $timeToAnacortes->format("H:i:s");
         if($dateToAnacortes == $tomorrow && $anaTime < $time) {
            echo "This reservation is within 24 hours of departure.";
            echo "<br>";
            echo "It cannot be canceled online.  Please contact Kathy.";
            echo "<br>";
            echo "<a id=link href=$admin_url>Return</a>";
            die();
         }
       }

      // give warning if reservation was paid by PayPal
      // Kathy must give refunds, no way to automatically do this
       if ($paypal == "Y") {
         echo "This reservation was previously paid by PayPal.";
         echo "<br>";
         echo "The charges WILL NOT be automatically reversed.";
         echo "<br>";
         echo "You are responsible for contacting Kathy to set up the refund.";
         echo "<br>";
         echo "You have one year from the time of the original reservation to make the request.";
         echo "<br>";
         echo "<br>";
       }
       // proceed to confirm cancel if all tests passed
        ?>

            <a>Please confirm that you would like to cancel the following reservation:</a>
            <br/>
            <br/>
            <?php
            echo 'Confirmation Code: ';
            echo $confirmationCode;
            echo "<br>";
            echo 'Homeowner Name: ';
            echo $homeownerName;
            echo "<br>";
            if ($guestName !== "") {
              echo 'Guest Name: ';
              echo $guestName;
              echo "<br>";
            }
            echo 'Email: ';
            echo $email;
            echo "<br>";
            echo 'Phone: ';
            echo "(".substr($phone, 0, 3).") ".substr($phone, 3, 3)."-".substr($phone,6);
            echo "<br>";
            echo 'Number of Adult Homeowners: ';
            echo $numAdultHomeowners;
            echo "<br>";
            echo 'Number of Child Homeowners: ';
            echo $numChildHomeowners;
            echo "<br>";
            echo 'Number of Adult Guests: ';
            echo $numAdultGuests;
            echo "<br>";
            echo 'Number of Child Guests: ';
            echo $numChildGuests;
            echo "<br>";
            if (isset($dateToDecatur)) {
              echo 'Date to Decatur: ';
              $newDate  = date("m-d-Y", strtotime($dateToDecatur));
              echo $newDate;
              echo "<br>";
              echo 'Time to Decatur: ';
              echo $timeToDecatur->format('g:ia');
              echo "<br>";
            }
            if (isset($dateToAnacortes)) {
              echo 'Date to Anacortes: ';
              $newDate  = date("m-d-Y", strtotime($dateToAnacortes));
              echo $newDate;
              echo "<br>";
              echo 'Time to Anacortes: ';
              echo $timeToAnacortes->format('g:ia');
              echo "<br>";
            }
            echo 'Paid by PayPal?: ';
            echo $paypal;
            echo "<br>";
            echo 'Total Cost: ';
            echo number_format($cost, 2);
            echo "<br>";
            echo 'Comments: ';
            echo $comments;
            ?>
            <br />
            <br />

            <form action="viewResCancel.php" method="post">
            <input type="submit" name="submit" value="Cancel this reservation"></input>
            </form>

            <br/>

            <form action="viewResSelect.php">
            <input type="submit" name="submit" value="Return without canceling"></input>
            </form>
            </p>
    </body>
</html>
