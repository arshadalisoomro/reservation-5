<?php ini_set('display_errors', 'On'); ?>
<?php

//set url variables for redirect if no POST
$admin_url="https://www.flessner.org/-jonTest/editEmail.php";

//connect to sql server with global $conn
if($_POST){
   require '../dbConnect.php';
   $emailComment = $_POST['comment'];
   }
  else{
    header("Location: $admin_url");
    die();
}

$qry = $conn->prepare("UPDATE  AppData
                       SET RespondMsg = '$emailComment'
                       WHERE ID = 1");

$qry->execute();

$conn = null;

?>

<html>
    <head>
    <title>Update Email Response Message</title>
    <meta charset = "UTF-8"></meta>
  <!--  <script src="../libraries/jquery-2.2.3.min.js"></script> -->
    <link rel="stylesheet" type="text/css" href="../css/admin.css"></link>

     </head>
     <body>
            <div id=emailDiv>
            <a>The email response message has been updated:</a>
            <br />
            <br />
                <?php
                  echo $emailComment
                ?>
            <br />
            <br />
            </div>
            <form action="admin_form.php">
            <input type="submit" name="submit" value="Return"></input>
            </form>
    </body>
</html>
