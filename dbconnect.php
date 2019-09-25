<?php
$servername = "50.116.3.147";
$serverusername = "ss9361mn";
$password = "ss9361mn";
$dbname = "ss9361mn_midterm_project";

//$servername = "localhost";
//$serverusername = "root";
//$password = "";
//$dbname = "midterm_project";
// Create connection
$conn = new mysqli($servername, $serverusername, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
session_start();
?>