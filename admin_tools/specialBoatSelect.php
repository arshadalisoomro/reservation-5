<?php
// require valid admin login 
require 'check_login.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Special Boat Selection</title>
    <meta charset = "UTF-8">
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="../css/admin.css">


    <script>
         // show the date calendar for the next 30 days
         $(function() {
              $("#datepicker").datepicker({minDate: 'today', maxDate: "+30D"});
         });

    </script>

</head>
<body>

<form action="specialBoatAdd.php" method="post">
<p>Select date for non-scheduled boat: <input type="text" id="datepicker" name="datepicker"></p>

<p>Select time From Decatur to Anacortes:
<br />
(boat From Anacortes to Decatur will automatically be generated)</p>

  <input type="radio" name="time" value="10:00:00" checked> 10am<br>
  <input type="radio" name="time" value="11:00:00"> 11am<br>
  <input type="radio" name="time" value="12:00:00"> 12pm<br>
  <input type="radio" name="time" value="13:00:00"> 1pm<br>
  <input type="radio" name="time" value="14:00:00"> 2pm<br>
  <input type="radio" name="time" value="15:00:00"> 3pm<br>
  <input type="radio" name="time" value="16:00:00"> 4pm<br>
  <input type="radio" name="time" value="17:00:00"> 5pm<br>
  <input type="radio" name="time" value="18:00:00"> 6pm<br>
  <input type="radio" name="time" value="19:00:00"> 7pm<br>
  <input type="radio" name="time" value="19:30:00"> 7:30pm<br>
  <br>
  <input type="submit" name="submit" value="Submit">
</form>



</body>
</html>
