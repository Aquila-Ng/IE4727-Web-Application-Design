<?php
    // Include the database connection file
    include '../../includes/db_connect.php';

    // Test the connection by querying the database
    $sql = "SELECT * FROM product  LIMIT 50;"; // This will return the current database name
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch and print the rows
        while ($row = $result->fetch_assoc()) {
            // echo "Email: " . $row["email"] . "<br>";
            // echo "Password: " . $row["password"] . "<br>";
            // echo "Role: " . $row["role"] . "<br><br>";
            echo "Name: " . $row["name"] . "<br>";
            echo "Single Price: " . $row["single_price"] . "<br>";
            echo "Double Price: " . $row["double_price"] . "<br><br>";
        }
    } else {
        echo "No results found.";
    }

    // Close the connection
    $conn->close();
?>