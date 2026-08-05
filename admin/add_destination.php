<?php
session_start();
include('../config/db_connection.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location:../admin/Admin_login.php");
    exit();
}

// Fetch all destinations
$dest_query = "SELECT * FROM packages";
$dest_result = $conn->query($dest_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Destinations</title>
    <link rel="stylesheet" href="../assets/css/Admin_dashboard.css">
</head>
<body>
    <h1>Destinations</h1>
    <a href="../admin/insert_destination.php">Add New Destination</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Destination Name</th>
            <th>Price</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
        <?php while ($dest = $dest_result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $dest['id']; ?></td>
                <td><?php echo $dest['name']; ?></td>
                <td><?php echo $dest['price']; ?></td>
                <td><?php echo $dest['description']; ?></td>
                <td>
                    <a href="delete_destination.php?id=<?php echo $dest['id']; ?>" onclick="return confirm('Are you sure you want to delete this destination?');">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>

    <br>
    <a href="../config/db_connection.php">Back to Dashboard</a>
</body>
</html>

<?php
$conn->close();
?>
