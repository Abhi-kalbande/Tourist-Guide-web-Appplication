<?php
include "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $age = $_POST['age'];
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert user data into the database
        $stmt = $conn->prepare("INSERT INTO user_information (full_name, age, contact_number, email, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sisss", $full_name, $age, $contact_number, $email, $hashed_password);

        if ($stmt->execute()) {
            // Redirect to login page after successful sign-up
            header("Location: login.php?success=1");
            exit();
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Sign Up</title>
    <link rel="stylesheet" href="../assets/css/signup.css">
</head>
<body>
    <div class="signup-container">
    <div class="logo"><img src="../Traveling photo/logo.jpeg" alt="Travel Tales logo"> </div> <!-- Optional Logo -->
        <h2>Sign Up</h2>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="full_name" placeholder="Full Name" required>
            <input type="number" name="age" placeholder="Age" required>
            <input type="text" name="contact_number" placeholder="Contact Number" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Create Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Sign Up</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
