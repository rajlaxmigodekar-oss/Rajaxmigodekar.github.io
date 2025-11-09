<?php
session_start();
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

include 'db.php'; // Include database connection

$user_id = $_SESSION['user_id'] ?? null;
$latest_address = null;

if ($user_id) {
    $stmt = $conn->prepare("SELECT full_name, mobile, address, city, pincode FROM orders WHERE user_id = ? ORDER BY id DESC LIMIT 1");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $latest_address = $result->fetch_assoc();
    }
    $stmt->close();
}

$order_id = $_POST['order_id'] ?? uniqid("ORD_");
$_SESSION['order_id'] = $order_id;

// If the user has a saved address, auto-submit the form
if ($latest_address) {
    $_SESSION['latest_address'] = $latest_address;
    header("Location: confirm_order.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Details | Omkara Murti's</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
         body{background: linear-gradient(45deg, #E6D6F2, #FFB6C1, #B0E2FF);}
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container mt-5">
    <h2 class="text-center text-primary fw-bold">📦 Enter Your Shipping Details</h2>

    <div class="card p-4 mt-3">
        <h4 class="mb-3">🛍 Your Order</h4>
        <?php foreach ($_SESSION['cart'] as $item) { ?>
            <div class="product-item">
                <img src="uploads/<?php echo htmlspecialchars($item['image']); ?>" alt="Product">
                <div>
                    <h6 class="product-name"><?php echo htmlspecialchars($item['name']); ?></h6>
                    <p class="mb-0">₹<?php echo number_format($item['price'], 2); ?> x <?php echo $item['quantity']; ?></p>
                </div>
                <div class="ms-auto product-price">
                    ₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                </div>
            </div>
        <?php } ?>
        <h4 class="order-total mt-3">Total: ₹<?php echo number_format(array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $_SESSION['cart'])), 2); ?></h4>
    </div>

    <form action="confirm_order.php" method="POST" class="mt-4">
        <div class="mb-3">
            <input type="text" name="full_name" class="form-control" placeholder="Full Name" required>
        </div>
        <div class="mb-3">
            <input type="text" name="mobile" class="form-control" placeholder="Mobile Number" required pattern="[0-9]{10}" title="Enter a valid 10-digit number">
        </div>
        <div class="mb-3">
            <textarea name="address" class="form-control" rows="3" placeholder="Address" required></textarea>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <input type="text" name="city" class="form-control" placeholder="City" required>
            </div>
        </div>
        <div class="mb-3">
            <input type="text" name="pincode" class="form-control" placeholder="Pincode" required pattern="[0-9]{6}" title="Enter a valid 6-digit pincode">
        </div>
        
        <div class="cod-box mt-3">
            <h5>💰 Cash on Delivery (COD) Only</h5>
            <p>No online payment required. Pay in cash upon delivery.</p>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Confirm Order</button>
    </form>

    <div class="text-center mt-3">
        <a href="profile.php" class="btn btn-warning">Update Address</a>
    </div>
</div>

<br/><br/>
<?php include 'footer.php'; ?>
</body>
</html>
