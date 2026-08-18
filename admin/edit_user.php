<?php
session_start();
require_once __DIR__ . '/../config/db_connection.php';

if (empty($_SESSION['admin_logged_in'])) {
    header('Location: Admin_login.php');
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: view_users.php');
    exit;
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare(
    "SELECT id, name, email FROM user_information WHERE id = ?"
);
$stmt->bind_param("i", $id);
$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    header('Location: view_users.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    if ($name === '' || $email === '') {
        $error = "Name and email are required.";
    } else {

        $stmt = $conn->prepare(
            "UPDATE user_information SET name = ?, email = ? WHERE id = ?"
        );

        $stmt->bind_param("ssi", $name, $email, $id);

        if ($stmt->execute()) {
            header('Location: view_users.php');
            exit;
        }

        $error = "Unable to update user.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit User | Travel Tales</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/view_users.css">
</head>

<body>

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Edit User</h2>
            <p class="text-muted mb-0">Update registered user information.</p>
        </div>

        <a href="view_users.php" class="btn btn-secondary">
            Back
        </a>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <div class="card edit-card">
        <div class="card-body">

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Name</label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="<?php echo htmlspecialchars($user['name']); ?>"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="<?php echo htmlspecialchars($user['email']); ?>"
                        required
                    >
                </div>

                <button type="submit" class="btn btn-warning">
                    Update User
                </button>

            </form>

        </div>
    </div>

</div>

</body>
</html>