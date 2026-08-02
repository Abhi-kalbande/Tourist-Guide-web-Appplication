<?php
include 'db_connection.php'; // Your database connection file

if (isset($_GET['id'])) {
    $package_id = $_GET['id'];
    $sql = "DELETE FROM bookings WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $package_id);

    if ($stmt->execute()) {
        echo "Package deleted successfully.";
    } else {
        echo "Error deleting package.";
    }
    $stmt->close();
    $conn->close();
}
header("Location: admin_dashboard.php");
exit();
?>
