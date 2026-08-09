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

$fullName = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $password = $_POST['password'] ?? '';

    if ($fullName === '') {
        $errors[] = 'Full name is required.';
    } elseif (mb_strlen($fullName) > 150) {
        $errors[] = 'Full name must be 150 characters or less.';
    }

    if ($email === '') {
        $errors[] = 'Email address is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Enter a valid email address.';
    } elseif (mb_strlen($email) > 150) {
        $errors[] = 'Email address is too long.';
    }

    if ($password === '') {
        $errors[] = 'Password is required.';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password must contain at least 6 characters.';
    }

    if (!$errors) {
        $check = $conn->prepare(
            "SELECT id FROM user_information WHERE email = ? LIMIT 1"
        );

        if (!$check) {
            $errors[] = 'Unable to validate the email address.';
        } else {
            $check->bind_param("s", $email);
            $check->execute();
            $existingUser = $check->get_result()->fetch_assoc();
            $check->close();

            if ($existingUser) {
                $errors[] = 'A user with this email address already exists.';
            }
        }
    }

    if (!$errors) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare(
            "INSERT INTO user_information (full_name, email, password)
             VALUES (?, ?, ?)"
        );

        if (!$stmt) {
            $errors[] = 'Unable to prepare the user query.';
        } else {
            $stmt->bind_param("sss", $fullName, $email, $hashedPassword);

            if ($stmt->execute()) {
                $stmt->close();
                $conn->close();

                header("Location: view_users.php?added=1");
                exit;
            }

            $errors[] = 'User could not be added. Please try again.';
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
    <title>Travel Tales | Add User</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin_add_user.css">
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

            <a href="view_destinations.php" class="nav-link">
                <i class="bi bi-map-fill"></i>
                <span>Packages</span>
            </a>

            <a href="add_destination.php" class="nav-link">
                <i class="bi bi-plus-circle"></i>
                <span>Add Package</span>
            </a>

            <a href="add_user.php" class="nav-link active">
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

            <div class="topbar-title">User Management</div>

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
                <a href="admin_dashboard.php">Dashboard</a>
                <i class="bi bi-chevron-right"></i>
                <span>Add User</span>
            </div>

            <section class="page-heading">
                <div>
                    <p class="eyebrow">USER MANAGEMENT</p>
                    <h2>Add New User</h2>
                    <p>Create a customer account for your Travel Tales website.</p>
                </div>
            </section>

            <?php if ($errors): ?>
                <div class="alert-box">
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
                        <i class="bi bi-person-plus-fill"></i>
                    </div>
                    <div>
                        <h3>User Details</h3>
                        <p>Enter the customer's basic account information.</p>
                    </div>
                </div>

                <form method="POST" action="" novalidate>

                    <div class="form-grid">

                        <div class="field full-width">
                            <label for="full_name">
                                Full Name <span>*</span>
                            </label>

                            <div class="input-wrap">
                                <i class="bi bi-person"></i>
                                <input
                                    type="text"
                                    id="full_name"
                                    name="full_name"
                                    value="<?php echo e($fullName); ?>"
                                    maxlength="150"
                                    placeholder="e.g. Rahul Sharma"
                                    autocomplete="name"
                                    required
                                >
                            </div>
                        </div>

                        <div class="field">
                            <label for="email">
                                Email Address <span>*</span>
                            </label>

                            <div class="input-wrap">
                                <i class="bi bi-envelope"></i>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="<?php echo e($email); ?>"
                                    maxlength="150"
                                    placeholder="customer@example.com"
                                    autocomplete="email"
                                    required
                                >
                            </div>
                        </div>

                        <div class="field">
                            <label for="password">
                                Temporary Password <span>*</span>
                            </label>

                            <div class="input-wrap password-wrap">
                                <i class="bi bi-lock"></i>
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    minlength="6"
                                    placeholder="Minimum 6 characters"
                                    autocomplete="new-password"
                                    required
                                >
                                <button type="button" id="togglePassword" class="password-toggle" aria-label="Show password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>

                            <small>Password is stored securely using PHP password hashing.</small>
                        </div>

                    </div>

                    <div class="form-actions">
                        <a href="admin_dashboard.php" class="secondary-btn">
                            <i class="bi bi-arrow-left"></i>
                            Cancel
                        </a>

                        <button type="submit" class="primary-btn">
                            <i class="bi bi-person-check"></i>
                            Add User
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

const togglePassword = document.getElementById('togglePassword');
const password = document.getElementById('password');

togglePassword?.addEventListener('click', () => {
    const isPassword = password.type === 'password';
    password.type = isPassword ? 'text' : 'password';

    togglePassword.innerHTML = isPassword
        ? '<i class="bi bi-eye-slash"></i>'
        : '<i class="bi bi-eye"></i>';
});
</script>

</body>
</html>

<?php
$conn->close();
?>
