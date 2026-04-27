<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: login.html");
    exit();
}

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "online_shop";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the user's email from the session
$userEmail = $_SESSION['email']; // Ensure the email is stored in session upon login

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_address'])) {
    // Get the new delivery address from the form
    $newDeliveryAddress = $_POST['delivery_address'];

    // Validate the new address
    if (empty($newDeliveryAddress)) {
        echo "<script>alert('Delivery address cannot be empty.'); window.location.href='delivery_info.html';</script>";
        exit();
    }

    // Update the delivery address in the database
    $stmt = $conn->prepare("UPDATE users SET delivery_address = ? WHERE email = ?");
    $stmt->bind_param("ss", $newDeliveryAddress, $userEmail);

    if ($stmt->execute()) {
        echo "<script>alert('Delivery address updated successfully!'); window.location.href='customer_panel.php';</script>";
    } else {
        echo "<script>alert('Error updating address. Please try again.'); window.location.href='delivery_info.html';</script>";
    }

    $stmt->close();
}

$conn->close();
?>
