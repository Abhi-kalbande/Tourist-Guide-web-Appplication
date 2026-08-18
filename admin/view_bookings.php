<?php
session_start();
require_once __DIR__ . '/../config/db_connection.php';

if (empty($_SESSION['admin_logged_in'])) {
    header('Location: Admin_login.php');
    exit;
}

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

$adminUser = $_SESSION['admin_user'] ?? 'Admin';
$search = trim($_GET['search'] ?? '');
$date = trim($_GET['date'] ?? '');

$where = [];
$params = [];
$types = '';

if ($search !== '') {
    $where[] = '(full_name LIKE ? OR email LIKE ? OR whatsapp_number LIKE ? OR tour_name LIKE ?)';
    $term = '%' . $search . '%';
    $params = [$term, $term, $term, $term];
    $types = 'ssss';
}

if ($date !== '') {
    $where[] = 'DATE(booking_date) = ?';
    $params[] = $date;
    $types .= 's';
}

$whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$sql = "
    SELECT id, full_name, email, whatsapp_number, tour_name,
           number_of_members, booking_date
    FROM bookings
    $whereSql
    ORDER BY booking_date DESC
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die('Unable to load bookings.');
}

if ($types !== '') {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

// Summary numbers
$totalResult = $conn->query("SELECT COUNT(*) AS total FROM bookings");
$todayResult = $conn->query("SELECT COUNT(*) AS total FROM bookings WHERE DATE(booking_date) = CURDATE()");
$membersResult = $conn->query("SELECT COALESCE(SUM(number_of_members), 0) AS total FROM bookings");

$totalBookings = (int) ($totalResult->fetch_assoc()['total'] ?? 0);
$todayBookings = (int) ($todayResult->fetch_assoc()['total'] ?? 0);
$totalMembers = (int) ($membersResult->fetch_assoc()['total'] ?? 0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings | Travel Tales Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin_dashboard.css">
    <link rel="stylesheet" href="../assets/css/admin_bookings.css">
</head>

<body>
<div class="admin-layout">

    <aside class="sidebar" id="sidebar">
        <div class="brand">
            <div class="brand-icon"><i class="bi bi-compass"></i></div>
            <div>
                <h1>Travel Tales</h1>
                <span>Admin Panel</span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <a href="admin_dashboard.php" class="nav-link">
                <i class="bi bi-grid-1x2-fill"></i>
                <span>Dashboard</span>
            </a>

            <a href="view_bookings.php" class="nav-link active">
                <i class="bi bi-calendar-check"></i>
                <span>Bookings</span>
            </a>

            <a href="view_destinations.php" class="nav-link">
                <i class="bi bi-map"></i>
                <span>Packages</span>
            </a>

            <a href="add_destination.php" class="nav-link">
                <i class="bi bi-plus-circle"></i>
                <span>Add Package</span>
            </a>

            <a href="add_user.php" class="nav-link">
                <i class="bi bi-person-plus"></i>
                <span>Add User</span>
            </a>
        </nav>

        <div class="sidebar-bottom">
            <a href="logout.php" class="nav-link logout-link">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </a>
        </div>
    </aside>

    <main class="main-content">
        <header class="topbar">
            <button class="mobile-menu-btn" id="menuToggle" type="button" aria-label="Open menu">
                <i class="bi bi-list"></i>
            </button>

            <div class="topbar-title">Booking Management</div>

            <div class="admin-profile">
                <div class="admin-avatar">
                    <?php echo e(strtoupper(substr($adminUser, 0, 1))); ?>
                </div>
                <div class="admin-info">
                    <strong><?php echo e($adminUser); ?></strong>
                    <small>Administrator</small>
                </div>
            </div>
        </header>

        <div class="dashboard-container">

            <section class="page-heading">
                <div>
                    <p class="eyebrow">MANAGEMENT</p>
                    <h2>Bookings</h2>
                    <p>View and manage customer tour bookings.</p>
                </div>
            </section>

            <section class="booking-stats">
                <div class="booking-stat">
                    <div class="booking-stat-icon">
                        <i class="bi bi-calendar2-check"></i>
                    </div>
                    <div>
                        <span>Total Bookings</span>
                        <strong><?php echo number_format($totalBookings); ?></strong>
                    </div>
                </div>

                <div class="booking-stat">
                    <div class="booking-stat-icon">
                        <i class="bi bi-calendar-day"></i>
                    </div>
                    <div>
                        <span>Today's Bookings</span>
                        <strong><?php echo number_format($todayBookings); ?></strong>
                    </div>
                </div>

                <div class="booking-stat">
                    <div class="booking-stat-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <div>
                        <span>Total Travellers</span>
                        <strong><?php echo number_format($totalMembers); ?></strong>
                    </div>
                </div>
            </section>

            <section class="content-card booking-card">
                <div class="card-header-custom">
                    <div>
                        <h3>All Bookings</h3>
                        <p>Customer bookings stored in your database</p>
                    </div>
                </div>

                <form method="GET" class="booking-filter">
                    <div class="booking-search">
                        <i class="bi bi-search"></i>
                        <input
                            type="search"
                            name="search"
                            value="<?php echo e($search); ?>"
                            placeholder="Search name, email, phone or tour..."
                        >
                    </div>

                    <input
                        type="date"
                        name="date"
                        value="<?php echo e($date); ?>"
                        class="booking-date"
                    >

                    <button class="filter-btn" type="submit">
                        <i class="bi bi-funnel"></i>
                        Filter
                    </button>

                    <?php if ($search !== '' || $date !== ''): ?>
                        <a href="view_bookings.php" class="clear-filter">Clear</a>
                    <?php endif; ?>
                </form>

                <div class="table-responsive">
                    <table class="booking-table">
                        <thead>
                        <tr>
                            <th>Customer</th>
<th>Tour</th>
<th>Travellers</th>
<th>Booking Date</th>
<th>Contact</th>
<th>Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <div class="booking-customer">
                                            <div class="customer-avatar">
                                                <?php echo e(strtoupper(substr($row['full_name'], 0, 1))); ?>
                                            </div>
                                            <div>
                                                <strong><?php echo e($row['full_name']); ?></strong>
                                                <small><?php echo e($row['email']); ?></small>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="tour-name">
                                            <?php echo e($row['tour_name']); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <span class="traveller-count">
                                            <i class="bi bi-people"></i>
                                            <?php echo (int) $row['number_of_members']; ?>
                                        </span>
                                    </td>

                                    <td>
                                        <div class="booking-date-text">
                                            <strong>
                                                <?php echo e(date('d M Y', strtotime($row['booking_date']))); ?>
                                            </strong>
                                            <small>
                                                <?php echo e(date('h:i A', strtotime($row['booking_date']))); ?>
                                            </small>
                                        </div>
                                    </td>

                                    <td>
                                        <a
                                            class="contact-button"
                                            href="tel:<?php echo e($row['whatsapp_number']); ?>"
                                        >
                                            <i class="bi bi-telephone"></i>
                                            Contact
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">
                                    <div class="booking-empty">
                                        <i class="bi bi-calendar-x"></i>
                                        <strong>No bookings found</strong>
                                        <span>
                                            <?php echo ($search !== '' || $date !== '')
                                                ? 'Try changing your search or date filter.'
                                                : 'Customer bookings will appear here.'; ?>
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <p class="booking-note">
                <i class="bi bi-info-circle"></i>
                Booking status is not displayed because the current <code>bookings</code>
                table does not contain a status column.
            </p>

            <footer class="dashboard-footer">
                Travel Tales Admin Panel &copy; <?php echo date('Y'); ?>
            </footer>
        </div>
    </main>
</div>

<script>
const menuToggle = document.getElementById('menuToggle');
const sidebar = document.getElementById('sidebar');

menuToggle?.addEventListener('click', () => {
    sidebar.classList.toggle('open');
});
</script>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
