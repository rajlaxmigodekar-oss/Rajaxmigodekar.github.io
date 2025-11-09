<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "myproject";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch customer details with order summary
$sql = "SELECT u.id, u.username, u.email, u.mobile_no, 
        COUNT(o.id) AS total_orders, 
        COALESCE(SUM(o.total_price), 0) AS total_spent, 
        MAX(o.created_at) AS last_purchase 
        FROM users u 
        LEFT JOIN orders o ON u.id = o.user_id 
        GROUP BY u.id";

$result = $conn->query($sql);

// Check for errors
if (!$result) {
    die("Query Failed: " . $conn->error);
}

$customers = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Report</title>
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
    <h2>📊 Customer Reports</h2>
    <table>
        <tr>
            <th>🆔 ID</th>
            <th>👤 Name</th>
            <th>📧 Email</th>
            <th>📞 Mobile</th>
            <th>🛍 Total Orders</th>
            <th>💰 Amount Spent (₹)</th>
            <th>📅 Last Purchase</th>
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
                <td><strong>₹<?php echo number_format($customer['total_spent'], 2); ?></strong></td>
                <td><?php echo $customer['last_purchase'] ? date('Y-m-d', strtotime($customer['last_purchase'])) : 'No Orders'; ?></td>
            </tr>
            <?php } ?>
        <?php } else { ?>
            <tr><td colspan="7" class="no-data">No customer records found</td></tr>
        <?php } ?>
    </table>
</body>
</html>
