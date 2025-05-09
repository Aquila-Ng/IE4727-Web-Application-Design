<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Include database connection if necessary (e.g., for retrieving specific user data)
include '../../includes/db_connect.php';

// Get user role from session
$user_role = $_SESSION['user_role'];
$user_email = $_SESSION['user_email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card" style="width: 40rem;">
            <div class="card-header d-flex justify-content-between">
                <h5 class="card-title">Welcome, <?php echo htmlspecialchars($user_email); ?></h5>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
            <div class="card-body">
                <?php if ($user_role == 'admin'): ?>
                    <h6 class="card-subtitle mb-2 text-muted">Admin Panel</h6>
                    <p class="card-text">You have access to admin functionality.</p>
                    <!-- Add admin-specific features here -->
                <?php else: ?>
                    <h6 class="card-subtitle mb-2 text-muted">User Dashboard</h6>
                    <p class="card-text">Welcome to your user dashboard.</p>
                    <!-- Add user-specific features here -->
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close database connection if necessary
$conn->close();
?>