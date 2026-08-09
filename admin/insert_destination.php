<?php
session_start();
require_once "../config/db_connection.php";

if (empty($_SESSION['admin_logged_in'])) {
    header("Location: Admin_login.php");
    exit;
}

function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$errors = [];
$success = '';

$name = trim($_POST['name'] ?? '');
$information = trim($_POST['information'] ?? '');
$description = trim($_POST['description'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($name === '') {
        $errors[] = 'Package name is required.';
    } elseif (mb_strlen($name) > 150) {
        $errors[] = 'Package name must be 150 characters or fewer.';
    }

    if ($information === '') {
        $errors[] = 'Package information/price is required.';
    }

    if ($description === '') {
        $errors[] = 'Description is required.';
    }

    if (!$errors) {
        $sql = "INSERT INTO packages (name, information, description) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            $errors[] = 'Unable to prepare the database query.';
        } else {
            $stmt->bind_param("sss", $name, $information, $description);

            if ($stmt->execute()) {
                header("Location: view_destinations.php?added=1");
                exit;
            }

            $errors[] = 'Package could not be added. Please try again.';
            $stmt->close();
        }
    }
}

$adminName = $_SESSION['admin_user'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Tales | Add Package</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin_add_package.css">
</head>

<body>
<div class="admin-layout">

    <aside class="sidebar">
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

            <a href="add_destination.php" class="nav-link active-sub">
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

            <div class="topbar-title">Package Management</div>

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

            <div class="breadcrumb">
                <a href="view_destinations.php">Packages</a>
                <i class="bi bi-chevron-right"></i>
                <span>Add Package</span>
            </div>

            <section class="page-heading">
                <div>
                    <p class="eyebrow">PACKAGE MANAGEMENT</p>
                    <h2>Add New Package</h2>
                    <p>Create a package that can be displayed on your Travel Tales website.</p>
                </div>
            </section>

            <?php if ($errors): ?>
                <div class="alert alert-danger custom-alert">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <div>
                        <strong>Please fix the following:</strong>
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
            <section class="form-card">

                <div class="form-card-header">
                    <div class="form-header-icon">
                        <i class="bi bi-map"></i>
                    </div>
                    <div>
                        <h3>Package Details</h3>
                        <p>Enter the basic information for your new package.</p>
                    </div>
                </div>

                <form method="POST" action="" novalidate>

                    <div class="form-grid">

                        <div class="field-group full-width">
                            <label for="name">
                                Package Name
                                <span>*</span>
                            </label>

                            <div class="input-wrapper">
                                <i class="bi bi-geo-alt"></i>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value="<?php echo e($name); ?>"
                                    maxlength="150"
                                    placeholder="e.g. Golden Triangle Tour"
                                    required
                                >
                            </div>

                            <small>Use a clear and customer-friendly package name.</small>
                        </div>

                        <div class="field-group full-width">
                            <label for="information">
                                Package Information / Price
                                <span>*</span>
                            </label>

                            <div class="input-wrapper">
                                <i class="bi bi-info-circle"></i>
                                <input
                                    type="text"
                                    id="information"
                                    name="information"
                                    value="<?php echo e($information); ?>"
                                    placeholder="Enter the package information or price"
                                    required
                                >
                            </div>

                            <small>
                                Your current database field is named
                                <strong>information</strong>, so we are keeping it unchanged for now.
                            </small>
                        </div>

                        <div class="field-group full-width">
                            <label for="description">
                                Description
                                <span>*</span>
                            </label>

                            <textarea
                                id="description"
                                name="description"
                                rows="7"
                                placeholder="Describe the package, places covered, highlights, and other useful information..."
                                required
                            ><?php echo e($description); ?></textarea>

                            <small>Write useful information that helps customers understand the package.</small>
                        </div>

                    </div>
                    <div class="form-actions">
                        <a href="view_destinations.php" class="secondary-btn">
                            <i class="bi bi-arrow-left"></i>
                            Cancel
                        </a>

                        <button type="submit" class="primary-btn">
                            <i class="bi bi-plus-lg"></i>
                            Create Package
                        </button>
                    </div>
                </form>
            </section>
            <footer class="page-footer">
                Travel Tales Admin Panel &copy; <?php echo date('Y'); ?>
            </footer>

        </div>
    </main>
</div>

<script>
const menuToggle = document.getElementById('menuToggle');
const sidebar = document.querySelector('.sidebar');

menuToggle?.addEventListener('click', () => {
    sidebar.classList.toggle('open');
});
</script>
</body>
</html>

<?php
$conn->close();
?>
