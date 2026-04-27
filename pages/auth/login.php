<?php
session_start();

// Database connection
require_once '../../includes/db.php';

if (isset($_POST['submit'])) {
    // Get form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to check if the user exists and get the hashed password
    $query = "SELECT * FROM users WHERE email = ?";  // Only select email, password, and role

    // Prepare and bind
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);  // Bind email only
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch user data
        $user = $result->fetch_assoc();
        $hashed_password = $user['password'];  // Get the hashed password from the database

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Password is correct, create session
            $_SESSION['user_logged_in'] = true;  // Mark the user as logged in
            $_SESSION['user_id'] = $user['id']; // Store the user's ID
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $user['role'];  // Store the user's role from the database

            // Redirect based on role
            if ($user['role'] == 'customer') {
                header("Location: ../home/home.php");  // Redirect to customer page
            } elseif ($user['role'] == 'admin') {
                header("Location: ../admin/admin_panel.php");  // Redirect to admin panel
            }
            exit();
        } else {
            // Invalid login: incorrect password
            echo "<script>alert('Invalid email or password.'); window.location.href='login.html';</script>";
        }
    } else {
        // Invalid login: no user found
        echo "<script>alert('Invalid email or password. No user found'); window.location.href='login.html';</script>";
    }

    $stmt->close();
}
$conn->close();
?>
