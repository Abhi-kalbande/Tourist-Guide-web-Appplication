<?php
session_start();

require_once __DIR__ . '/../config/db_connection.php';

if (empty($_SESSION['admin_logged_in'])) {
    header('Location: Admin_login.php');
    exit;
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {

    $id = (int) $_GET['id'];

    $stmt = $conn->prepare(
        "DELETE FROM bookings WHERE id = ?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $stmt->close();
}

$conn->close();

header('Location: view_bookings.php');
exit;
?>