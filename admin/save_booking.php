<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "projectwebsite");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$name = $_POST['name'];
$email = $_POST['email'];
$whatsapp = $_POST['whatsapp'];
$tour = $_POST['tour'];
$members = $_POST['members'];

// Insert into database
$sql = "INSERT INTO bookings (full_name, email, whatsapp_number, tour_name, number_of_members)
        VALUES ('$name', '$email', '$whatsapp', '$tour', '$members')";

$message = "";
if ($conn->query($sql) === TRUE) {
    $message = "Thank you for booking! We will contact you soon.";
} else {
    $message = "Error: " . $conn->error;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Status</title>
    <style>
        body {
            text-align: center;
            font-family: Arial, sans-serif;
            padding: 50px;
        }
        h1 {
            color: green;
        }
        .error {
            color: red;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: hsl(75, 48%, 49%);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .button:hover {
            background-color: hsl(0, 0.00%, 3.90%);
        }
    </style>
</head>
<body>
    <div class="container">
    <h1><?php echo $message; ?></h1>
    <a href="index.php" class="button">Go Back to Home</a>
    </div>
</body>
</html>
