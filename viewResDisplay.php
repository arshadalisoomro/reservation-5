<?php ini_set('display_errors', 'On'); ?>
<?php

//set url variables for redirect if no POST
$admin_url="https://www.flessner.org/-jonTest/viewResSelect.php";

//this page displays the reservation selected in viewResSelect.php

//connect to sql server with global $conn
if($_POST){
   require 'dbConnect.php';
   $confCode = $_POST['confCode'];
   // set value found to yes
   $Found = "yes";
   }
  else{
    header("Location: $admin_url");
    die();
}

//attempt to select the reservation
$qry = $conn->prepare("SELECT * FROM reservationsDNW WHERE confirmationCode= '$confCode'");
$qry->execute();
$result = $qry->fetchAll();

//see if reservation was found
if (count($result) > 0){
$resultArray = array();
$json = json_encode($resultArray);
} else
{
// reservation matching confirmation code was not found
$Found = "no";
}

//close connection
$conn = null;
?>

<html>
    <head>
        <title>View Reservation</title>
        <meta charset = "UTF-8"></meta>
    <!--    <script src="../libraries/jquery-2.2.3.min.js"></script> -->
        <link rel="stylesheet" type="text/css" href="css/viewRes.css"></link>

     </head>
     <body>
            <div id="viewResDiv">

                <?php
                if ($Found == "yes") {
                  //unset session to clear variables in case came from viewResConfirmCancel.php
                  session_unset();
                  //start php session
                  session_start();

                foreach($result as $row) {
                // display reservation
                echo 'Boat Reservation for confirmation number: ';
                $_SESSION['confirmationCode'] = $row['confirmationCode'];
                echo $row['confirmationCode'];
                echo "<br>";
                echo 'Homeowner Name: ';
                $_SESSION['homeownerName'] = $row['homeownerName'];
                echo $row['homeownerName'];
                echo "<br>";
                $guest = $row['guestName'];
                $_SESSION['guestName'] = $guest;
                if ($guest !== "") {
                echo 'Guest Name: ';
                echo $row['guestName'];
                echo "<br>";
                }
                echo 'Email: ';
                $_SESSION['email'] = $row['email'];
                echo $row['email'];
                echo "<br>";
                echo 'Phone: ';
                $_SESSION['phone'] = $row['phone'];
                echo "(".substr($row['phone'], 0, 3).") ".substr($row['phone'], 3, 3)."-".substr($row['phone'],6);
                echo "<br>";
                echo 'Number of Adult Homeowners: ';
                $_SESSION['numAdultHomeowners'] = $row['numAdultHomeowners'];
                echo $row['numAdultHomeowners'];
                echo "<br>";
                echo 'Number of Child Homeowners: ';
                $_SESSION['numChildHomeowners'] = $row['numChildHomeowners'];
                echo $row['numChildHomeowners'];
                echo "<br>";
                echo 'Number of Adult Guests: ';
                $_SESSION['numAdultGuests'] = $row['numAdultGuests'];
                echo $row['numAdultGuests'];
                echo "<br>";
                echo 'Number of Child Guests: ';
                $_SESSION['numChildGuests'] = $row['numChildGuests'];
                echo $row['numChildGuests'];
                echo "<br>";
                if (isset($row['dateToDecatur'])) {
                  echo 'Date to Decatur: ';
                  $originalDecDate = $row['dateToDecatur'];
                  $_SESSION['dateToDecatur'] = $originalDecDate;
                  $newDate  = date("m-d-Y", strtotime($originalDecDate));
                  echo $newDate;
                  echo "<br>";
                  echo 'Time to Decatur: ';
                  $timeOutDec = new DateTime($row['timeToDecatur']);
                  $_SESSION['timeToDecatur'] = $timeOutDec;
                  echo $timeOutDec->format('g:ia');
                  echo "<br>";
                }
                else {
                  $_SESSION['dateToDecatur'] = NULL;
                  $_SESSION['timeToDecatur'] = NULL;
                }
                if (isset($row['dateToAnacortes'])) {
                echo 'Date to Anacortes: ';
                $originalAnaDate = $row['dateToAnacortes'];
                $_SESSION['dateToAnacortes'] = $originalAnaDate;
                $newDate  = date("m-d-Y", strtotime($originalAnaDate));
                echo $newDate;
                echo "<br>";
                echo 'Time to Anacortes: ';
                $timeOutAna = new DateTime($row['timeToAnacortes']);
                $_SESSION['timeToAnacortes'] = $timeOutAna;
                echo $timeOutAna->format('g:ia');
                echo "<br>";
                }
                else {
                  $_SESSION['dateToAnacortes'] = NULL;
                  $_SESSION['timeToAnacortes'] = NULL;
                }
                echo 'Paid by PayPal?: ';
                $_SESSION['paypal'] = $row['paypal'];
                echo $row['paypal'];
                echo "<br>";
                echo 'Total Cost: ';
                $_SESSION['cost'] = $row['cost'];
                echo number_format($row['cost'], 2);
                echo "<br>";
                echo 'Comments: ';
                $_SESSION['comments'] = $row['comments'];
                echo $row['comments'];
                }
              }
              else {
                // no reservation found
                echo "<br>";
                echo "<br>";
                echo "The Confirmation Code you entered was not found.";
                echo "<br>";
                echo "<br>";
                echo "<a id=link href=$admin_url>Return</a>";
                die();
              }
            ?>

        </div>
        <p>
        <!-- choose to return to prev page or cancel the reservation -->
        <form action="viewResSelect.php">
        <input type="submit" name="submit" value="Return"></input>
        </form>
        <br />
        <form action="viewResConfirmCancel.php" method="post">
        <input type="submit" name="submit" value="Cancel this reservation"></input>
        </form>
        </p>
    </body>
</html>
