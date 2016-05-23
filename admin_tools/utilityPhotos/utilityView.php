<?php ini_set('display_errors', 'On'); ?>
<?php

//this script displays the Utility site photo previously selected


if (isset($_POST['site'])) {
    $site = $_POST['site'];
}
if ($site == "ec2") {
  header('Location: UEC2.htm');
}
if ($site == "ec12and13and14") {
  header('Location: UEC12.13.14.htm');
}
if ($site == "sb1and2") {
  header('Location: USB1.2.htm');
}
if ($site == "sb2") {
  header('Location: USB2.htm');
}
if ($site == "sc2and3and9") {
  header('Location: USC2.3.9.htm');
}
if ($site == "wellS7and9") {
  header('Location: Uwell.s7.9.htm');
}
?>
