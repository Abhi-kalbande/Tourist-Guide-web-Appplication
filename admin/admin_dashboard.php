<?php
session_start();
include('../config/db_connection.php');

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: Admin_login.php");
    exit();
}

// Search and Filter Variables
$search = isset($_GET['search']) ? $_GET['search'] : '';
$filter_package = isset($_GET['filter_package']) ? $_GET['filter_package'] : '';
$filter_date = isset($_GET['filter_date']) ? $_GET['filter_date'] : '';
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'ASC';

// Fetch data from user_information table
$user_query = "SELECT * FROM user_information WHERE full_name LIKE '%$search%' OR email LIKE '%$search%'";
$user_result = $conn->query($user_query);

// Fetch data from package_booking table with filters and search
$booking_query = "SELECT * FROM bookings WHERE full_name LIKE '%$search%'";

if ($filter_package) {
    $booking_query .= " AND tour_name = '$filter_package'";
}
if ($filter_date) {
    $booking_query .= " AND booking_date = '$filter_date'";
}

$booking_query .= " ORDER BY booking_date $sort_order";
$booking_result = $conn->query($booking_query);

// Fetch distinct packages for filter dropdown
$package_result = $conn->query("SELECT DISTINCT tour_name FROM bookings");

// Fetch all destinations
$dest_query = "SELECT * FROM packages";
$dest_result = $conn->query($dest_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/Admin_dashboard.css">
</head>

<body>
    <h1>Welcome, <?php echo $_SESSION['admin_user']; ?>!</h1>
    <a href="logout.php">Logout</a>

    <!-- Search and Filter Form -->
    <form method="GET" action="">
        <input type="text" name="search" placeholder="Search..." value="<?php echo $search; ?>">
        <select name="filter_package">
            <option value="">All Packages</option>
            <?php while ($package = $package_result->fetch_assoc()) { ?>
                <option value="<?php echo $package['tour_name']; ?>" <?php echo $filter_package === $package['tour_name'] ? 'selected' : ''; ?>>
                    <?php echo $package['tour_name']; ?>
                </option>
            <?php } ?>
        </select>
        <input type="date" name="filter_date" value="<?php echo $filter_date; ?>">
        <button type="submit">Search & Filter</button>
    </form>

    <!-- User Information -->
    <h2>User Information</h2>
    <a href="add_user.php">Add User</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        <?php while ($user = $user_result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo $user['full_name']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td>
                    <a href="delete_user.php?id=<?php echo $user['id']; ?>"
                        onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>

    <!-- Destination Management -->
    <h2>Destinations</h2>
    <a href="../admin/add_destination.php">Add Destination</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Destination Name</th>
            <th>Price</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
        <?php while ($dest = $dest_result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $dest['id']; ?></td>
                <td><?php echo $dest['name']; ?></td>
                <td><?php echo $dest['price']; ?></td>
                <td><?php echo $dest['description']; ?></td>
                <td>
                    <a href="delete_destination.php?id=<?php echo $dest['id']; ?>"
                        onclick="return confirm('Are you sure you want to delete this destination?');">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>

    <!-- Booking Information -->
    <h2>Package Bookings</h2>
    <a href="export.php">Export to CSV</a>
    <table border="1">
        <tr>
            <th>Booking ID</th>
            <th>Package Name</th>
            <th>User Name</th>
            <th>Contact</th>
            <th>Booking Date</th>
            <th>
                <a href="?sort_order=<?php echo $sort_order === 'ASC' ? 'DESC' : 'ASC'; ?>">Sort by Date</a>
            </th>
        </tr>
        <?php while ($booking = $booking_result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $booking['id']; ?></td>
                <td><?php echo $booking['tour_name']; ?></td>
                <td><?php echo $booking['full_name']; ?></td>
                <td><?php echo $booking['whatsapp_number']; ?></td>
                <td><?php echo $booking['booking_date']; ?></td>
            </tr>
        <?php } ?>
    </table>
</body>

</html>

<?php
$conn->close();
?>