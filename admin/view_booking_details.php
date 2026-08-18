<?php
session_start();
require_once __DIR__ . '/../config/db_connection.php';

if (empty($_SESSION['admin_logged_in'])) {
    header('Location: Admin_login.php');
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: view_bookings.php');
    exit;
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare(
    "SELECT id, full_name, email, whatsapp_number, tour_name,
            number_of_members, booking_date
     FROM bookings
     WHERE id = ?"
);

$stmt->bind_param("i", $id);
$stmt->execute();

$booking = $stmt->get_result()->fetch_assoc();

if (!$booking) {
    header('Location: view_bookings.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Booking Details | Travel Tales</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link rel="stylesheet"
          href="assets/css/view_users.css">
</head>

<body>

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2>Booking Details</h2>
            <p class="text-muted mb-0">
                View customer booking information.
            </p>
        </div>

        <a href="view_bookings.php" class="btn btn-secondary">
            Back to Bookings
        </a>

    </div>


    <div class="card booking-card">

        <div class="card-body">

            <h4 class="mb-4">
                <?php echo htmlspecialchars($booking['tour_name']); ?>
            </h4>

            <div class="row">

                <div class="col-md-6 mb-4">
                    <strong>Booking ID</strong>
                    <p>#<?php echo $booking['id']; ?></p>
                </div>

                <div class="col-md-6 mb-4">
                    <strong>Customer Name</strong>
                    <p>
                        <?php echo htmlspecialchars($booking['full_name']); ?>
                    </p>
                </div>

                <div class="col-md-6 mb-4">
                    <strong>Email</strong>
                    <p>
                        <?php echo htmlspecialchars($booking['email']); ?>
                    </p>
                </div>

                <div class="col-md-6 mb-4">
                    <strong>WhatsApp Number</strong>
                    <p>
                        <?php echo htmlspecialchars($booking['whatsapp_number']); ?>
                    </p>
                </div>

                <div class="col-md-6 mb-4">
                    <strong>Tour</strong>
                    <p>
                        <?php echo htmlspecialchars($booking['tour_name']); ?>
                    </p>
                </div>

                <div class="col-md-6 mb-4">
                    <strong>Number of Travellers</strong>
                    <p>
                        <?php echo (int) $booking['number_of_members']; ?>
                    </p>
                </div>

                <div class="col-md-6 mb-4">
                    <strong>Booking Date</strong>
                    <p>
                        <?php echo date(
                            'd M Y',
                            strtotime($booking['booking_date'])
                        ); ?>
                    </p>
                </div>

                <div class="col-md-6 mb-4">
                    <strong>Booking Time</strong>
                    <p>
                        <?php echo date(
                            'h:i A',
                            strtotime($booking['booking_date'])
                        ); ?>
                    </p>
                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>