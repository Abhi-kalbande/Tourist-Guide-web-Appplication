<?php
session_start();
require_once "../config/db_connection.php";

if (empty($_SESSION['admin_logged_in'])) {
    header("Location: Admin_login.php");
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: view_destinations.php");
    exit;
}

$stmt = $conn->prepare(
    "SELECT id, name, price, description, created_at
     FROM packages
     WHERE id = ?
     LIMIT 1"
);

$stmt->bind_param("i", $id);
$stmt->execute();

$package = $stmt->get_result()->fetch_assoc();

if (!$package) {
    header("Location: view_destinations.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Package Details | Travel Tales</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link rel="stylesheet"
          href="../assets/css/view_package_details.css">
</head>

<body>

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2>Package Details</h2>
            <p class="text-muted mb-0">
                View travel package information.
            </p>
        </div>

        <a href="view_destinations.php" class="btn btn-secondary">
            Back to Packages
        </a>

    </div>

    <div class="card package-card">

        <div class="card-body">

            <h3 class="mb-4">
                <?php echo htmlspecialchars($package['name']); ?>
            </h3>

            <div class="row">

                <div class="col-md-6 mb-4">
                    <strong>Package ID</strong>
                    <p>#<?php echo $package['id']; ?></p>
                </div>

                <div class="col-md-6 mb-4">
                    <strong>Package Name</strong>
                    <p>
                        <?php echo htmlspecialchars($package['name']); ?>
                    </p>
                </div>

                <div class="col-md-6 mb-4">
                    <strong>Price</strong>
                    <p>
                        ₹<?php echo number_format(
                            (float)$package['price'],
                            2
                        ); ?>
                    </p>
                </div>

                <div class="col-md-6 mb-4">
                    <strong>Created Date</strong>
                    <p>
                        <?php echo date(
                            'd M Y',
                            strtotime($package['created_at'])
                        ); ?>
                    </p>
                </div>

                <div class="col-12">
                    <strong>Description</strong>
                    <p class="description">
                        <?php echo nl2br(
                            htmlspecialchars($package['description'])
                        ); ?>
                    </p>
                </div>

            </div>

            <div class="mt-3">

                <a href="update_package.php?id=<?php echo $package['id']; ?>"
                   class="btn btn-warning">
                    Edit Package
                </a>

                <a href="view_destinations.php"
                   class="btn btn-secondary">
                    Back
                </a>

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