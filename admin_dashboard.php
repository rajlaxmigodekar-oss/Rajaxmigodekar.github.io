<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Database Connection
$conn = new mysqli("localhost", "root", "", "myproject");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch Summary Data
$total_users = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$total_products = $conn->query("SELECT COUNT(*) AS total FROM products")->fetch_assoc()['total'];
$total_orders = $conn->query("SELECT COUNT(*) AS total FROM orders")->fetch_assoc()['total'];
$total_categories = $conn->query("SELECT COUNT(*) AS total FROM categories")->fetch_assoc()['total'];

// Fetch Recent Orders
$orders = $conn->query("SELECT orders.id, orders.total_price, COALESCE(orders.order_status, 'pending') AS order_status, orders.created_at, users.username 
                        FROM orders 
                        JOIN users ON orders.user_id = users.id 
                        ORDER BY orders.created_at DESC 
                        LIMIT 5");

// Fetch Recent Payments
$payments = $conn->query("SELECT orders.id AS order_id, orders.total_price AS amount, 
                                 COALESCE(orders.payment_status, 'Pending') AS payment_status, 
                                 orders.created_at AS transaction_date, users.username 
                          FROM orders 
                          JOIN users ON orders.user_id = users.id 
                          WHERE orders.payment_status = 'Paid' 
                          ORDER BY orders.created_at DESC 
                          LIMIT 5");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { display: flex; background-color: #f4f4f9; font-family: 'Poppins', sans-serif; }
        .sidebar { width: 250px; height: 100vh; background: #333; color: #fff; padding: 20px; position: fixed; left: 0; }
        .sidebar .logo { font-size: 22px; font-weight: bold; text-align: center; margin-bottom: 30px; letter-spacing: 1px; }
        .sidebar ul { list-style: none; padding: 0; }
        .sidebar ul li { margin: 15px 0; }
        .sidebar ul li a { color: #fff; text-decoration: none; padding: 10px; display: flex; align-items: center; gap: 10px; border-radius: 4px; transition: 0.3s; }
        .sidebar ul li a:hover { background: #ff416c; }
        .main-content { margin-left: 270px; width: calc(100% - 270px); padding: 30px; }
        .dashboard-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
        .stat-box { background: linear-gradient(45deg, #007bff, #00c6ff); color: white; padding: 20px; border-radius: 10px; text-align: center; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); transition: 0.3s; }
        .stat-box:hover { transform: translateY(-5px); }
        .stat-box i { font-size: 40px; margin-bottom: 10px; }
        .stat-box h3 { font-size: 20px; margin-bottom: 5px; }
        .stat-box p { font-size: 18px; font-weight: bold; }
        .table { background: white; border-radius: 10px; padding: 20px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1); }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="logo">Idol Admin</div>
    <ul>
        <li><a href="admin_dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="add_category.php"><i class="fas fa-list"></i> Manage Categories</a></li>
        <li><a href="add_product.php"><i class="fas fa-box"></i> Manage Products</a></li>
        <li><a href="order_manage.php"><i class="fas fa-shopping-cart"></i> Manage Orders</a></li>
        <li><a href="manage_user.php"><i class="fas fa-users"></i> Manage Users</a></li>
        <li>Reports
                    <div class="dropdown">
                <a href="stock_report.php">Stock Reports</a>
                 <a href="sales_report.php">Sales Reports</a>
                  <a href="orders_report.php">Orders Reports</a>
                   <a href="customer_report.php">Customer Reports</a>
                    <a href="customer_interest_report.php">Customer Interest Reports</a>
                     <a href="payment_report.php">Payment Reports</a>
               </div></li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content">
    <h1>Welcome to the Admin Dashboard</h1>

    <!-- Dashboard Stats -->
    <div class="dashboard-stats">
        <div class="stat-box"><i class="fas fa-box"></i><h3>Total Products</h3><p><?= $total_products ?></p></div>
        <div class="stat-box" style="background: #e74c3c;"><i class="fas fa-shopping-cart"></i><h3>Total Orders</h3><p><?= $total_orders ?></p></div>
        <div class="stat-box" style="background: #ff9f00;"><i class="fas fa-list"></i><h3>Total Categories</h3><p><?= $total_categories ?></p></div>
        <div class="stat-box" style="background: #28a745;"><i class="fas fa-users"></i><h3>Total Users</h3><p><?= $total_users ?></p></div>
    </div>

    <!-- Recent Orders -->
    <h2 class="mt-4">📦 Recent Orders</h2>
    <table class="table table-bordered">
        <thead>
            <tr><th>Order ID</th><th>User</th><th>Total Price</th><th>Status</th><th>Created At</th></tr>
        </thead>
        <tbody>
            <?php while ($order = $orders->fetch_assoc()) { ?>
                <tr>
                    <td><?= $order['id'] ?></td>
                    <td><?= htmlspecialchars($order['username']) ?></td>
                    <td>₹<?= number_format($order['total_price'], 2) ?></td>
                    <td><?= ucfirst(htmlspecialchars($order['order_status'])) ?></td>
                    <td><?= $order['created_at'] ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Recent Payments -->
    <h2 class="mt-4">💳 Recent Payments</h2>
    <table class="table table-bordered">
        <thead>
            <tr><th>Order ID</th><th>User</th><th>Amount</th><th>Payment Status</th><th>Transaction Date</th></tr>
        </thead>
        <tbody>
            <?php while ($payment = $payments->fetch_assoc()) { ?>
                <tr>
                    <td><?= $payment['order_id'] ?></td>
                    <td><?= htmlspecialchars($payment['username']) ?></td>
                    <td>₹<?= number_format($payment['amount'], 2) ?></td>
                    <td><?= ucfirst(htmlspecialchars($payment['payment_status'])) ?></td>
                    <td><?= $payment['transaction_date'] ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
