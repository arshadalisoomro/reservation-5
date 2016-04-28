
<?php
//require valid admin login
require 'check_login.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Selection Form</title>
    <meta charset = "UTF-8">
    <link rel="stylesheet" type="text/css" href="../css/admin.css">
</head>
<body>
    <div>

        <legend><b>Admin Selections:</b></legend>
        <br />
        <br />
        <a id=link href="manifestSelect.php">View Manifest</a>
        <br />
        <br />
        <a id=link href="futureRes.php">View all future reservations</a>
        <br />
        <br />
        <a id=link href="pastRes.php">View all past reservations</a>
        <br />
        <br />
        <a id=link href="specialBoatselect.php">Add a non-scheduled boat</a>
        <br />
        <br />
        <a id=link href="cancelBoatSelect.php">Cancel a boat</a>
        <br />
        <br />
        <a id=link href="editEmail.html">Edit the email text</a>

    </div>
</body>
</html>
