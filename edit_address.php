<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "myproject";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch latest order address
$order_sql = "SELECT full_name, mobile, address, city, pincode 
              FROM orders 
              WHERE user_id = ? 
              ORDER BY created_at DESC LIMIT 1";
$order_stmt = $conn->prepare($order_sql);
$order_stmt->bind_param("i", $user_id);
$order_stmt->execute();
$order_result = $order_stmt->get_result();
$order = $order_result->fetch_assoc();

// Default values if no order found
$full_name = $order ? htmlspecialchars($order['full_name']) : "";
$mobile = $order ? htmlspecialchars($order['mobile']) : "";
$address = $order ? htmlspecialchars($order['address']) : "";
$city = $order ? htmlspecialchars($order['city']) : "";
$pincode = $order ? htmlspecialchars($order['pincode']) : "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Address | Omkara Murtis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
             background: linear-gradient(45deg, #E6D6F2, #FFB6C1, #B0E2FF);
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            max-width: 500px;
            background: rgba(0, 0, 0, 0.6);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
            color: #fff;
        }
        input, textarea {
            background: rgba(255, 255, 255, 0.2) !important;
            border: none !important;
            color: #fff !important;
        }
        input::placeholder, textarea::placeholder {
            color: rgba(255, 255, 255, 0.7) !important;
        }
        .btn-primary {
            background: #ff4444;
            border: none;
            width: 100%;
            padding: 10px;
        }
        .btn-primary:hover {
            background: #cc0000;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4">Edit Address</h2>
        <form action="update_address.php" method="POST">
            <div class="mb-3">
                <input type="text" name="full_name" class="form-control" placeholder="Full Name" required value="<?= $full_name; ?>">
            </div>
            <div class="mb-3">
                <input type="text" name="mobile" class="form-control" placeholder="Mobile Number" required pattern="[0-9]{10}" value="<?= $mobile; ?>">
            </div>
            <div class="mb-3">
                <textarea name="address" class="form-control" rows="3" placeholder="Address" required><?= $address; ?></textarea>
            </div>
            <div class="mb-3">
                <input type="text" name="city" class="form-control" placeholder="City" required value="<?= $city; ?>">
            </div>
            
            <div class="mb-3">
                <input type="text" name="pincode" class="form-control" placeholder="Pincode" required pattern="[0-9]{6}" value="<?= $pincode; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Save Address</button>
        </form>
    </div>
</body>
</html>
