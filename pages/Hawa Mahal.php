<?php include __DIR__ . '/../config/db_config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hawa Mahal</title>

    <!-- Correct CSS path -->
    <link rel="stylesheet" href="../assets/css/demo.css">
</head>
<body>

<!-- Navigation Bar -->
<?php include __DIR__ . '/../includes/Header.php'; ?>

<!-- Hero Section -->
<section class="hero">
    <h1>Hawa Mahal</h1>
</section>
     <main class="main">
          <section class="blog-post">
            <table class="image-table">
              <tr>
                <td><img src="../Traveling photo\jaipur1.jpg" alt="Image 1" style="width:350px;"></td>
                <td><img src="../Traveling photo\jaipur2.jpg" alt="Image 2" style="width:350px;"></td>
                <td><img src="../Traveling photo\jaipur3.jpg" alt="Image 3" style="width:350px;"></td>
              </tr>     
            </table>
            <!--info-->
            <div class="container">
              <h1>Jaipur - The Pink City</h1>
              <p><strong>Founded in 1727</strong> by Maharaja Sawai Jai Singh II, Jaipur is the capital of Rajasthan, known for its royal heritage, stunning architecture, and vibrant markets.</p>
              
              <div class="section">
                  <h2>History of Jaipur</h2>
                  <p>Jaipur was built according to Vastu Shastra by architect Vidyadhar Bhattacharya. The city is famous for its pink-colored buildings, which were painted in 1876 to honor the visit of Prince of Wales.</p>
              </div>
      
              <div class="section">
                  <h2>Best Time to Visit</h2>
                  <p class="highlight">The ideal time to visit Jaipur is between <strong>October to March</strong> when the weather is pleasant. Summers can be extremely hot (up to 45°C), while monsoons bring unpredictable rain.</p>
              </div>
              
              <div class="section">
                  <h2>Top Attractions in Jaipur</h2>
                  <ul>
                      <li><strong>Amber Fort:</strong> A majestic fort on a hilltop offering panoramic views.</li>
                      <li><strong>City Palace:</strong> A royal residence with museums and gardens.</li>
                      <li><strong>Hawa Mahal:</strong> The famous "Palace of Winds" with intricate lattice windows.</li>
                      <li><strong>Jantar Mantar:</strong> A UNESCO-listed astronomical observatory.</li>
                      <li><strong>Jaigarh & Nahargarh Forts:</strong> Offering historic charm and city views.</li>
                      <li><strong>Jal Mahal:</strong> A palace in the middle of Man Sagar Lake.</li>
                  </ul>
              </div>
      
              <div class="section">
                  <h2>Things to Do in Jaipur</h2>
                  <ul>
                      <li>Explore Jaipur's vibrant bazaars like Johari Bazaar and Bapu Bazaar.</li>
                      <li>Enjoy a traditional Rajasthani meal with <em>Dal Baati Churma</em> and <em>Laal Maas</em>.</li>
                      <li>Experience cultural performances and festivals like the <strong>Jaipur Literature Festival</strong>.</li>
                      <li>Take a camel or elephant ride at Amber Fort.</li>
                      <li>Witness the city from a hot air balloon ride.</li>
                  </ul>
              </div>
          </div>
            <center>
              <h3 class="styled-text">End</h3>
              <h1><a href="contact us.php" class="btn">Select Package</a></h1></center>
        </section>
    <!--footer-->
<?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>