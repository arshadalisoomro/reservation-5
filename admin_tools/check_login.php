<?php
session_start();

if(!isset($_SESSION['valid']) || empty($_SESSION['valid']))
  header('Location: admin_login.php');
?>
