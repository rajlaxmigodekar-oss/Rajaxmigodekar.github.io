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

$sql = "SELECT 
            DATE_FORMAT(o.created_at, '%Y-%m') AS month, 
            o.id AS order_id,
            u.username AS buyer_name,
            u.id AS user_id,
            p.name AS product_name,
            p.image AS product_image,
            oi.price 
        FROM orders o
        JOIN users u ON o.user_id = u.id
        JOIN order_items oi ON o.id = oi.order_id
        JOIN products p ON oi.product_id = p.id
        WHERE o.order_status = 'delivered'
        ORDER BY month DESC, o.created_at DESC";


$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}

// Store data in array
$sales_data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $sales_data[$row['month']][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background-color: #007bff; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        img { width: 50px; height: 50px; object-fit: cover; border-radius: 5px; }
    </style>
</head>
<body>
  <?php include 'adminnav.php';?>
<h2>📊 Monthly Sales Report</h2>

<?php foreach ($sales_data as $month => $orders) { ?>
    <h3>🗓 <?php echo htmlspecialchars($month); ?></h3>
    <table>
        <tr>
            <th>🆔 Order ID</th>
            <th>🖼 Product</th>
            <th>📛 Name</th>
            <th>👤 User ID</th>
            <th>🛍 Buyer</th>
            <th>💰 Price (₹)</th>
            <th>📄 Invoice</th>  <!-- New Column for Invoice -->
        </tr>
        <?php foreach ($orders as $order) { ?>
        <tr>
            <td><?php echo $order['order_id']; ?></td>
            <td><img src="uploads/<?php echo $order['product_image']; ?>" alt="Product"></td>
            <td><?php echo htmlspecialchars($order['product_name']); ?></td>
            <td><?php echo $order['user_id']; ?></td>
            <td><?php echo htmlspecialchars($order['buyer_name']); ?></td>
            <td><strong>₹ <?php echo number_format($order['price'], 2); ?></strong></td>
            <td>
                <a href="invoice.php?order_id=<?php echo $order['order_id']; ?>" target="_blank">
                    🖨 Download Invoice
                </a>
            </td>
        </tr>
        <?php } ?>
    </table>
<?php } ?>


</body>
</html>
