<?php
session_start();
$host = "localhost";
$username = "root";
$password = "";
$database = "myproject";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<div class='alert alert-danger text-center'>Please login to view your orders.</div>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch orders for the logged-in user
$query = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(45deg, #E6D6F2, #FFB6C1, #B0E2FF); font-family: 'Poppins', sans-serif; }
        .container { margin-top: 50px; }
        .order-card { border-radius: 10px; box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1); background: #fff; padding: 20px; margin-bottom: 20px; }
        .product-img { width: 60px; height: 60px; border-radius: 10px; object-fit: cover; }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        <h2 class="text-center">🛍️ My Orders</h2>

        <div class="row">
            <?php if ($orders->num_rows > 0) { ?>
                <?php while ($order = $orders->fetch_assoc()) { ?>
                    <div class="col-md-6">
                        <div class="card order-card">
                            <div class="card-body">
                                <h5 class="card-title">Order ID: #<?php echo $order['id']; ?></h5>
                                <p><strong>Status:</strong> 
                                    <?php 
                                    if ($order['order_status'] == 'pending') {
                                        echo "<span class='badge bg-warning text-dark'>Pending</span>";
                                    } elseif ($order['order_status'] == 'completed') {
                                        echo "<span class='badge bg-success'>Completed</span>";
                                    } else {
                                        echo "<span class='badge bg-danger'>Processing</span>";
                                    }
                                    ?>
                                </p>
                                <p><strong>Total Price:</strong> ₹<?php echo number_format($order['total_price'], 2); ?></p>
                                <p><strong>Order Date:</strong> <?php echo $order['created_at']; ?></p>

                                <h6>📍 Shipping Details:</h6>
                                <p><strong>Name:</strong> <?php echo htmlspecialchars($order['full_name']); ?></p>
                                <p><strong>Mobile:</strong> <?php echo htmlspecialchars($order['mobile']); ?></p>
                                <p><strong>Address:</strong> <?php echo htmlspecialchars($order['address']); ?>, <?php echo htmlspecialchars($order['city']); ?>, <?php echo htmlspecialchars($order['state']); ?> - <?php echo htmlspecialchars($order['pincode']); ?></p>

                                <h6>🛒 Ordered Products:</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Product</th>
                                                <th>Name</th>
                                                <th>Qty</th>
                                                <th>Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Fetch ordered products for this order
                                            $product_query = "SELECT oi.quantity, oi.price, p.name, p.image 
                                                              FROM order_items oi 
                                                              JOIN products p ON oi.product_id = p.id 
                                                              WHERE oi.order_id = ?";
                                            $product_stmt = $conn->prepare($product_query);
                                            $product_stmt->bind_param("i", $order['id']);
                                            $product_stmt->execute();
                                            $products = $product_stmt->get_result();

                                            if ($products->num_rows > 0) {
                                                while ($product = $products->fetch_assoc()) {
                                            ?>
                                                    <tr>
                                                        <td>
                                                            <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" class="product-img" alt="Product Image">
                                                        </td>
                                                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                                                        <td><?php echo $product['quantity']; ?></td>
                                                        <td>₹<?php echo number_format($product['price'], 2); ?></td>
                                                    </tr>
                                            <?php 
                                                } 
                                            } else {
                                                echo "<tr><td colspan='4' class='text-center'>No products found for this order.</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <a href="index.php" class="btn btn-primary mt-2">🔙 Continue Shopping</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">No orders found.</div>
                </div>
            <?php } ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>

<?php 
$stmt->close();
$conn->close();
?>
