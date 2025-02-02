<?php
// Start the session
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit;
}

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "online_shop";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if feedback ID is provided for deletion
if (isset($_GET['feedback_id'])) {
    $feedback_id = intval($_GET['feedback_id']); // Sanitize the input

    // Debugging: Log the feedback_id
    error_log("Attempting to delete feedback with ID: $feedback_id");

    // Prepare and execute the delete query
    $delete_sql = "DELETE FROM contact_messages WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);

    if ($stmt) {
        $stmt->bind_param("i", $feedback_id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo "<script>alert('Feedback deleted successfully.'); window.location.href='admin_feedback.php';</script>";
            } else {
                echo "<script>alert('No feedback found with the given ID.'); window.location.href='admin_feedback.php';</script>";
            }
        } else {
            echo "<script>alert('Failed to execute the delete query: " . $stmt->error . "'); window.location.href='admin_feedback.php';</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Failed to prepare the SQL statement: " . $conn->error . "'); window.location.href='admin_feedback.php';</script>";
    }
}

// Fetch all feedback entries
$sql = "SELECT * FROM contact_messages ORDER BY created_at DESC";
$result = $conn->query($sql);

?>