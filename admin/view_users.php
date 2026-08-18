<?php
include('../config/db_connection.php'); 

$sql = "SELECT id, name, email, created_at
        FROM user_information
        ORDER BY id DESC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Database Error: " . mysqli_error($conn));
}

$total_users = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Users | Travel Tales</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/view_users.css">
</head>

<body>

    <div class="container-fluid py-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>Manage Users</h2>
                <p class="text-muted mb-0">
                    View and manage registered users.
                </p>
            </div>

            <a href="add_user.php" class="btn btn-warning">
                + Add User
            </a>
        </div>


        <!-- Total Users -->
        <div class="card mb-4 stats-card">
            <div class="card-body">
                <h6 class="text-muted">Total Users</h6>
                <h3><?php echo $total_users; ?></h3>
            </div>
        </div>


        <!-- Users -->
        <div class="card">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4>Registered Users</h4>

                    <input type="text" id="searchUser" class="form-control search-box" placeholder="Search users...">
                </div>

                <div class="table-responsive">

                    <table class="table table-hover" id="usersTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Registered</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php while ($user = mysqli_fetch_assoc($result)): ?>

                                <tr>
                                    <td>#<?php echo $user['id']; ?></td>

                                    <td>
                                        <?php echo htmlspecialchars($user['name']); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars($user['email']); ?>
                                    </td>

                                    <td>
                                        <?php echo date('d M Y', strtotime($user['created_at'])); ?>
                                    </td>

                                    <td>
                                        <a href="view_user_details.php?id=<?php echo $user['id']; ?>"
                                            class="btn btn-sm btn-primary">
                                            View
                                        </a>

                                        <a href="edit_user.php?id=<?php echo $user['id']; ?>"
                                            class="btn btn-sm btn-warning">
                                            Edit
                                        </a>

                                        <a href="delete_user.php?id=<?php echo $user['id']; ?>"
                                            class="btn btn-sm btn-danger" onclick="return confirm('Delete this user?');">
                                            Delete
                                        </a>
                                    </td>
                                </tr>

                            <?php endwhile; ?>

                        </tbody>
                    </table>

                </div>
            </div>
        </div>

    </div>


    <script>
        document.getElementById("searchUser").addEventListener("keyup", function () {

            let search = this.value.toLowerCase();

            document.querySelectorAll("#usersTable tbody tr").forEach(row => {
                row.style.display =
                    row.innerText.toLowerCase().includes(search) ? "" : "none";
            });

        });
    </script>

</body>

</html>