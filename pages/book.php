<?php
include "../config/db.php";

$id = $_GET['id'];
$dest = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM destinations WHERE id=$id")
);

if (isset($_POST['book'])) {
    $name  = $_POST['name'];
    $email = $_POST['email'];
    $msg   = $_POST['message'];

    mysqli_query($conn,
      "INSERT INTO bookings (user_name, user_email, destination_id, message)
       VALUES ('$name', '$email', '$id', '$msg')"
    );

    $success = "Booking Successful! Our team will contact you.";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Book Destination</title>
<link rel="stylesheet" href="../assets/css/main.css">
</head>
<body>

<section class="booking">
  
  <h2>Book: <?= $dest['title'] ?></h2>

  <?php if(isset($success)) echo "<p class='success'>$success</p>"; ?>

  <form method="post">
    <input type="text" name="name" placeholder="Your Name" required>
    <input type="email" name="email" placeholder="Your Email" required>
    <textarea name="message" placeholder="Message"></textarea>
    <button name="book">Confirm Booking</button>
    
  </form>
  
</section>


</body>
</html>