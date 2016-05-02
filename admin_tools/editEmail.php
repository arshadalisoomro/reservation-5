<?php ini_set('display_errors', 'On'); ?>
<?php

//connect to sql server with global $conn

require '../dbConnect.php';

//bring this value in from the form instead of hardcoding

$query = $conn->query("SELECT RespondMsg FROM AppData WHERE ID = 1");
$result = $query->fetch(PDO::FETCH_ASSOC);
$addtlinfo = $result['RespondMsg'];


//close connection
$conn = null;

?>

<html>
    <head>
        <title>Edit Email Response Message</title>
        <meta charset = "UTF-8"></meta>
            <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'></link>
        <link rel="stylesheet" type="text/css" href="../css/admin.css"></link>
    </head>
    <body>
        <div id="emailDiv">
            <form id="editEmailForm" action="updateEmail.php" method="post"
                onsubmit="return whenSubmit()" novalidate>
            <legend><b>Update Email Response Message</b></legend>
        <br />
        <br />
        <?php
        echo "Current Email Response Message:<br>";
        echo "<br>".$addtlinfo;
        ?>

        <p>Enter New Email Response Message:</p>
        <p>
            <textarea rows="6" cols="60" id="comment" name="comment"
                form="editEmailForm"></textarea>
        </p>

        <input id="submitButton" type="submit" value="Submit">
        </form>
        <br />
        <form action="admin_form.php">
        <input type="submit" value="Return without updating"></input>
        </form>
    </div>





    </body>
</html>
