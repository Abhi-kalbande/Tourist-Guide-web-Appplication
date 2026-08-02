<?php
include 'db_connection.php';

if (isset($_POST['update'])) {
    $package_id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    $sql = "UPDATE bookings SET name=?, price=?, description=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $name, $price, $description, $package_id);

    if ($stmt->execute()) {
        echo "Package updated successfully.";
    } else {
        echo "Error updating package.";
    }
    $stmt->close();
    $conn->close();
}
header("Location: admin_dashboard.php");
exit();
?>
