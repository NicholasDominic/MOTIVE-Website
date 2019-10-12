<?php
$servername = "localhost";
$username = "root";
$password = "0a8DSnkN1LOjNwC6";
$database = "motive";
$conn = mysqli_connect($servername, $username, $password, $database);

if (mysqli_connect_errno()) {
    die("Cannot connect to database " . mysqli_connect_error());
}