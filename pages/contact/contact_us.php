<?php
session_start();
require_once '../../includes/db.php';

$success_message = '';
$error_message = '';

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['Message'];

    if (empty($name) || empty($email) || empty($message)) {
        $error_message = 'All fields are required.';
    } else {
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $message);
        if ($stmt->execute()) {
            $success_message = 'Your message has been sent successfully!';
        } else {
            $error_message = 'Failed to send message. Please try again.';
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
    <title>Contact Us - AgriMarket</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/contact.css">
</head>
<body>
    <div class="login-container">
        <div class="image-section">
            <a href="../home/home.php" class="x-btn">X</a>
            <h2>Send Us a Message</h2>
            <div class="info">
                <?php if (!empty($success_message)): ?>
                    <p style="color: #00B85E; font-weight: bold;"><?php echo htmlspecialchars($success_message); ?></p>
                <?php endif; ?>
                <?php if (!empty($error_message)): ?>
                    <p style="color: red; font-weight: bold;"><?php echo htmlspecialchars($error_message); ?></p>
                <?php endif; ?>
                <form action="contact_us.php" method="POST">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required>

                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>

                    <label for="message">Message</label>
                    <textarea id="message" name="Message" required></textarea>

                    <button type="submit" name="submit">Send</button>
                </form>
            </div>
        </div>

        <div class="form-section">
            <div class="form-header">
                <h2>Get in Touch</h2>
                <p>We'd love to hear from you. Reach out through any of these channels.</p>
            </div>
            
            <div class="contact-list">
                <div class="contact-card-item">
                    <div class="icon-box">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <div class="contact-text">
                        <h3>Address</h3>
                        <span>Plot 5, Idu Industrial Estate, Abuja</span>
                    </div>
                </div>

                <div class="contact-card-item">
                    <div class="icon-box">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <div class="contact-text">
                        <h3>Email</h3>
                        <span>hello@agromarket.com</span>
                    </div>
                </div>

                <div class="contact-card-item">
                    <div class="icon-box">
                        <i class="fa-solid fa-phone"></i>
                    </div>
                    <div class="contact-text">
                        <h3>Phone Number</h3>
                        <span>+234 801 234 5678</span>
                    </div>
                </div>
            </div>

            <div class="social-links">
                <a href="#"><i class="fa-brands fa-facebook"></i></a>
                <a href="#"><i class="fa-brands fa-twitter"></i></a>
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
            </div>
        </div>
    </div>
</body>
</html>
