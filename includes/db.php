<?php
// Database credentials
if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1' || $_SERVER['HTTP_HOST'] === 'agri.local' || $_SERVER['HTTP_HOST'] === 'agrimarket.local') {
    // LOCAL SETTINGS (XAMPP)
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'agrimarket');
    define('BASE_URL', '/');
} else {
    // PRODUCTION SETTINGS (InfinityFree)
    define('DB_HOST', 'sql100.infinityfree.com');
    define('DB_USER', 'if0_41769329');
    define('DB_PASS', 'ByrwPqY0fs');
    define('DB_NAME', 'if0_41769329_agrimarket');
    define('BASE_URL', '/');
}

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
