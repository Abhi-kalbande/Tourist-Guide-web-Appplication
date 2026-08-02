<?php
session_start();
include "../config/db.php";
if (!isset($_SESSION['admin'])) header("Location: login.php");

$data = mysqli_query($conn,
  "SELECT b.*, d.title FROM bookings b
   JOIN destinations d ON b.destination_id = d.id");
?>

<h2 style="text-align:center">Bookings</h2>

<table>
<tr>
  <th>Name</th>
  <th>Email</th>
  <th>Destination</th>
  <th>Message</th>
</tr>

<?php while($row = mysqli_fetch_assoc($data)) { ?>
<tr>
  <td><?= $row['user_name'] ?></td>
  <td><?= $row['user_email'] ?></td>
  <td><?= $row['title'] ?></td>
  <td><?= $row['message'] ?></td>
</tr>
<?php } ?>
</table>