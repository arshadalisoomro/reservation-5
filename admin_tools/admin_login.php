<?php ini_set('display_errors', 'On'); ?>
<?php
//server side admin login page - verify username and password

$admin_login_url="https://flessner.org/-jonTest/admin_tools/admin_tools.html";

if(!$_POST){
    header("Location: $admin_login_url");
    die();
}
if($_POST){
    //connect to sql server with global $conn
    require '../dbConnect.php';

    //timezone is always west coast
    date_default_timezone_set('America/Los_Angeles');

    session_start();
    unset($_SESSION['valid']);

    //set variables
    $username = $_POST['user'];
    $password = $_POST['password'];
    // random string from html site
    $returnString = $_POST['returnString'];

    //check if correct
    $qry = $conn->prepare("SELECT username, password FROM adminLogin
        WHERE username = ?
        AND password = ?");
    $qry->execute(array($username, $password));
    $response = $qry->fetch();

    if(!$response){
        $_SESSION['invalid'] = true;
        echo 'incorrect';
        die();
    }
    else{
        $_SESSION['valid'] = true;
        unset($_SESSION['invalid']);
        $_SESSION['last_activity'] = time();
        // return random string on successful login
        echo $returnString;
        die();
    }

    //close connection
    $conn = null;
}
?>
