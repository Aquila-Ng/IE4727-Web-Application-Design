<?php
// Start session
session_start();

// Include database connection
include '../../includes/db_connect.php';

// Initialize variables
$email = $password = "";
$email_err = $password_err = $general_err = "";

// Check if user is already logged in, if so redirect
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: dashboard.php"); // Redirect to dashboard or any other protected page
    exit;
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize input
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password']; // No need to sanitize as it's used for hashing

    // Validate email
    if (empty($email)) {
        $email_err = "Please enter an email address.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format";
    }

    // Validate password
    if (empty($password)) {
        $password_err = "Please enter a password.";
    }

    // If no validation errors, attempt to log in the user
    if (empty($email_err) && empty($password_err)) {
        $stmt = $conn->prepare("SELECT id, email, password, role FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Store user information in session
                $_SESSION['loggedin'] = true;
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_id'] = $user['id'];

                // Redirect to avoid form resubmission\
                if ($user['role'] == 'admin'){
                    header("Location: dashboard.php");// Or any protected page
                    exit();
                }
                if ($user['role'] == 'user') {
                    header("Location: index.html");
                }
            } else {
                $password_err = "The password you entered is incorrect.";
            }
        } else {
            $email_err = "No account found with that email address.";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
        .alert alert-success {
            padding: 10px;
        }
        .alert alert-danger {
            padding: 10px;
        }
    </style>
</head>
<body>
    <?php if (!empty($general_err)) echo $general_err; ?>
    <div class="login-container">
        <div class="card" style="width: 30rem;">
            <div class="card-header d-flex justify-content-between">
                <h5 class="card-title">Login</h5>
            </div>
            <form style="padding: 15px;" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" novalidate>
                <div class="form-group mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" id="email" placeholder="Enter email" value="<?php echo $email; ?>" required>
                    <div class="invalid-feedback">
                        <?php echo $email_err; ?>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" id="password" placeholder="Password" required>
                    <div class="invalid-feedback">
                        <?php echo $password_err; ?>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>