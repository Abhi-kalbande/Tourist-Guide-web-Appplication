<?php
session_start();

include('../config/db_connection.php');

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: Admin_login.php");
    exit();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {

    $id = (int) $_GET['id'];

    $stmt = mysqli_prepare(
        $conn,
        "DELETE FROM user_information WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
}

header("Location: view_users.php");
exit();
?>