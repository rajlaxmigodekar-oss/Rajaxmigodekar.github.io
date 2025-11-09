<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "myproject";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
$payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : '';

if ($order_id > 0 && !empty($payment_method)) {
    // Update order status (Assume status column exists in `orders` table)
    $update_order = "UPDATE orders SET status = 'Paid', payment_method = '$payment_method' WHERE order_id = $order_id";
    if ($conn->query($update_order) === TRUE) {
        echo "<h2>Payment Successful! Your order has been placed.</h2>";
        echo "<a href='orders.php'>View Orders</a>";
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Invalid payment request.";
}

$conn->close();
?>
