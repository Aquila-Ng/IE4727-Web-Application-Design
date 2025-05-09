<?php
// Start session
session_start();

// Include database connection
include '../../includes/db_connect.php';

// Initialize variables
$email = $password = "";
$email_err = $password_err = $general_err = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize input  
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password']; // We'll hash this, so no need to sanitize

    // Validate email
    if (empty($email)) {
        $email_err = "Please enter an email address.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT email FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $email_err = "This email is already registered";
        }
        $stmt->close();
    }

    // Validate password
    $password_regex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";
    $password = trim($password);
    if (empty($password)) {
        $password_err = "Password required";
    } else {
        $password_err = "Password must contain the following: <br>";

        // Check length
        if (strlen($password) < 8) {
            $password_err .= "- 8 or more characters<br>";
        }

        // Check for uppercase
        if (!preg_match("/(?=.*?[A-Z])/", $password)) {
            $password_err .= "- One uppercase letter<br>";
        }

        // Check for lowercase
        if (!preg_match("/(?=.*?[a-z])/", $password)) {
            $password_err .= "- One lowercase letter<br>";
        }

        // Check for digit
        if (!preg_match("/(?=.*?[0-9])/", $password)) {
            $password_err .= "- One digit<br>";
        }

        // Check for special character
        if (!preg_match("/(?=.*?[#?!@$%^&*-.])/", $password)) {
            $password_err .= "- One special character<br>";
        }

        // If the error message only contains the heading, then no issues were found
        if ($password_err === "Password must contain the following: <br>") {
            $password_err = ""; // Clear error if no issues
        }
    }

    // If no errors, proceed with registration
    if (empty($email_err) && empty($password_err)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if this is the first user
        $role = 'user'; // Default role is user
        $stmt = $conn->prepare("SELECT COUNT(*) as user_count FROM users");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        // If no users exist yet, assign the first user as 'admin'
        if ($row['user_count'] == 0) {
            $role = 'admin';
        }

        // Insert the user into the database
        $stmt = $conn->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $hashed_password, $role);

        if ($stmt->execute()) {
            // Redirect to avoid form resubmission
            header("Location: register.php?success=1");
            exit();
        } else {
            // Failure message
            $general_err = "<div class='alert alert-danger' role='alert'>Oops! Something went wrong. Please try again later.</div>";
        }
        $stmt->close();
    }
}

// Display success message if redirected
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $general_err = "<div class='alert alert-success' role='alert'>Registration successful! Please <a href='login.php'>login</a>.</div>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
    <div class="login-container">
        <?php echo $general_err; ?>
        <div class="card" style="width: 40rem;">    
            <div class='card-header d-flex justify-content-between'>
                <h5 class='card-title'>Register</h5>
            </div>
            <form style="padding: 15px;" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method='POST' novalidate>
                <div class="form-group mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" name='email' class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" id="email" placeholder="Enter email" value="<?php echo $email; ?>" required>
                    <div class="invalid-feedback">
                        <?php echo $email_err; ?>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name='password' class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" id="password" placeholder="Password" required>
                    <div class="invalid-feedback">
                        <?php echo $password_err; ?>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Register</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>