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

$adminName = $_SESSION['admin_user'] ?? 'Admin';
$errors = [];
$success = '';

$name = trim($_POST['name'] ?? '');
$price = trim($_POST['price'] ?? '');
$description = trim($_POST['description'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($name === '') {
        $errors[] = 'Package name is required.';
    } elseif (mb_strlen($name) > 150) {
        $errors[] = 'Package name must be 150 characters or less.';
    }

    if ($price === '') {
        $errors[] = 'Package price is required.';
    } elseif (!is_numeric($price) || (float)$price < 0) {
        $errors[] = 'Enter a valid package price.';
    }

    if ($description === '') {
        $errors[] = 'Package description is required.';
    }

    if (!$errors) {
        $sql = "INSERT INTO packages (name, price, description) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            $errors[] = 'Unable to prepare the package query.';
        } else {
            $packagePrice = (float)$price;
            $stmt->bind_param("sds", $name, $packagePrice, $description);

            if ($stmt->execute()) {
                header("Location: view_destinations.php?added=1");
                exit;
            }

            $errors[] = 'Package could not be added. Please try again.';
            $stmt->close();
        }
    }
}
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
                    <p>Create a travel package that can be managed from your admin panel.</p>
                </div>
            </section>

            <?php if ($errors): ?>
                <div class="alert-box error">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <div>
                        <?php foreach ($errors as $error): ?>
                            <div><?php echo e($error); ?></div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <section class="form-card">

                <div class="form-card-header">
                    <div class="header-icon">
                        <i class="bi bi-map"></i>
                    </div>
                    <div>
                        <h3>Package Details</h3>
                        <p>Enter the basic information for this travel package.</p>
                    </div>
                </div>

                <form method="POST" action="" novalidate>

                    <div class="form-grid">

                        <div class="field full-width">
                            <label for="name">
                                Package Name
                                <span>*</span>
                            </label>
                            <div class="input-wrap">
                                <i class="bi bi-geo-alt"></i>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value="<?php echo e($name); ?>"
                                    maxlength="150"
                                    placeholder="e.g. Goa Beach Escape"
                                    required
                                >
                            </div>
                        </div>

                        <div class="field">
                            <label for="price">
                                Package Price
                                <span>*</span>
                            </label>
                            <div class="input-wrap">
                                <span class="currency">₹</span>
                                <input
                                    type="number"
                                    id="price"
                                    name="price"
                                    value="<?php echo e($price); ?>"
                                    min="0"
                                    step="0.01"
                                    placeholder="15000"
                                    required
                                >
                            </div>
                            <small>Enter the price in Indian Rupees.</small>
                        </div>

                        <div class="field full-width">
                            <label for="description">
                                Package Description
                                <span>*</span>
                            </label>
                            <textarea
                                id="description"
                                name="description"
                                rows="7"
                                maxlength="2000"
                                placeholder="Describe the package, places included, activities, duration, and other useful information..."
                                required
                            ><?php echo e($description); ?></textarea>
                            <div class="field-footer">
                                <small>Keep the description clear and useful for customers.</small>
                                <small><span id="charCount">0</span>/2000</small>
                            </div>
                        </div>

                    </div>

                    <div class="form-actions">
                        <a href="view_destinations.php" class="secondary-btn">
                            <i class="bi bi-arrow-left"></i>
                            Cancel
                        </a>

                        <button type="submit" class="primary-btn">
                            <i class="bi bi-check2-circle"></i>
                            Add Package
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

const description = document.getElementById('description');
const charCount = document.getElementById('charCount');

function updateCount() {
    charCount.textContent = description.value.length;
}

description.addEventListener('input', updateCount);
updateCount();
</script>

</body>
</html>
<?php
$conn->close();
?>
