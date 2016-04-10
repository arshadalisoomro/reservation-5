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


//foreach($outManifest as $row) {
//     $manifestArray[] = $row['homeownerName'];  
//     echo '<td width="150" align=center>' . $row['homeownerName'] . '</td>';
     //echo $row['homeownerName'];
     //echo $row['numAdultHomeowners'];     
//}



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
                                 
     </head>
    <body>        
            <div>  
            <a>Manifest</a> 
                <?php               
                  $dateOut=date_create("$manifestDate");
                  echo 'for: ' . date_format($dateOut,"l m/d/Y ");
                ?>
                
        

         <!--   <table class="table table-striped table-condensed"> -->
                <table>
                    <thead>
                        <tr>
                            <th>Homeowner Name</th>
                            <th>Guest Name </th>
                            <th>Adult Homeowners </th>
                            <th>Child Homeowners </th>
                            <th>Adult Guests </th>
                            <th>Child Guests </th>
                            <th>Paypal </th>
                            <th>Cost </th>
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
                echo'<td>'. $row['cost'].'</td>';
                echo'<tr>';
                echo'</tbody>';
              }
            ?>


                </table>

        </div>

    </body>
</html>