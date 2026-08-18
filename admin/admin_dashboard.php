<?php
session_start();
require_once __DIR__ . '/../config/db_connection.php';

// Admin authentication
if (empty($_SESSION['admin_logged_in'])) {
    header('Location: Admin_login.php');
    exit;
}

$adminUser = $_SESSION['admin_user'] ?? 'Admin';

// Safe output helper
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Dashboard statistics
$stats = [
    'users' => 0,
    'bookings' => 0,
    'packages' => 0,
    'today_bookings' => 0
];

$countQueries = [
    'users' => "SELECT COUNT(*) AS total FROM user_information",
    'bookings' => "SELECT COUNT(*) AS total FROM bookings",
    'packages' => "SELECT COUNT(*) AS total FROM packages",
    'today_bookings' => "SELECT COUNT(*) AS total FROM bookings WHERE DATE(booking_date) = CURDATE()"
];

foreach ($countQueries as $key => $sql) {
    $result = $conn->query($sql);
    if ($result) {
        $row = $result->fetch_assoc();
        $stats[$key] = (int) ($row['total'] ?? 0);
    }
}

// Booking search/filter
$search = trim($_GET['search'] ?? '');
$filterDate = trim($_GET['filter_date'] ?? '');

$bookingSql = "
    SELECT id, full_name, email, whatsapp_number, tour_name,
           number_of_members, booking_date
    FROM bookings
    WHERE 1=1
";

$params = [];
$types = '';

if ($search !== '') {
    $bookingSql .= " AND (
        full_name LIKE ?
        OR email LIKE ?
        OR tour_name LIKE ?
        OR whatsapp_number LIKE ?
    )";
    $searchValue = '%' . $search . '%';
    $params = [$searchValue, $searchValue, $searchValue, $searchValue];
    $types = 'ssss';
}

if ($filterDate !== '') {
    $bookingSql .= " AND DATE(booking_date) = ?";
    $params[] = $filterDate;
    $types .= 's';
}

$bookingSql .= " ORDER BY booking_date DESC LIMIT 8";

$stmt = $conn->prepare($bookingSql);
if ($types !== '') {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$bookingResult = $stmt->get_result();

// Recent users
$userResult = $conn->query("
    SELECT id, name, email, created_at
    FROM user_information
    ORDER BY created_at DESC
    LIMIT 6
");

// Recent packages
$packageResult = $conn->query("
    SELECT id, name, price, created_at
    FROM packages
    ORDER BY created_at DESC
    LIMIT 6
");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Tales | Admin Dashboard</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Dashboard styles -->
    <link rel="stylesheet" href="../assets/css/admin_dashboard.css">
</head>

<body>
    <div class="admin-layout">

        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="brand">
                <div class="brand-icon">
                    <i class="bi bi-compass"></i>
                </div>
                <div>
                    <h1>Travel Tales</h1>
                    <span>Admin Panel</span>
                </div>
            </div>

            <nav class="sidebar-nav">
                <a href="admin_dashboard.php" class="nav-link active">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Dashboard</span>
                </a>

                <a href="view_bookings.php" class="nav-link">
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
                <a href="view_users.php" class="nav-link">
                    <i class="bi bi-people"></i>
                    <span>Users</span>
                </a>
            </nav>

            <div class="sidebar-bottom">
                <a href="logout.php" class="nav-link logout-link">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>

        <!-- Main content -->
        <main class="main-content">

            <!-- Topbar -->
            <header class="topbar">
                <button class="mobile-menu-btn" id="menuToggle" type="button" aria-label="Open menu">
                    <i class="bi bi-list"></i>
                </button>

                <div class="topbar-title">
                    <span>Administration</span>
                </div>

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

                <!-- Welcome -->
                <section class="welcome-section">
                    <div>
                        <p class="eyebrow">OVERVIEW</p>
                        <h2>Welcome back, <?php echo e($adminUser); ?> 👋</h2>
                        <p class="welcome-text">
                            Here's what's happening with your Travel Tales platform.
                        </p>
                    </div>

                    <a href="add_destination.php" class="btn-primary-custom">
                        <i class="bi bi-plus-lg"></i>
                        Add Package
                    </a>
                </section>

                <!-- Statistics -->
                <section class="stats-grid">

                    <div class="stat-card">
                        <div class="stat-icon users">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div>
                            <span>Total Users</span>
                            <strong><?php echo number_format($stats['users']); ?></strong>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon bookings">
                            <i class="bi bi-calendar2-check-fill"></i>
                        </div>
                        <div>
                            <span>Total Bookings</span>
                            <strong><?php echo number_format($stats['bookings']); ?></strong>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon packages">
                            <i class="bi bi-map-fill"></i>
                        </div>
                        <div>
                            <span>Total Packages</span>
                            <strong><?php echo number_format($stats['packages']); ?></strong>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon today">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div>
                            <span>Today's Bookings</span>
                            <strong><?php echo number_format($stats['today_bookings']); ?></strong>
                        </div>
                    </div>

                </section>

                <!-- Booking section -->
                <section class="content-card">
                    <div class="card-header-custom">
                        <div>
                            <h3>Recent Bookings</h3>
                            <p>Latest customer booking activity</p>
                        </div>
                        <a href="view_bookings.php" class="view-all">
                            View all <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>

                    <form method="GET" class="filter-bar">
                        <div class="search-box">
                            <i class="bi bi-search"></i>
                            <input type="search" name="search" value="<?php echo e($search); ?>"
                                placeholder="Search customer, email or package...">
                        </div>

                        <input type="date" name="filter_date" value="<?php echo e($filterDate); ?>" class="date-filter">

                        <button type="submit" class="filter-btn">
                            <i class="bi bi-funnel"></i>
                            Filter
                        </button>

                        <?php if ($search !== '' || $filterDate !== ''): ?>
                            <a href="admin_dashboard.php" class="clear-filter">Clear</a>
                        <?php endif; ?>
                    </form>

                    <div class="table-responsive">
                        <table class="dashboard-table">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Package</th>
                                    <th>Members</th>
                                    <th>Booking Date</th>
                                    <th>Contact</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php if ($bookingResult && $bookingResult->num_rows > 0): ?>
                                    <?php while ($booking = $bookingResult->fetch_assoc()): ?>
                                        <tr>
                                            <td>
                                                <div class="customer-cell">
                                                    <div class="customer-avatar">
                                                        <?php echo e(strtoupper(substr($booking['full_name'], 0, 1))); ?>
                                                    </div>
                                                    <div>
                                                        <strong><?php echo e($booking['full_name']); ?></strong>
                                                        <small><?php echo e($booking['email']); ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="package-name">
                                                    <?php echo e($booking['tour_name']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="member-badge">
                                                    <?php echo (int) $booking['number_of_members']; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php echo e(date('d M Y', strtotime($booking['booking_date']))); ?>
                                            </td>
                                            <td>
                                                <a href="tel:<?php echo e($booking['whatsapp_number']); ?>" class="contact-btn">
                                                    <i class="bi bi-telephone"></i>
                                                    Contact
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="empty-state">
                                            <i class="bi bi-inbox"></i>
                                            <strong>No bookings found</strong>
                                            <span>Try changing your search or filter.</span>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- Lower cards -->
                <section class="lower-grid">

                    <!-- Users -->
                    <div class="content-card">
                        <div class="card-header-custom">
                            <div>
                                <h3>Recent Users</h3>
                                <p>Latest registered customers</p>
                            </div>

                            <a href="view_users.php" class="view-all">
                                View all <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>

                        <div class="list-container">
                            <?php if ($userResult && $userResult->num_rows > 0): ?>
                                <?php while ($user = $userResult->fetch_assoc()): ?>
                                    <div class="user-row">
                                        <div class="customer-avatar">
                                            <?php echo e(strtoupper(substr($user['name'], 0, 1))); ?>
                                        </div>
                                        <div class="user-details">
                                            <strong><?php echo e($user['name']); ?></strong>
                                            <small><?php echo e($user['email']); ?></small>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="mini-empty">No users available.</div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Packages -->
                    <div class="content-card">
                        <div class="card-header-custom">
                            <div>
                                <h3>Recent Packages</h3>
                                <p>Recently added travel packages</p>
                            </div>
                            <a href="view_destinations.php" class="view-all">
                                View all <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>

                        <div class="list-container">
                            <?php if ($packageResult && $packageResult->num_rows > 0): ?>
                                <?php while ($package = $packageResult->fetch_assoc()): ?>
                                    <div class="package-row">
                                        <div class="package-icon">
                                            <i class="bi bi-geo-alt-fill"></i>
                                        </div>
                                        <div class="package-details">
                                            <strong><?php echo e($package['name']); ?></strong>
                                            <small>
                                                Added <?php echo e(date('d M Y', strtotime($package['created_at']))); ?>
                                            </small>
                                        </div>
                                        <span class="price">
                                            ₹<?php echo number_format((float) $package['price'], 2); ?>
                                        </span>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="mini-empty">No packages available.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
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