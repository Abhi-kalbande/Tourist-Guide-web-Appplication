<?php
$host = "127.0.0.1";
$user = "root";
$pass = "";   // SAME password
$db   = "travel_tale";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die(mysqli_connect_error());
}
?>