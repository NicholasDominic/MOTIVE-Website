<?php
error_reporting(E_ALL);
ini_set('display_errors','Off');
ini_set('error_log','error.log');

session_start();
session_unset();
session_destroy();
header("Location: ./LogIn.php");
?>