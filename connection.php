<?php
$server = "localhost";
$pass = "";
$username = "root";
$dbname="faculty_new";

$conn = mysqli_connect($server, $username, '', $dbname);
if (!$conn) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    exit;
}
//Start session
session_start();
?>