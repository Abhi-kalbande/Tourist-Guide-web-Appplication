<?php
$host = "localhost";  
$username = "root";   
$password = "";       
$database = "travel_tale";  


$conn = new mysqli($host, $username, $password, $database);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
