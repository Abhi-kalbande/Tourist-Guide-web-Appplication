<?php
session_start();
include('db_connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_user = $_POST['admin_user'];
    $admin_pass = $_POST['admin_pass'];

    // Fetch admin details from the database
    $stmt = $conn->prepare("SELECT password FROM admin WHERE username = ?");
    $stmt->bind_param("s", $admin_user);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($stored_password);
        $stmt->fetch();

        // Compare plain passwords (without hashing)
        if ($admin_pass === $stored_password) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_user'] = $admin_user;
            header("Location: admin_dashboard.php");
            exit();
        } else {
            echo "<script>alert('Incorrect password');</script>";
        }
    } else {
        echo "<script>alert('Admin user not found');</script>";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="Admin_login.css">
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <form method="POST" action="">
            <input type="text" name="admin_user" placeholder="Admin Username" required><br>
            <input type="password" name="admin_pass" placeholder="Password" required><br>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>