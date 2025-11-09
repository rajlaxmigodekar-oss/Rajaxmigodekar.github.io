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

// Total Orders Summary
$sql = "SELECT order_status, COUNT(id) AS total_orders, COALESCE(SUM(total_price), 0) AS total_sales FROM orders GROUP BY order_status";
$result = mysqli_query($conn, $sql);

$orders = [
    'pending' => 0,
    'completed' => 0,
    'canceled' => 0
];

$total_sales = 0;

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $orders[$row['order_status']] = $row['total_orders'] ?? 0;
        $total_sales += $row['total_sales'];
    }
} else {
    die("Query Error: " . mysqli_error($conn)); // Debugging help
}

// Top 5 Selling Cities
$sql_cities = "SELECT city, COUNT(id) AS total_orders FROM orders WHERE city IS NOT NULL GROUP BY city ORDER BY total_orders DESC LIMIT 5";
$result_cities = mysqli_query($conn, $sql_cities);
if (!$result_cities) {
    die("City Query Failed: " . mysqli_error($conn));
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>📊 Order Reports</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            margin-left: 200px;
            padding: 20px;
        }
        h3 {
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
        }
        .table-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 30px;
        }
        table {
            width: 45%;
            border-collapse: collapse;
            background: white;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            transition: transform 0.2s;
        }
        table:hover {
            transform: scale(1.02);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }
        th {
            background: #007bff;
            color: white;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
            transition: 0.3s;
        }
        .total-sales {
            font-size: 18px;
            font-weight: bold;
            background-color: #ffc107;
        }
        .status {
            font-weight: bold;
        }
        .pending { color: orange; }
        .completed { color: green; }
        .failed { color: red; }

        /* Responsive */
        @media (max-width: 768px) {
            .table-container {
                flex-direction: column;
                align-items: center;
            }
            table {
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <?php include 'adminnav.php'; ?>

    <div class="container">
        <h3>📊 Total Orders Summary</h3>
        <div class="table-container">
            <table>
                <tr><th>Status</th><th>Total Orders</th></tr>
                <tr><td class="status pending">🟡 Pending</td><td><?php echo $orders['pending']; ?></td></tr>
                <tr><td class="status completed">🟢 Completed</td><td><?php echo $orders['delivered']; ?></td></tr>
                <tr><td class="status failed">🔴 Failed</td><td><?php echo $orders['canceled']; ?></td></tr>
                <tr><th colspan="2" class="total-sales">💰 Total Sales: ₹<?php echo number_format($total_sales, 2); ?></th></tr>
            </table>
        </div>

        <h3>🏙 Top 5 Selling Cities</h3>
        <div class="table-container">
            <table>
                <tr><th>City</th><th>Total Orders</th></tr>
                <?php while ($row = mysqli_fetch_assoc($result_cities)) { ?>
                    <tr><td><?php echo htmlspecialchars($row['city']); ?></td><td><?php echo $row['total_orders']; ?></td></tr>
                <?php } ?>
            </table>
        </div>
    </div>
</body>
</html>
