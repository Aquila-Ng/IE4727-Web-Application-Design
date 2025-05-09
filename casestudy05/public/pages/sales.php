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

$sql = "SELECT 
    o.id AS order_id,
    p.name AS product_name,
    CASE 
        WHEN o.category = 'single' THEN p.single_price
        WHEN o.category = 'double' THEN p.double_price
    END AS product_price,
    o.category,
    o.quantity,
    CASE 
        WHEN o.category = 'single' THEN p.single_price * o.quantity
        WHEN o.category = 'double' THEN p.double_price * o.quantity
    END AS total_price
    FROM 
        orders o
    JOIN 
        product p ON o.product_id = p.id
    ORDER BY o.id DESC
    LIMIT 5;";
$result = $conn->query($sql);
$sales_report = "";
while ($row = $result->fetch_assoc()){
    $id = $row['order_id'];
    $sales_report .= "
    <tr>
        <th scope='row'>{$id}</th>
        <td>{$row['product_name']}</td>
        <td>{$row['category']}</td>
        <td>{$row['product_price']}</td>
        <td>{$row['quantity']}</td>
        <td>{$row['total_price']}</td>
    </tr>";
}

$product_sales = "SELECT 
    p.name AS product_name,
    SUM(
        CASE 
            WHEN o.category = 'single' THEN o.quantity * p.single_price
            WHEN o.category = 'double' THEN o.quantity * p.double_price
        END
    ) AS total_sales,
    SUM(o.quantity) AS total_quantity_sold
FROM 
    orders o
JOIN 
    product p ON o.product_id = p.id
GROUP BY 
    p.name
LIMIT 50;";
$result = $conn->query($product_sales);
$product_sales_report = "";
while ($row = $result->fetch_assoc()){
    $name = $row['product_name'];
    $product_sales_report .= "
    <tr>
        <th scope='row'>{$name}</th>
        <td>{$row['total_sales']}</td>
        <td>{$row['total_quantity_sold']}</td>
    </tr>";
}

$category_sales = "SELECT 
    o.category AS category,
    SUM(
        CASE 
            WHEN o.category = 'single' THEN o.quantity * p.single_price
            WHEN o.category = 'double' THEN o.quantity * p.double_price
        END
    ) AS total_sales,
    SUM(o.quantity) AS total_quantity_sold
FROM 
    orders o
JOIN 
    product p ON o.product_id = p.id
GROUP BY 
    o.category;";
$result = $conn->query($category_sales);
$category_sales_report = "";
while ($row = $result->fetch_assoc()){
    $category = $row['category'];
    $category_sales_report .= "
    <tr>
        <th scope='row'>{$category}</th>
        <td>{$row['total_sales']}</td>
        <td>{$row['total_quantity_sold']}</td>
    </tr>";
}

$popular_query = "SELECT 
    p.name AS product_name,
    SUM(o.quantity) AS total_quantity_sold
FROM 
    orders o
JOIN 
    product p ON o.product_id = p.id
GROUP BY 
    p.name
ORDER BY 
    total_quantity_sold DESC
LIMIT 1;";

$result = $conn->query($popular_query);
while ($row = $result->fetch_assoc()){
    $name = $row["product_name"];
    $sales = $name;
}

$sales_query = "SELECT 
    p.name AS product_name,
    SUM(
        CASE 
            WHEN o.category = 'single' THEN o.quantity * p.single_price
            WHEN o.category = 'double' THEN o.quantity * p.double_price
        END
    ) AS total_sales
FROM 
    orders o
JOIN 
    product p ON o.product_id = p.id
GROUP BY 
    p.name
ORDER BY 
    total_sales DESC
LIMIT 1;";

$result = $conn->query($sales_query);
while ($row = $result->fetch_assoc()){
    $name = $row["product_name"];
    $popular = $name;
}

$sold_query = "SELECT 
    o.category AS category,
    SUM(o.quantity) AS total_quantity_sold
FROM 
    orders o
GROUP BY 
    o.category
ORDER BY 
    total_quantity_sold DESC
LIMIT 1;";

$result = $conn -> query($sold_query);
while ($row = $result->fetch_assoc()){
    $category = $row["category"];
}

$conn -> close();
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
                        <a href="dashboard.php" class="nav-link text-dark" aria-current="page">
                            <i class="bi bi-shop"></i>  Product
                        </a>
                    </li>
                    <li>
                        <a href="sales.php" class="nav-link active">
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
                    <li><a class="dropdown-item" href='logout.php'>Sign out</a></li>
                </ul>
            </div>
        </div>

        <div class='d-flex flex-row' style="margin: 20px; height: auto;">
            <div class='d-flex flex-column'>
                <h2 style='margin-bottom: 0'>Sales Report</h2>
                <hr>
                <div class="card" style="width: 40rem;">
                    <div class="card-header d-flex justify-content-between">
                    <strong>Orders Report</strong>
                    <!-- Include Export Sales Report Button -->
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Order ID</th>
                                    <th scope="col">Product</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Sub Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php echo $sales_report;?>
                            </tbody>
                        </table>
                        <!-- <button type="submit" class="btn btn-primary">Update</button> -->  
                    </div>
                </div>

                <h2 style='margin-top: 20px; margin-bottom: 0;'>Sales By Product</h2>
                <hr>
                <div class="card" style="width: 40rem;">
                    <div class="card-header d-flex justify-content-between">
                    <strong>Sales by Product</strong>
                    <!-- Include Export Sales Report Button -->
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Product</th>
                                    <th scope="col">Total Sales</th>
                                    <th scope="col">Total Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php echo $product_sales_report;?>
                            </tbody>
                        </table>
                        <!-- <button type="submit" class="btn btn-primary">Update</button> -->
                    </div>
                </div>

                <h2 style='margin-top: 20px; margin-bottom: 0;'>Sales By Category</h2>
                <hr>
                <div class="card" style="width: 40rem;">
                    <div class="card-header d-flex justify-content-between">
                    <strong>Sales by Categories</strong>
                    <!-- Include Export Sales Report Button -->
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Category</th>
                                    <th scope="col">Total Sales</th>
                                    <th scope="col">Total Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php echo $category_sales_report;?>
                            </tbody>
                        </table>
                        <!-- <button type="submit" class="btn btn-primary">Update</button> -->
                    </div>
                </div>
            </div>

            <div class='d-flex flex-column'>
                <div class="card" style="margin: 20px 0 0 20px; width: 17.5rem;">
                    <div class="card-header d-flex justify-content-between">
                    <strong>Summary</strong>
                    <!-- Include Export Sales Report Button -->
                    </div>
                    <div class="card-body">
                        <strong>Most Popular:</strong> <?php echo $popular ?><br>
                        <strong>Highest Sales:</strong> <?php echo $sales ?>
                        <strong>Most Sold (Category):</strong> <?php echo $category ?>
                        <!-- <button type="submit" class="btn btn-primary">Update</button> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>