<?php
session_start();
include('../config/db_connection.php');

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: Admin_login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    mysqli_query($conn, "DELETE FROM user_information WHERE id='$id'");
}

header("Location: admin_dashboard.php");
exit();
?>
