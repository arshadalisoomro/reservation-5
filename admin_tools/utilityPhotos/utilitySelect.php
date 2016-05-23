<?php
// require valid admin login
require '../check_login.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Select Utility site to View</title>
    <meta charset = "UTF-8">
    <link rel="stylesheet" type="text/css" href="../../css/admin.css">

</head>
<body>
  <span>Utilities Photo Archive by Site Number</span>
  <br />
  <br />
<form action="utilityView.php" method="post" onsubmit="return whenSubmit()">

<p>Select Site Number to view:</p>

  <input type="radio" name="site" value="ec2" checked> EC-2<br>
  <input type="radio" name="site" value="ec12and13and14"> EC-12,EC-13, EC-14<br>
  <input type="radio" name="site" value="sb1and2"> SB-1, SB-2<br>
  <input type="radio" name="site" value="sb2"> SB-2<br>
  <input type="radio" name="site" value="sc2and3and9"> SC-2, SC-3, SC-9<br>
  <input type="radio" name="site" value="wellS7and9"> Well #S 7, 9<br>
  <br>
  <input type="submit" name="submit" value="Submit">
</form>
<br />
<br />
<form action="../admin_form.php">
<input type="submit" name="submit" value="Return"></input>
</form>


</body>
</html>
