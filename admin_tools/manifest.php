<?php ini_set('display_errors', 'On'); ?>
<?php

//set url variables for redirect if no POST
$admin_url="https://www.flessner.org/-jonTest/manifestSelect.php";

//returns manifest

//connect to sql server with global $conn
if($_POST){
   require '../dbConnect.php';
   $manifestDate = $_POST['manifestDate'];
   $manifestTime = $_POST['manifestTime'];
   }
  else{
    header("Location: $admin_url");
    die();
}

//bring this value in from the form instead of hardcoding


$qry = $conn->prepare("SELECT homeownerName,
                              guestName,
                              numAdultHomeowners,
                              numChildHomeowners,
                              numAdultGuests,
                              numChildGuests,
                              paypal,
                              cost
                              FROM reservationsDNW
                       WHERE (dateToDecatur = ?
                       AND timeToDecatur = ?)
                       OR (dateToAnacortes = ?
                       AND timeToAnacortes =?)");


$qry->execute(array($manifestDate, $manifestTime, $manifestDate, $manifestTime));

$outManifest = $qry->fetchAll();

$manifestArray = array();


//close connection
$conn = null;

?>

<html>
    <head>
        <title>Manifest Output</title>
        <meta charset = "UTF-8"></meta>
            <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'></link>
        <link rel="stylesheet" type="text/css" href="../css/admin.css"></link>

     </head>
     <body class=bodyManifest>
            <div>
            <a>Passenger Manifest for:</a>
                <?php
                  $totalPass = 0;
                  $dateOut=date_create("$manifestDate");
                  echo date_format($dateOut,"l m/d/Y ");
                  echo " - ";
                  $timeOut = new DateTime("$manifestTime");
                  echo $timeOut->format('g:ia');
                ?>
            <a>Boat</a>
            <br />
            <br />
            <a>Adult Homeowner $15, Child Homeowner $10, Adult Guest $35, Child Guest $20</a>
            <br />
            <br />
         <!--   <table class="table table-striped table-condensed"> -->
                <table class = tableAdmin>
                    <thead class=thManifest>
                        <tr>
                            <th>Homeowner</th>
                            <th>Guest</th>
                            <th>Adult</th>
                            <th>Child</th>
                            <th>Adult</th>
                            <th>Child</th>
                            <th>Paid</th>
                            <th>Cost</th>
                            <th>Initials</th>
                            <th>Check $</th>
                        </tr>
                        <tr class="noBorder">
                          <th>Name</th>
                          <th>Name</th>
                          <th>HmOwn</th>
                          <th>HmOwn</th>
                          <th>Guests</th>
                          <th>Guests</th>
					            	  <th></th>
				   	              <th></th>
						              <th></th>
                          <th></th>
                        </tr>
                    </thead>
                <?php
                foreach($outManifest as $row) {
                    $manifestArray[] = $row['homeownerName'];

                $totalPass = $totalPass + $row['numAdultHomeowners']
                                        + $row['numChildHomeowners']
                                        + $row['numAdultGuests']
                                        + $row['numChildGuests'];

                echo'<tbody>';
                echo'<tr>';
                echo'<td class=tdManifest>'. $row['homeownerName']."</td>";
                echo'<td class=tdManifest>'. $row['guestName'].'</td>';
                echo'<td class=tdManifest>'. $row['numAdultHomeowners'].'</td>';
                echo'<td class=tdManifest>'. $row['numChildHomeowners'].'</td>';
                echo'<td class=tdManifest>'. $row['numAdultGuests'].'</td>';
                echo'<td class=tdManifest>'. $row['numChildGuests'].'</td>';
                echo'<td class=tdManifest>'. $row['paypal'].'</td>';
                echo'<td class=tdManifest>'. number_format($row['cost'], 2).'</td>';
                echo'<td class=tdManifest>'.'</td>';
                echo'<td class=tdManifest>'.'</td>';
                echo'</tr>';
                echo'</tbody>';
              }
            ?>
                </table>
                <br />
                <a>Total Passengers:</a>
                    <?php
                      echo "$totalPass";
                    ?>
        </div>
    </body>
</html>
