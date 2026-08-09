<?php
session_start();
require_once "../config/db_connection.php";

if (empty($_SESSION['admin_logged_in'])) {
    header("Location: Admin_login.php");
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: view_destinations.php?error=invalid_id");
    exit;
}

$stmt = $conn->prepare("DELETE FROM packages WHERE id = ?");

if (!$stmt) {
    header("Location: view_destinations.php?error=delete_failed");
    exit;
}

$stmt->bind_param("i", $id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    header("Location: view_destinations.php?deleted=1");
} else {
    header("Location: view_destinations.php?error=not_found");
}

$stmt->close();
$conn->close();
exit;
?>

