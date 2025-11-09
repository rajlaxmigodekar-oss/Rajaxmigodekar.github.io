<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "myproject";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user ID from session (Assuming user is logged in)
session_start();
$user_id = $_SESSION['user_id'] ?? 0; // Change according to session variable

// Fetch customer order history
$sql = "SELECT id, total_price, order_status, created_at FROM orders WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f8f9fa; }
        h2 { text-align: center; color: #007bff; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: center; }
        th { background-color: #007bff; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .status { padding: 5px 10px; border-radius: 5px; font-weight: bold; }
        .pending { background-color: orange; color: white; }
        .completed { background-color: green; color: white; }
        .failed { background-color: red; color: white; }
        .no-orders { text-align: center; font-weight: bold; color: red; padding: 20px; }
    </style>
</head>
<body>
    <h2>📦 My Orders</h2>
    <table>
        <tr>
            <th>🆔 Order ID</th>
            <th>📅 Date</th>
            <th>💰 Total Price</th>
            <th>📌 Status</th>
        </tr>
        <?php if (count($orders) > 0) { ?>
            <?php foreach ($orders as $order) { ?>
            <tr>
                <td>#<?php echo $order['id']; ?></td>
                <td><?php echo date('Y-m-d', strtotime($order['created_at'])); ?></td>
                <td><strong>₹<?php echo number_format($order['total_price'], 2); ?></strong></td>
                <td>
                    <span class="status <?php echo strtolower($order['order_status']); ?>">
                        <?php echo ucfirst($order['order_status']); ?>
                    </span>
                </td>
            </tr>
            <?php } ?>
        <?php } else { ?>
            <tr><td colspan="4" class="no-orders">🚫 No Orders Found</td></tr>
        <?php } ?>
    </table>
</body>
</html>
