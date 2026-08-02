<?php
include "../config/db.php";

$data = mysqli_query($conn, "SELECT * FROM destinations");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>destination</title>
    <link rel="stylesheet" href="../assets/css/demo.css">
    <link rel="stylesheet" href="../assets/css/destination.css">
    
</head>
<?php include __DIR__ . '/../includes/Header.php'; ?>
<body>
<section class="package" id="package">
    <div class="container">
            <section class="banner">
                <div class="overlay">
                    <h1>DESTINATIONS</h1>
                </div>
            </section><br><br>
       <!-- Row 1 -->
       <div class="image-row">

                <div class="image-container">
                  <img src="../Traveling photo/1.Ladakh.jpg" alt="Image 8">
                  <a href="Ladakh.php"><h2>Ladakh</h2></a>
                </div>
                
                <div class="image-container">
                  <img src="../Traveling photo/1.Kerala.jpg" alt="Image 2">
                  <a href="Kerala.php"><h2>Kerala</h2></a>
                </div>
                <div class="image-container">
                  <img src="../Traveling photo/1.Orissa-Tribal.jpg" alt="Image 3">
                  <a href="Orrisa.php"><h2>Orrisa</h2></a>
                </div>
              </div>
            
              <!-- Row 2 -->
              <div class="image-row">
                <div class="image-container">
                  <img src="../Traveling photo/1.Shimla.jpg" alt="Image 4">
                  <a href="Shimla.php"><h2>shimla</h2></a>
                </div>
                <div class="image-container">
                  <img src="../Traveling photo/1.Srinagar.jpg" alt="Image 5">
                  <a href="Srinagar.php"><h2>shrinagar</h2></a>
                </div>
                <div class="image-container">
                  <img src="../Traveling photo/1.Varanasi.jpg" alt="Image 6">
                  <a href="Varanasi.php"><h2>Varanasi</h2></a>
                </div>
              </div>
            
              <!-- Row 3 -->
              <div class="image-row">
                <div class="image-container">
                  <img src="../Traveling photo/1.Rajasthan.jpg" alt="Image 7">
                  <a href="Rajasthan.php"><h2>Rajasthan</h2></a>
                </div>
                <div class="image-container">
                  <img src="../Traveling photo/1.Karnataka.jpg" alt="Image 1">
                 <a href="karnataka .php"><h2>Karnataka</h2></a>
                </div>
                
                <div class="image-container">
                  <img src="../Traveling photo/1.Himachal-Pradesh.jpg" alt="Image 9">
                  <a href="Himachal.php"><h2>Himachal-Pradesh</h2></a>
                </div>
              </div>
            
              <!-- Row 4 -->
              <div class="image-row">
                <div class="image-container">
                  <img src="../Traveling photo/1.Assam_.jpg" alt="Image 10">
                  <a href="Assam.php"><h2>Assam</h2></a>
                </div>
                <div class="image-container">
                  <img src="../Traveling photo/1.Amritsar.jpg" alt="Image 11">
                  <a href="Amritsar.php"><h2>Amritsar</h2></a>
                </div>
                <div class="image-container">
                  <img src="../Traveling photo/1.Agra_.jpg" alt="Image 12">
                  <a href="Agra.php"><h2>Agra</h2></a>
                </div>
    </div>
</section>
<!-- Footer -->
<?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
