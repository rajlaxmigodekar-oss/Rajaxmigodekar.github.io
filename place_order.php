<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "myproject";

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("<div class='error'>User is not logged in.</div>");
}
$user_id = $_SESSION['user_id'];

// Ensure user exists in database
$result = $conn->query("SELECT id FROM users WHERE id = $user_id");
if ($result->num_rows == 0) {
    die("<div class='error'>Error: User ID $user_id does not exist in the users table.</div>");
}

// Check if cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    die("<div class='error'>Your cart is empty.</div>");
}

$total_price = 0;

// Insert order
$conn->query("INSERT INTO orders (user_id, total_price) VALUES ($user_id, 0)");
$order_id = $conn->insert_id;

// Insert order items
foreach ($_SESSION['cart'] as $item) {
    $product_id = $item['id'];
    $quantity = $item['quantity'];
    $price = $item['price'];
    $subtotal = $price * $quantity;
    $total_price += $subtotal;

    $conn->query("INSERT INTO order_items (order_id, product_id, quantity, price) 
                  VALUES ($order_id, $product_id, $quantity, $price)");
}

// Update total price in orders table
$conn->query("UPDATE orders SET total_price = $total_price WHERE id = $order_id");

// Clear cart
unset($_SESSION['cart']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
             background: linear-gradient(45deg, #E6D6F2, #FFB6C1, #B0E2FF);
            text-align: center;
            font-family: Arial, sans-serif;
        }
        .confirmation {
            margin-top: 50px;
            padding: 40px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            animation: fadeIn 1.2s ease-in-out;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        .checkmark {
            font-size: 80px;
            color: #28a745;
            animation: pop 0.6s ease-in-out;
        }
        @keyframes pop {
            0% { transform: scale(0.5); opacity: 0; }
            50% { transform: scale(1.1); opacity: 1; }
            100% { transform: scale(1); }
        }
        .back-home {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 18px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            transition: background 0.3s ease-in-out;
            text-decoration: none;
        }
        .back-home:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="confirmation">
        <div class="checkmark">✔</div>
        <h2 class="text-success">Order Placed Successfully!</h2>
        <p>Thank you for shopping with us. Your order ID is <strong>#<?php echo $order_id; ?></strong>.</p>
        <p>Total Amount: <strong>₹<?php echo number_format($total_price, 2); ?></strong></p>
        <a href="index.php" class="back-home">Go to Home</a>
    </div>
</div>

</body>
</html>
