<?php ini_set('display_errors', 'On'); ?>
<?php

?>

<html>
<!-- Displays page to enter confirmation code of reservation to be viewed or deleted -->
<head>
        <title>View Boat Reservation</title>
        <meta charset = "UTF-8">
        <link rel="stylesheet" href="../css/jquery-ui.min.css">
        <script src="libraries/jquery-2.2.3.min.js"></script>
        <script src="libraries/jquery-ui.min.js"></script>
        <link rel="stylesheet" type="text/css" href="css/viewRes.css">

        <script>

        function whenSubmit(){
            //remove any submit msg
            $('#subMsg').html("");

            if (!$('#confCode').val()){
                //error
                $('#confCodeReq').show();
                $('#confCode').focus();
                return false;
            }
            else{
                $('#confCodeReq').hide();
            }

            if (!/^[A-Za-z]{6}$/.test($('#confCode').val())){
              //error
              $('#confCodeReq').show();
              $('#confCode').focus();
              return false;
            }

            //submit if passes all
            var formData = $('#viewResForm').serialize();
            //return true to complete submit
            return true;
        }
        </script>

</head>

<body>
<a style="color:Black; text-align:left;" href='http://www.flessner.org/-jonTest/mainPage.html'>Home</a>
<br />
<br />
<legend><b>View Boat Reservation</b></legend>

    <form id="viewResForm" action="viewResDisplay.php" method="post"
      onsubmit="return whenSubmit()" novalidate>
      <p><label for="confCode">Please enter a Confirmation Code:</label></p>
        <input id="confCode" name="confCode" type="text">
    <div id="confCodeReq">Please enter a valid confirmation code.</div>
      <input type="submit">
    </form>

</body>
</html>
