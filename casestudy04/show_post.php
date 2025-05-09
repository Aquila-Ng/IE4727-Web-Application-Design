<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the form data is submitted
    echo "<h1>Job Application Details</h1>";
    echo "<table border='1' cellpadding='10'>";
    
    // Display each field from the POST request
    foreach ($_POST as $key => $value) {
        // Sanitize output
        $safe_key = htmlspecialchars($key);
        $safe_value = htmlspecialchars($value);
        
        echo "<tr>";
        echo "<td><strong>" . ucfirst($safe_key) . "</strong></td>";
        echo "<td>" . $safe_value . "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "No form data submitted.";
}
?>
