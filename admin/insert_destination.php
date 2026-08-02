<?php
session_start();
include('db_connection.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: Admin_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $information = $_POST['information'];
    $description = $_POST['description'];

    // Insert into packages table
    $sql = "INSERT INTO packages (name, information, description) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $information, $description);

    if ($stmt->execute()) {
        echo "Destination added successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    header("Location: destination.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Destination</title>
    <link rel="stylesheet" href="Admin_dashboard.css">
</head>
<body>
    <h1>Add New Destination</h1>
    <form method="post" action="">
        <label for="name">Destination Name:</label>
        <input type="text" id="name" name="name" required>
        <br>
        <label for="information">Price:</label>
        <input type="text" id="information" name="information" required>
        <br>
        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>
        <br>
        <button type="submit">Add Destination</button>
    </form>
    <br>
    <a href="destination.php">Back to Destinations</a>
</body>
</html>
