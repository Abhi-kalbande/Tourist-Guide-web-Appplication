<?php
session_start();
require_once "../config/db_connection.php";

if (empty($_SESSION['admin_logged_in'])) {
    header("Location: Admin_login.php");
    exit;
}

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$adminName = $_SESSION['admin_user'] ?? 'Admin';
$search = trim($_GET['search'] ?? '');
$minPrice = trim($_GET['min_price'] ?? '');
$maxPrice = trim($_GET['max_price'] ?? '');

$sql = "SELECT id, name, price, description, created_at
        FROM packages
        WHERE 1=1";

$params = [];
$types = '';

if ($search !== '') {
    $sql .= " AND (name LIKE ? OR description LIKE ?)";
    $term = "%{$search}%";
    $params[] = $term;
    $params[] = $term;
    $types .= "ss";
}

if ($minPrice !== '' && is_numeric($minPrice)) {
    $sql .= " AND price >= ?";
    $params[] = (float) $minPrice;
    $types .= "d";
}

if ($maxPrice !== '' && is_numeric($maxPrice)) {
    $sql .= " AND price <= ?";
    $params[] = (float) $maxPrice;
    $types .= "d";
}

$sql .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Database query error.");
}

if ($types !== '') {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$packages = $stmt->get_result();

$countResult = $conn->query("SELECT COUNT(*) AS total FROM packages");
$totalPackages = $countResult ? (int) $countResult->fetch_assoc()['total'] : 0;

$priceResult = $conn->query(
    "SELECT COALESCE(MIN(price), 0) AS min_price,
            COALESCE(MAX(price), 0) AS max_price
     FROM packages"
);

$priceRange = $priceResult
    ? $priceResult->fetch_assoc()
    : ['min_price' => 0, 'max_price' => 0];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Tales | Packages</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin_packages.css">
</head>

<body>

    <div class="admin-layout">

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

                <a href="admin_dashboard.php" class="nav-link">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Dashboard</span>
                </a>

                <a href="view_bookings.php" class="nav-link">
                    <i class="bi bi-calendar-check"></i>
                    <span>Bookings</span>
                </a>

                <a href="view_destinations.php" class="nav-link active">
                    <i class="bi bi-map-fill"></i>
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

                <button class="mobile-menu-btn" id="menuToggle" type="button">
                    <i class="bi bi-list"></i>
                </button>

                <div class="topbar-title">
                    Package Management
                </div>

                <div class="admin-profile">

                    <div class="admin-avatar">
                        <?php echo e(strtoupper(substr($adminName, 0, 1))); ?>
                    </div>

                    <div class="admin-info">
                        <strong><?php echo e($adminName); ?></strong>
                        <small>Administrator</small>
                    </div>

                </div>

            </header>

            <div class="page-container">

                <section class="page-heading">

                    <div>
                        <p class="eyebrow">CONTENT MANAGEMENT</p>

                        <h2>Travel Packages</h2>

                        <p>
                            Manage the packages available on your Travel Tales website.
                        </p>
                    </div>

                    <a href="add_destination.php" class="primary-btn">
                        <i class="bi bi-plus-lg"></i>
                        Add Package
                    </a>

                </section>

                <section class="stats-grid">

                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-map-fill"></i>
                        </div>

                        <div>
                            <span>Total Packages</span>
                            <strong>
                                <?php echo number_format($totalPackages); ?>
                            </strong>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-cash-stack"></i>
                        </div>

                        <div>
                            <span>Lowest Price</span>
                            <strong>
                                ₹<?php echo number_format((float) $priceRange['min_price'], 0); ?>
                            </strong>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>

                        <div>
                            <span>Highest Price</span>
                            <strong>
                                ₹<?php echo number_format((float) $priceRange['max_price'], 0); ?>
                            </strong>
                        </div>
                    </div>

                </section>

                <section class="content-card">

                    <div class="card-heading">

                        <div>
                            <h3>All Packages</h3>
                            <p>
                                View and manage packages stored in your database.
                            </p>
                        </div>

                    </div>

                    <form method="GET" class="filter-bar">

                        <div class="search-box">

                            <i class="bi bi-search"></i>

                            <input type="search" name="search" value="<?php echo e($search); ?>"
                                placeholder="Search package...">

                        </div>

                        <input type="number" name="min_price" value="<?php echo e($minPrice); ?>" min="0" step="0.01"
                            placeholder="Min price" class="price-input">

                        <input type="number" name="max_price" value="<?php echo e($maxPrice); ?>" min="0" step="0.01"
                            placeholder="Max price" class="price-input">

                        <button type="submit" class="filter-btn">
                            <i class="bi bi-funnel"></i>
                            Filter
                        </button>

                        <?php if ($search !== '' || $minPrice !== '' || $maxPrice !== ''): ?>

                            <a href="view_destinations.php" class="clear-btn">
                                Clear
                            </a>

                        <?php endif; ?>

                    </form>

                    <div class="table-responsive">

                        <table class="package-table">

                            <thead>
                                <tr>
                                    <th>Package</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php if ($packages->num_rows > 0): ?>

                                    <?php while ($package = $packages->fetch_assoc()): ?>

                                        <tr>

                                            <td>

                                                <div class="package-cell">

                                                    <div class="package-icon">
                                                        <i class="bi bi-geo-alt-fill"></i>
                                                    </div>

                                                    <div>
                                                        <strong>
                                                            <?php echo e($package['name']); ?>
                                                        </strong>

                                                        <small>
                                                            Package #<?php echo (int) $package['id']; ?>
                                                        </small>
                                                    </div>

                                                </div>

                                            </td>

                                            <td>

                                                <div class="description">
                                                    <?php
                                                    echo e(
                                                        $package['description']
                                                        ?: 'No description available.'
                                                    );
                                                    ?>
                                                </div>

                                            </td>

                                            <td>

                                                <span class="price-tag">
                                                    ₹<?php
                                                    echo number_format(
                                                        (float) $package['price'],
                                                        2
                                                    );
                                                    ?>
                                                </span>

                                            </td>

                                            <td>

                                                <?php
                                                if (!empty($package['created_at'])) {
                                                    echo e(
                                                        date(
                                                            'd M Y',
                                                            strtotime($package['created_at'])
                                                        )
                                                    );
                                                } else {
                                                    echo '—';
                                                }
                                                ?>

                                            </td>

                                            <td>

                                                <div class="actions">
                                                    <a href="view_package_details.php?id=<?php echo (int) $package['id']; ?>"
                                                        class="action-btn view" title="View package">
                                                        <i class="bi bi-eye"></i>
                                                    </a>

                                                    <a href="update_package.php?id=<?php echo (int) $package['id']; ?>"
                                                        class="action-btn edit" title="Edit package">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>

                                                    <a href="delete_package.php?id=<?php echo (int) $package['id']; ?>"
                                                        class="action-btn delete" title="Delete package"
                                                        onclick="return confirm('Are you sure you want to delete this package?');">
                                                        <i class="bi bi-trash3"></i>
                                                    </a>

                                                </div>

                                            </td>

                                        </tr>

                                    <?php endwhile; ?>

                                <?php else: ?>

                                    <tr>

                                        <td colspan="5" class="empty-state">

                                            <i class="bi bi-map"></i>

                                            <strong>
                                                No packages found
                                            </strong>

                                            <span>
                                                Add a package or change your search/filter.
                                            </span>

                                        </td>

                                    </tr>

                                <?php endif; ?>

                            </tbody>

                        </table>

                    </div>

                </section>

                <footer class="page-footer">
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