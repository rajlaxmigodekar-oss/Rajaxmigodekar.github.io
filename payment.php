<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "myproject";
$conn = new mysqli($servername, $username, $password, "", $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get order_id from URL
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if ($order_id > 0) {
    // Fetch order details
    $order_sql = "SELECT orders.*, products.name 
                  FROM orders 
                  JOIN products ON orders.product_id = products.id 
                  WHERE orders.order_id = $order_id";
    
    $order_result = $conn->query($order_sql);

    if ($order_result && $order_result->num_rows > 0) {
        $order = $order_result->fetch_assoc();
    } else {
        die("<h2 style='color:red; text-align:center;'>Order not found.</h2>");
    }
} else {
    die("<h2 style='color:red; text-align:center;'>Invalid request.</h2>");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment for <?php echo htmlspecialchars($order['name']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; font-family: 'Poppins', sans-serif; }
        .payment-card {
            max-width: 600px; margin: 50px auto; background: #fff; border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15); padding: 20px;
        }
        .btn-custom { background: #007bff; color: #fff; padding: 10px 20px; border-radius: 30px; }
        .btn-custom:hover { background: #0056b3; }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container">
    <div class="payment-card">
        <h3 class="text-center">Payment for <?php echo htmlspecialchars($order['name']); ?></h3>
        <p><strong>Quantity:</strong> <?php echo $order['quantity']; ?></p>
        <p><strong>Total Price:</strong> ₹<?php echo number_format($order['total_price'], 2); ?></p>

        <form action="process_payment.php" method="POST">
            <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">

            <label for="payment_method"><strong>Select Payment Method:</strong></label>
            <select name="payment_method" class="form-control" required>
                <option value="credit_card">Credit Card</option>
                <option value="debit_card">Debit Card</option>
                <option value="net_banking">Net Banking</option>
                <option value="upi">UPI</option>
                <option value="cash_on_delivery">Cash on Delivery</option>
            </select>
            <br>

            <button type="submit" class="btn btn-custom w-100">Proceed to Payment</button>
        </form>
    </div>
</div>

</body>
</html>
