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

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    $name = trim($_POST['name'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if (!$id) {
        $errors[] = 'Invalid package ID.';
    }

    if ($name === '') {
        $errors[] = 'Package name is required.';
    } elseif (mb_strlen($name) > 150) {
        $errors[] = 'Package name must be 150 characters or less.';
    }

    if ($price === '' || !is_numeric($price) || (float)$price < 0) {
        $errors[] = 'Enter a valid package price.';
    }

    if ($description === '') {
        $errors[] = 'Package description is required.';
    }

    if (!$errors) {
        $sql = "UPDATE packages SET name = ?, price = ?, description = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            $errors[] = 'Unable to prepare the update query.';
        } else {
            $packagePrice = (float)$price;
            $stmt->bind_param("sdsi", $name, $packagePrice, $description, $id);

            if ($stmt->execute()) {
                $stmt->close();
                $conn->close();

                header("Location: view_destinations.php?updated=1");
                exit;
            }

            $errors[] = 'Package could not be updated. Please try again.';
            $stmt->close();
        }
    }
} elseif (!$id) {
    $errors[] = 'Invalid package ID.';
}

$name = $name ?? '';
$price = $price ?? '';
$description = $description ?? '';

if (!$errors && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $stmt = $conn->prepare(
        "SELECT id, name, price, description FROM packages WHERE id = ? LIMIT 1"
    );

    if (!$stmt) {
        $errors[] = 'Unable to load the package.';
    } else {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $package = $result->fetch_assoc();
        $stmt->close();

        if (!$package) {
            $errors[] = 'Package not found.';
        } else {
            $name = $package['name'];
            $price = $package['price'];
            $description = $package['description'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Tales | Edit Package</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin_edit_package.css">
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
            <button class="mobile-menu-btn" id="menuToggle" type="button">
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
                <span>Edit Package</span>
            </div>

            <section class="page-heading">
                <div>
                    <p class="eyebrow">PACKAGE MANAGEMENT</p>
                    <h2>Edit Package</h2>
                    <p>Update the information for this travel package.</p>
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

            <?php if (!$errors || $_SERVER['REQUEST_METHOD'] === 'POST'): ?>

            <section class="form-card">

                <div class="form-card-header">
                    <div class="header-icon">
                        <i class="bi bi-pencil-square"></i>
                    </div>
                    <div>
                        <h3>Package Details</h3>
                        <p>Make your changes and save the updated package.</p>
                    </div>
                </div>

                <form method="POST" action="" novalidate>

                    <input type="hidden" name="id" value="<?php echo (int)$id; ?>">

                    <div class="form-grid">

                        <div class="field full-width">
                            <label for="name">
                                Package Name <span>*</span>
                            </label>

                            <div class="input-wrap">
                                <i class="bi bi-geo-alt"></i>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value="<?php echo e($name); ?>"
                                    maxlength="150"
                                    required
                                >
                            </div>
                        </div>

                        <div class="field">
                            <label for="price">
                                Package Price <span>*</span>
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
                                    required
                                >
                            </div>
                        </div>

                        <div class="field full-width">
                            <label for="description">
                                Package Description <span>*</span>
                            </label>

                            <textarea
                                id="description"
                                name="description"
                                rows="7"
                                maxlength="2000"
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
                            Save Changes
                        </button>

                    </div>

                </form>

            </section>

            <?php endif; ?>

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

if (description && charCount) {
    const updateCount = () => {
        charCount.textContent = description.value.length;
    };

    description.addEventListener('input', updateCount);
    updateCount();
}
</script>

</body>
</html>

<?php
$conn->close();
?>
