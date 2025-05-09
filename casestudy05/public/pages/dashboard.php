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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($_POST['products'] as $id => $product) {
        $single_price = $conn->real_escape_string($product['single_price']); 
        $double_price = $conn->real_escape_string($product['double_price']);
        if ($double_price != ''){
            $double_price = "'$double_price'";
        }
        else {
            $double_price = "NULL";
        }
        $sql = "UPDATE product SET single_price = '$single_price', double_price = $double_price WHERE id = $id";
        $conn->query($sql);
    }
    // Redirect to prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF'] . "?update=success");
    exit;
}

$sql = "SELECT * FROM product ORDER BY id LIMIT 50;";
$result = $conn->query($sql);

$product_table_data = "";
while ($row = $result->fetch_assoc()){
    $id = $row['id'];
    $product_table_data .= "
    <tr>
        <th scope='row'>{$id}</th>
        <td>{$row['name']}</td>
        <td><input class='form-control' name='products[{$id}][single_price]' value='{$row['single_price']}' required></td>
        <td><input class='form-control' name='products[{$id}][double_price]' value='{$row['double_price']}' ></td>
    </tr>";
}
// $rows = mysqli_num_rows( $result );
$conn->close(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
</head>
<body>
    <div class='d-flex flex-row'>
        <div class="d-flex flex-column flex-shrink-0 p-3 text-dark bg-light" style="width: 20%; height: 100vh; overflow-y: auto; position: sticky;">
            <a href="admin.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
                <img src="../assets/images/logo.png" alt="logo" width="40px;" style='margin-right: 5px;'>
                <span class="fs-4">Dashboard</span>
            </a>
            <hr>
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item">
                        <a href="dashboard.php" class="nav-link active" aria-current="page">
                            <i class="bi bi-shop"></i>  Product
                        </a>
                    </li>
                    <li>
                        <a href="sales.php" class="nav-link text-dark">
                            <i class='bi bi-cash'></i> Sales
                        </a>
                    </li>
                </ul>
            <hr>
            <div class="dropdown">
                <a href="logout.php" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="../assets/images/supernatural.png" alt="" width="32" height="32" class="rounded-circle me-2">
                    <strong>
                        <?php echo $user_email; ?>
                    </strong>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                    <li><a class="dropdown-item" href="logout.php">Sign out</a></li>
                </ul>
            </div>
        </div>

        <div class='d-flex flex-row' style="margin: 20px; height: auto;">
            <div class='d-flex flex-column'>
                <h2 style='margin-bottom: 0'>Product</h2>
                <hr>
                <?php
                if (isset($_GET['update']) && $_GET['update'] == 'success') {
                    echo "<div class='alert alert-success' role='alert' style='padding: 10px;'>Prices updated successfully!</div>";
                }
                ?>
                <div class="card" style="width: 40rem;">
                    <div class="card-header d-flex justify-content-between">
                    <strong>Product Listing</strong>
                    <!-- Include Export Sales Report Button -->
                    </div>
                    <div class="card-body">
                        <form method='POST' action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Product</th>
                                        <th scope="col">Single Price</th>
                                        <th scope="col">Double Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo $product_table_data; ?>
                                </tbody>
                            </table>
                            <hr>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>

                <!-- <h2 style='margin: 20px 20px 0 0'>Price</h2>
                <hr>
                <div class="card" style="width: 40rem;">
                    <div class="card-header d-flex justify-content-between">
                    Test
                    </div>
                    <div class="card-body">
                        Test
                    </div>
                </div> -->
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