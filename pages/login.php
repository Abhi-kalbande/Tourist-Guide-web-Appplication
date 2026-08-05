<?php
session_start();
include "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email exists in database
    $stmt = $conn->prepare("SELECT * FROM user_information WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_email'] = $email; // Store session
            header("Location: ../index.php"); // Redirect to index.php
            exit();
        } else {
            $error = "Invalid email or password!";
        }
    } else {
        $error = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>
    <div class="login-container">
        <div class="logo"><img src="../Traveling photo/logo.jpeg" alt="Travel Tales logo"> </div> <!-- Optional Logo -->
        <h2>Login to your account</h2>
        
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        
        <form method="POST">
            <div class="input-group">
                <label>Email address</label>
                <input type="email" name="email" placeholder="Enter Email" required>
            </div>
            
            <div class="input-group">
                <label>Password <a href="#" class="forgot-password">Forgot password?</a></label>
                <input type="password" name="password" placeholder="Enter Password" required>
            </div>
            
            <button type="submit">login</button>
        </form>

        <p class="signup-link">Not a member? <a href="signup.php">Sign up here</a></p>
    </div>
</body>
</html>
