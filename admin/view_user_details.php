<?php
include('../config/db_connection.php'); 

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: view_users.php");
    exit;
}

$id = (int) $_GET['id'];

$stmt = mysqli_prepare(
    $conn,
    "SELECT id, full_name, email, age, contact_number
     FROM user_information
     WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    header("Location: view_users.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>User Details | Travel Tales</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/view_users.css">
</head>

<body>

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>User Details</h2>
            <p class="text-muted mb-0">View registered user information.</p>
        </div>

        <a href="view_users.php" class="btn btn-secondary">
            Back to Users
        </a>
    </div>

    <div class="card user-card">

        <div class="card-body">

            <h4 class="mb-4">
                <?php echo htmlspecialchars($user['full_name']); ?>
            </h4>

            <div class="row">

                <div class="col-md-6 mb-3">
                    <strong>User ID</strong>
                    <p>#<?php echo $user['id']; ?></p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Full Name</strong>
                    <p><?php echo htmlspecialchars($user['full_name']); ?></p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Email</strong>
                    <p><?php echo htmlspecialchars($user['email']); ?></p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Age</strong>
                    <p><?php echo htmlspecialchars($user['age']); ?></p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Contact Number</strong>
                    <p><?php echo htmlspecialchars($user['contact_number']); ?></p>
                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>