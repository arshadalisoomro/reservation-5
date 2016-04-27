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
              $("#specBoatDate").datepicker({minDate: 'today', maxDate: "+30D"});
         });

         function whenSubmit(){
         if(!$('#specBoatDate').val()){
             $('#boatDateReq').show();
             return false;
         }
         return true;
         }

    </script>

</head>
<body>

<form action="specialBoatConfirm.php" method="post" onsubmit="return whenSubmit()">

<p>Select date for non-scheduled boat:
  <br/>
  <br/>
  <input type="text" id="specBoatDate" name="specBoatDate"></p>
<div id="boatDateReq">Select a date for the non-scheduled boat.</div>


<p>Select time for non-scheduled boat:
<br />
<br />

  <input type="radio" name="time" value="10:00:00" checked> 10am to Anacortes, 9am to DNW<br>
  <input type="radio" name="time" value="11:00:00"> 11am to Anacortes, 10am to DNW<br>
  <input type="radio" name="time" value="12:00:00"> 12pmto Anacortes, 11am to DNW<br>
  <input type="radio" name="time" value="13:00:00"> 1pm to Anacortes, 12pm to DNW<br>
  <input type="radio" name="time" value="14:00:00"> 2pm to Anacortes, 1pm to DNW<br>
  <input type="radio" name="time" value="15:00:00"> 3pm to Anacortes, 2pm to DNW<br>
  <input type="radio" name="time" value="16:00:00"> 4pm to Anacortes, 3pm to DNW<br>
  <input type="radio" name="time" value="17:00:00"> 5pm to Anacortes, 4pm to DNW<br>
  <input type="radio" name="time" value="18:00:00"> 6pm to Anacortes, 5pm to DNW<br>
  <input type="radio" name="time" value="19:00:00"> 7pm to Anacortes, 6pm to DNW<br>
  <input type="radio" name="time" value="19:30:00"> 7:30pm to Anacortes, 6:30pm to DNW<br>
  <input type="radio" name="time" value="20:30:00"> 8:30pm to Anacortes, 7:30pm to DNW<br>
  <br>
  <input type="submit" name="submit" value="Submit">
</form>



</body>
</html>
