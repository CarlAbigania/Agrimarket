<?php
// Database connection details
$host = "localhost";
$username = "root"; 
$password = "";
$database = "online_shop";
// Connect to the database
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize input
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['Message']));

    // Validate input
    if (!empty($name) && !empty($email) && !empty($message)) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Prepare the SQL statement
            $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $message);

            // Execute the statement and check if it was successful
            if ($stmt->execute()) {
                echo "<script>alert('Message submitted successfully!'); window.location.href = '../customer_side/customer_panel.php';</script>";
            } else {
                echo "<script>alert('Failed to submit your message. Please try again later.'); window.history.back();</script>";
            }

            // Close the statement
            $stmt->close();
        } else {
            echo "<script>alert('Invalid email address. Please try again.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('All fields are required. Please fill out the form completely.'); window.history.back();</script>";
    }
} else {
    // Redirect to the form if accessed directly
    header("Location: ../customer_side/customer_panel.php");
    exit();
}

// Close the database connection
$conn->close();
?>
