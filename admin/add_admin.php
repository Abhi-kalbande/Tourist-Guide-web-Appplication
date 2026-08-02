<?php
include('db_connection.php'); 

$admin_username = "admin";
$admin_password = "admin123"; 
$hashed_password = password_hash($admin_password, PASSWORD_DEFAULT); 

// Delete old admin user if exists
$conn->query("DELETE FROM admin WHERE username = '$admin_username'");

// Insert the new admin user with the hashed password
$stmt = $conn->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $admin_username, $hashed_password);

if ($stmt->execute()) {
    echo "Admin user inserted successfully with hashed password!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
