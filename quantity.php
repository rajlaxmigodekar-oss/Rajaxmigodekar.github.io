<?php
// Database Connection
include 'db_connect.php';

$product_id = $_GET['id']; // Get Product ID from URL
$query = "SELECT stock FROM products WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$available_stock = $product['stock'];

$stmt->close();
$conn->close();
?>
