<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Include database connection
include '../../includes/db_connect.php';

// Get user role and email from session
$user_id = $_SESSION['user_id']; // Assuming you store user_id in the session
$user_email = $_SESSION['user_email'];
// Fetch prices from the database
$prices_query = "SELECT * FROM product ORDER BY id LIMIT 50";
$result = $conn->query($prices_query);

$product_prices = [];
$product_ids = [];
while ($row = $result->fetch_assoc()) {
    $product_name = $row['name']; // Assuming 'name' column exists in the 'product' table
    $single_price = $row['single_price']; // Assuming 'single_price' column exists
    $double_price = $row['double_price']; // Assuming 'double_price' column exists

    // Store both single and double prices for each product
    $product_prices[$product_name] = [
        'single' => $single_price,
        'double' => $double_price
    ];

    // Store product ID
    $product_ids[$product_name] = $row['id'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    // Fetch posted data
    $decaf_quantity = intval($_POST['decaf']);
    $cal_quantity = intval($_POST['cal']);
    $ic_quantity = intval($_POST['ic']);
    $total_price = floatval(str_replace('$', '', $_POST['total_price'])); // Remove $ and convert to float
    $user_id = $_SESSION['user_id']; // Assuming user_id is stored in session

    // Define the products and their potential categories (single and double)
    $products = [
        'Just Java' => ['quantity' => $decaf_quantity, 'category' => 'single'], // Single only
        'Cafe Au Lait' => ['quantity' => $cal_quantity, 'category' => $_POST['CAL'] === 'CAL_double' ? 'double' : 'single'],
        'Iced Cappuccino' => ['quantity' => $ic_quantity, 'category' => $_POST['IC'] === 'IC_double' ? 'double' : 'single']
    ];
    // Start transaction for consistency in multiple inserts
    $conn->begin_transaction();

    try {
        $insert_success = true;

        // Process each product
        foreach ($products as $product_name => $details) {
            if ($details['quantity'] > 0) {
                $product_id = $product_ids[$product_name]; 
                $quantity = $details['quantity'];
                $category = $details['category'];
                $price_per_item = $product_prices[$product_name][$category];
                $order_total = $price_per_item * $quantity; // Calculate total for this order

                // Insert order as a separate entry
                $sql = "INSERT INTO orders (user_id, product_id, category, quantity, total_price, order_date) 
                        VALUES (?, ?, ?, ?, ?, NOW())";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iisid", $user_id, $product_id, $category, $quantity, $order_total);

                if (!$stmt->execute()) {
                    throw new Exception("Error processing order: " . $stmt->error);
                }
                $stmt->close();
            }
        }

        // Commit the transaction if all inserts succeed
        $conn->commit();
        $order_success = true;

    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $conn->rollback();
        $order_error = $e->getMessage();
        $insert_success = false;
    }

    // Close the connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>JavaJam Coffee House Menu</title>
    <link rel="stylesheet" href="../assets/css/stylesheet.css">
    <style>
        .checkout-btn {
            margin-top: 10px;
            padding: 10px 0px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .success-message {
            color: green;
            font-weight: bold;
        }
        .error-message {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
<header>
</header>
<div class="wrapper">
    <div class="navbar">
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="menu.php" class="active">Menu</a></li>
                <li><a href="../../views/music.html">Music</a></li>
                <li><a href="../../views/jobs.html">Jobs</a></li>
            </ul>
        </nav>
    </div>
    <div class="content">
        <h1>Coffee at JavaJam</h1>
        <?php
        if (isset($order_success)) {
            echo "<p class='success-message'>Order placed successfully!</p>";
        } elseif (isset($order_error)) {
            echo "<p class='error-message'>$order_error</p>";
        }
        ?>
        <form id='orderForm' method='POST' action='./menu.php'>
        <table id="menuTable">
                <tr>
                    <td id="leftcolumn_table">Just Java</td>
                    <td>Regular house blend, decaffeinated coffee, or flavor of the day. <br>
                    <strong>Endless cup <span id="decaf_price_display">$2.00</span></strong></td>
                    <td>
                        <div class="form-group">
                            <input type="number" id="decaf" name="decaf" min="0" max="50" value="0">
                            <span id="decaf_error" class="error-message"></span>
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <input type="text" id="decaf_price" value="$0.00" readonly>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Cafe au Lait</td>
                    <td>House blended coffee infused into a smooth, steamed milk.<br>
                        <strong>
                            <input name="CAL" type="radio" id="CAL_single" value='CAL_single' checked>Single <span id="cal_single_price_display">$2.00</span>
                            <input name="CAL" type="radio" id="CAL_double" value='CAL_double'>Double <span id="cal_double_price_display">$3.00</span>
                        </strong>
                    </td>
                    <td>
                        <div class="form-group">
                            <input type="number" id="CAL" name="cal" min="0" max="50" value="0">
                            <span id="CAL_error" class="error-message"></span>
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <input type="text" id="CAL_price" value="$0.00" readonly>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Iced Cappuccino</td>
                    <td>Sweetened espresso blended with icy-cold milk and served in a chilled glass<br>
                        <strong>
                            <input name="IC" type="radio" id="IC_single" value="IC_single" checked>Single <span id="ic_single_price_display">$4.75</span>
                            <input name="IC" type="radio" id="IC_double" value="IC_double">Double <span id="ic_double_price_display">$5.75</span>
                        </strong>
                    </td>
                    <td>
                        <div class="form-group">
                            <input type="number" id="IC" name="ic" min="0" max="50" value="0">
                            <span id="IC_error" class="error-message"></span>
                        </div>
                    </td>
                    <td>
                        <div class="form-group">
                            <input type="text" id="IC_price" value="$0.00" readonly>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Total:</strong></td>
                    <td>
                        <input type="text" id="totalPrice" name="total_price" value="$0.00" readonly>
                        <input type="submit" name="checkout" value="Checkout" class="checkout-btn">
                    </td>
                </tr>
            </table>
            
        </form>
    </div>
</div>
<footer><br>Copyright &copy; 2024 JavaJam Coffee House<br>
    <a href="mailto:aquila@ng.com">aquila@ng.com</a>
</footer>
</div>

<script>
let productPrices = <?php echo json_encode($product_prices); ?>;

// Assign prices to the correct elements
document.getElementById('decaf_price_display').textContent = '$' + productPrices["Just Java"].single;
document.getElementById('cal_single_price_display').textContent = '$' + productPrices["Cafe Au Lait"].single;
document.getElementById('cal_double_price_display').textContent = '$' + productPrices["Cafe Au Lait"].double;
document.getElementById('ic_single_price_display').textContent = '$' + productPrices["Iced Cappuccino"].single;
document.getElementById('ic_double_price_display').textContent = '$' + productPrices["Iced Cappuccino"].double;

// Get all the necessary elements
let decaf = document.getElementById('decaf');
let decaf_price = document.getElementById('decaf_price');
let decaf_error = document.getElementById('decaf_error')

let CAL_single = document.getElementById('CAL_single');
let CAL_double = document.getElementById('CAL_double');
let CAL = document.getElementById('CAL');
let CAL_price = document.getElementById('CAL_price');
let CAL_error = document.getElementById('CAL_error')

let IC_single = document.getElementById('IC_single');
let IC_double = document.getElementById('IC_double');
let IC = document.getElementById('IC');
let IC_price = document.getElementById('IC_price');
let IC_error = document.getElementById('IC_error')

let price = document.getElementById('totalPrice');

// Function to calculate subtotal
function calculate_subTotal(inputElement, quantity) {
    let id = inputElement.getAttribute('id');
    
    if (id === 'decaf') {
        // Calculate decaf subtotal
        let decaf_total = (productPrices['Just Java'].single * quantity).toFixed(2);
        decaf_price.value = '$' + decaf_total;
    } 
    
    if (id === 'CAL') {
        // Calculate CAL subtotal based on selected radio button
        let element = document.querySelector('input[name="CAL"]:checked');
        let priceKey = element.id === 'CAL_single' ? 'single' : 'double';
        let CAL_total = (productPrices["Cafe Au Lait"][priceKey] * quantity).toFixed(2);
        CAL_price.value = '$' + CAL_total;
    } 
    
    if (id === 'IC') {
        // Calculate IC subtotal based on selected radio button
        let element = document.querySelector('input[name="IC"]:checked');
        let priceKey = element.id === 'IC_single' ? 'single' : 'double';
        let IC_total = (productPrices['Iced Cappuccino'][priceKey] * quantity).toFixed(2);
        IC_price.value = '$' + IC_total;
    }
    
    // Update total price
    updateTotalPrice();
}

// Function to update the total price
function updateTotalPrice() {
    let total = (
        parseFloat(decaf_price.value.replace('$', '')) +
        parseFloat(CAL_price.value.replace('$', '')) +
        parseFloat(IC_price.value.replace('$', ''))
    ).toFixed(2);
    if (total > 0 ){
        price.value = '$' + total;   
    }
}

function validateQuantity(inputElement, errorElement) {
    inputElement.addEventListener('change', function () {
        let quantity = parseFloat(inputElement.value);
        if (!quantity || quantity < 1) {
            // Insert error handling here for invalid quantity
            inputElement.classList.add('input-error')
            errorElement.textContent = "Required.";
        } else if (quantity > 50) {
            // Insert error handling for value greater than max order of 50
            inputElement.classList.add('input-error')
            errorElement.textContent = "Invalid";
            
        } else {
            inputElement.classList.remove('input-error')
            errorElement.textContent = ''
            calculate_subTotal(inputElement, quantity);
        }
    });
}

// Add event listeners for quantity inputs
validateQuantity(decaf, decaf_error);
validateQuantity(CAL, CAL_error);
validateQuantity(IC, IC_error);

// Add event listeners for radio buttons
document.querySelectorAll('input[name="CAL"]').forEach(function (radio) {
    radio.addEventListener('change', function () {
        calculate_subTotal(CAL, CAL.value); // Use the current quantity value
    });
});

document.querySelectorAll('input[name="IC"]').forEach(function (radio) {
    radio.addEventListener('change', function () {
        calculate_subTotal(IC, IC.value); // Use the current quantity value
    });
});
</script>
</body>
</html>