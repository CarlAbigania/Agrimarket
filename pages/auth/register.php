<?php
session_start();

// Database connection
$host = 'localhost';  // your database host
$user = 'root';       // your database username
$password = '';       // your database password
$dbname = 'online_shop';  // your database name

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['submit'])) {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $password = $_POST['password'];

    // Validate required fields
    if (empty($name) || empty($email) || empty($phone_number) || empty($password)) {
        echo "<script>alert('All fields are required.'); window.location.href='../userlogin/register.html';</script>";
        exit();
    }

    // Validate phone number (basic validation)
    if (!preg_match("/^[0-9]{10,15}$/", $phone_number)) {
        echo "<script>alert('Invalid phone number format.'); window.location.href='../userlogin/register.html';</script>";
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Assign the role as 'customer'
    $role = 'customer';

    // Check if the email already exists
    $checkQuery = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('This email is already registered.'); window.location.href='../userlogin/register.html';</script>";
        exit();
    }

    // Insert the new user into the database
    $query = "INSERT INTO users (name, email, phone_number, password, role) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $name, $email, $phone_number, $hashed_password, $role);
    $stmt->execute();

    // Check if the insertion was successful
    if ($stmt->affected_rows > 0) {
        // Redirect to the register page with success flag in URL
        header("Location: ../userlogin/register.html?success=1");
        exit();
    } else {
        echo "<script>alert('Error during registration.'); window.location.href='../userlogin/register.html';</script>";
    }


    $stmt->close();
}
$conn->close();
?>
