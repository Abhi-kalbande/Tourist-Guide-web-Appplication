<?php
session_start();
include "../config/db.php";
if (!isset($_SESSION['admin'])) header("Location: login.php");

$data = mysqli_query($conn, "SELECT * FROM destinations");
?>

<!DOCTYPE html>
<html>
<head>
<title>View Destinations</title>
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<h2 class="center">Destinations</h2>

<table>
<tr>
  <th>ID</th>
  <th>Title</th>
  <th>Price</th>
  <th>Image</th>
</tr>

<?php while($row = mysqli_fetch_assoc($data)) { ?>
<tr>
  <td><?= $row['id'] ?></td>
  <td><?= $row['title'] ?></td>
  <td>₹<?= $row['price'] ?></td>
  <td><img src="../assets/images/<?= $row['image'] ?>" width="80"></td>
</tr>
<?php } ?>

</table>

</body>
</html>