<?php
// Database Connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "myproject";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch Customer Interests
$sql = "SELECT 
            u.id, 
            u.username, 
            u.email, 
            u.mobile_no, 
            COUNT(o.id) AS total_orders, 
            cat.category_name AS favorite_category, 
            (SELECT p.name 
             FROM order_items oi 
             JOIN products p ON oi.product_id = p.id 
             WHERE oi.order_id = o.id 
             GROUP BY p.name 
             ORDER BY COUNT(p.name) DESC 
             LIMIT 1) AS most_purchased_product
        FROM users u
        INNER JOIN orders o ON u.id = o.user_id
        INNER JOIN order_items oi ON o.id = oi.order_id
        INNER JOIN products p ON oi.product_id = p.id
        INNER JOIN categories cat ON p.category_id = cat.id
        GROUP BY u.id
        HAVING total_orders > 0";



$result = $conn->query($sql);
$customers = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Interest Report</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f4; }
        h2 { text-align: center; color: #007bff; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        th { background-color: #007bff; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .status-green { color: green; font-weight: bold; }
        .status-red { color: red; font-weight: bold; }
        .no-data { text-align: center; font-weight: bold; color: red; }
    </style>
</head>
<body>
      <?php include 'adminnav.php';?>
    <h2>📊 Customer Interest Report</h2>
    <table>
        <tr>
            <th>🆔 ID</th>
            <th>👤 Name</th>
            <th>📧 Email</th>
            <th>📞 Mobile</th>
            <th>🛒 Total Orders</th>
            <th>📂 Favorite Category</th>
            <th>🏆 Most Purchased Product</th>
        </tr>
        <?php if (count($customers) > 0) { ?>
            <?php foreach ($customers as $customer) { ?>
            <tr>
                <td><?php echo $customer['id']; ?></td>
                <td><?php echo htmlspecialchars($customer['username']); ?></td>
                <td><?php echo htmlspecialchars($customer['email']); ?></td>
                <td><?php echo htmlspecialchars($customer['mobile_no'] ?? 'N/A'); ?></td>
                <td class="<?php echo ($customer['total_orders'] > 5) ? 'status-green' : 'status-red'; ?>">
                    <?php echo $customer['total_orders']; ?>
                </td>
                <td><?php echo htmlspecialchars($customer['favorite_category'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($customer['most_purchased_product'] ?? 'N/A'); ?></td>
            </tr>
            <?php } ?>
        <?php } else { ?>
            <tr><td colspan="7" class="no-data">No customer records found</td></tr>
        <?php } ?>
    </table>
</body>
</html>
