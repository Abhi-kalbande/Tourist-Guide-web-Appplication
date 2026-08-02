<?php
$servername = "localhost";
$username = "root"; // Change if using a different username
$password = ""; // Change if using a password
$dbname = "travel_tale"; // Change to your actual database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
