<?php
session_start();
include '../php/db_connection.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['order_id'])) {
    echo "Order not found.";
    exit;
}

$customer_id = $_SESSION['user_id'];
$order_id = intval($_GET['order_id']);

// Fetch the order details
$sql_order = "SELECT order_id, customer_id, order_date, total FROM orders WHERE order_id = ? AND customer_id = ?";
$stmt = $conn->prepare($sql_order);
$stmt->bind_param("ii", $order_id, $customer_id);
$stmt->execute();
$order_result = $stmt->get_result();

if ($order_result->num_rows === 0) {
    echo "Order not found.";
    exit;
}

$order = $order_result->fetch_assoc();

// Fetch the order items
$sql_order_items = "SELECT oi.quantity, p.product_name, oi.price
                    FROM order_items oi
                    JOIN products p ON oi.product_id = p.product_id
                    WHERE oi.order_id = ?";
$stmt = $conn->prepare($sql_order_items);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_items_result = $stmt->get_result();

// Start the HTML output for printing
echo '<html><head><title>Print Order</title></head><body>';
echo '<h1>Order Confirmation</h1>';
echo '<p><strong>Order ID:</strong> ' . $order['order_id'] . '</p>';
echo '<p><strong>Order Date:</strong> ' . date('F j, Y, g:i a', strtotime($order['order_date'])) . '</p>';
echo '<p><strong>Total Amount:</strong> ₱ ' . number_format($order['total'], 2) . '</p>';

echo '<table border="1" cellpadding="5" cellspacing="0" width="100%">
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total</th>
        </tr>';

while ($item = $order_items_result->fetch_assoc()) {
    echo '<tr>';
    echo '<td>' . $item['product_name'] . '</td>';
    echo '<td>' . $item['quantity'] . '</td>';
    echo '<td>₱ ' . number_format($item['price'], 2) . '</td>';
    echo '<td>₱ ' . number_format($item['quantity'] * $item['price'], 2) . '</td>';
    echo '</tr>';
}

echo '</table>';
echo '<script>window.print();</script>';
echo '</body></html>';
