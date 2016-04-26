<?php ini_set('display_errors', 'On'); ?>
<?php
//must be logged in as admin
require 'check_login.php';
//this page displays all past reservations

//set url variables for redirect if no POST
$reservations_url="https://www.flessner.org/-jonTest/admin_tools/admin_form.php";

//connect to sql server with global $conn
require '../dbConnect.php';

$qry = $conn->prepare("SELECT * FROM reservationsDNW
where (dateToDecatur < CURRENT_TIMESTAMP or dateToDecatur is null) and
(dateToAnacortes < CURRENT_TIMESTAMP or dateToAnacortes is null)");

$qry->execute();

$outRes = $qry->fetchAll();

$resArray = array();

//close connection
$conn = null;

?>

<html>
<head>
    <title>Display Past Reservations</title>
    <meta charset = "UTF-8">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="../css/admin.css">

</head>
<body class=bodyAdmin>
  <p>All Past DNW Reservations</p>
  <div>
      <table class=tableAdmin>
          <thead class=thAdmin>
              <tr>
                  <th>Conf Code</th>
                  <th>Homeowner</th>
                  <th>GuestName</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>AdultH</th>
                  <th>ChildH</th>
                  <th>AdultG</th>
                  <th>ChildG</th>
                  <th>ToDecaturOn</th>
                  <th>ToAnacortes</th>
                  <th>Comments</th>
                  <th>Paid</th>
                  <th>Cost</th>
                  <th>Timestamp</th>
              </tr>

          </thead>
      <?php
      foreach($outRes as $row) {
          $resArray[] = $row['confirmationCode'];

      echo'<tbody>';
      echo'<tr>';
      echo'<td class=tdAdmin>'. $row['confirmationCode'].'</td>';
      echo'<td class=tdAdmin>'. $row['homeownerName'].'</td>';
      echo'<td class=tdAdmin>'. $row['guestName'].'</td>';
      echo "<td class=tdAdmin><a href='link'>" . $row['email'] . "</a></td>";
      $areaCode = substr($row['phone'], 0, 3);
      $nextThree = substr($row['phone'], 3, 3);
      $lastFour = substr($row['phone'], 6, 4);
      echo'<td class=tdAdmin>'.'('.$areaCode.')'.$nextThree.'-'.$lastFour.'</td>';
      echo'<td class=tdAdmin>'. $row['numAdultHomeowners'].'</td>';
      echo'<td class=tdAdmin>'. $row['numChildHomeowners'].'</td>';
      echo'<td class=tdAdmin>'. $row['numAdultGuests'].'</td>';
      echo'<td class=tdAdmin>'. $row['numChildGuests'].'</td>';
      if (empty($row['dateToDecatur'])) {
        $toDecDate = "";
        $toDecTime = "";
      }
      else {
      $toDecDate = date("m-d-Y", strtotime($row['dateToDecatur']));
      $toDecTime = date('h:i', strtotime($row['timeToDecatur']));
      }
      echo'<td class=tdAdmin>'. $toDecDate.' '.$toDecTime.'</td>';
      if (empty($row['dateToAnacortes'])) {
        $toAnaDate = "";
        $toAnaTime = "";
      }
      else {
      $toAnaDate = date("m-d-Y", strtotime($row['dateToAnacortes']));
      $toAnaTime = date('h:i', strtotime($row['timeToAnacortes']));
      }
      echo'<td class=tdAdmin>'. $toAnaDate.' '.$toAnaTime.'</td>';
      echo'<td class=tdAdmin>'. $row['comments'].'</td>';
      echo'<td class=tdAdmin>'. $row['paypal'].'</td>';
      echo'<td class=tdAdmin>'. number_format($row['cost'], 2).'</td>';
      echo'<td class=tdAdmin>'. $row['timestamp'].'</td>';
      echo'</tr>';
      echo'</tbody>';
    }
  ?>
      </table>

</div>

</body>
</html>
