<?php ini_set('display_errors', 'On'); ?>
<?php

//set url variables for redirect if no POST
$reservations_url="http://www.flessner.org/-jonTest/reservations.html";

//returns manifest

//connect to sql server with global $conn
if($_POST){
   require 'dbConnect.php';
   $manifestDate = $_POST['manifestDate'];
   }
  else{
    header("Location: $reservations_url");
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
    WHERE dateToDecatur = ?
    OR dateToAnacortes = ?");


$qry->execute(array($manifestDate, $manifestDate));

$outManifest = $qry->fetchAll();

$manifestArray = array();

//echo "<table border='1' style='border-collapse:
//    collapse;border-color: silver;'>";
//    echo "<tr style='font-weight: bold;'>";
//    echo "<td width='150' align='center'>Class</td>";
//    echo "</tr>";


//close connection
$conn = null;

?>

<html>
    <head>
        <title>Manifest Output</title>
        <meta charset = "UTF-8"></meta>
            <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'></link>
        <link rel="stylesheet" type="text/css" href="boatRes.css"></link>
        <style>
        table {
            border-collapse: collapse;
            width: 100%;            
        }
        th {
            font-weight: normal;
            text-align: left;          
        }
        td {
            text-align: left;
            border-bottom: 1px solid darkgray;          
        }
        </style>  
     </head>
     <body>
            <div>
            <a>Manifest for:</a>
                <?php
                  $dateOut=date_create("$manifestDate");
                  echo date_format($dateOut,"l m/d/Y ");
                ?>

         <!--   <table class="table table-striped table-condensed"> -->
                <table>
                    <thead>
                        <tr>
                            <th>Homeowner</th>
                            <th>Guest</th>
                            <th>Adult</th>
                            <th>Child</th>
                            <th>Adult</th>
                            <th>Child</th>
                            <th>Paypal</th>
                            <th>Cost</th>
                            <th>Admin</th>
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
                        </tr>
                    </thead>
                <?php
                foreach($outManifest as $row) {
                    $manifestArray[] = $row['homeownerName'];

                echo'<tbody>';
                echo'<tr>';
                echo'<td>'. $row['homeownerName']."</td>";
                echo'<td>'. $row['guestName'].'</td>';
                echo'<td>'. $row['numAdultHomeowners'].'</td>';
                echo'<td>'. $row['numChildHomeowners'].'</td>';
                echo'<td>'. $row['numAdultGuests'].'</td>';
                echo'<td>'. $row['numChildGuests'].'</td>';
                echo'<td>'. $row['paypal'].'</td>';
                echo'<td>'. number_format($row['cost'], 2).'</td>';
                echo'<td>'.'</td>';
                echo'<tr>';
                echo'</tbody>';
              }
            ?>

                </table>
        </div>
    </body>
</html>
