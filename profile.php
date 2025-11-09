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

// Fetch user details
$sql = "SELECT username, email, profile_image, status, created_at FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

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

// Default values if no order is found
$full_name = $order ? htmlspecialchars($order['full_name']) : "N/A";
$mobile = $order ? htmlspecialchars($order['mobile']) : "N/A";
$address = $order ? htmlspecialchars($order['address']) : "N/A";
$city = $order ? htmlspecialchars($order['city']) : "N/A";
$pincode = $order ? htmlspecialchars($order['pincode']) : "N/A";

// Default profile picture if none is uploaded
$profile_image = !empty($user['profile_image']) ? htmlspecialchars($user['profile_image']) : 'uploads/default-avatar.png';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{
            
            
            background: linear-gradient(45deg, #E6D6F2, #FFB6C1, #B0E2FF);

        }
        .profile-container {
            text-align: center;
            margin-top: 20px;
        }
        .profile-picture {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #007bff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .info-box {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            margin-top: 20px;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container mt-5">
        <div class="profile-container">
            <img src="<?= $profile_image ?>?t=<?= time(); ?>" alt="Profile Picture" class="profile-picture">
            <h3>👤 Welcome, <?= htmlspecialchars($user['username']); ?>!</h3>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($user['status']); ?></p>
            <p><strong>Joined:</strong> <?= date("d M Y", strtotime($user['created_at'])); ?></p>
            <a href="edit_profile.php" class="btn btn-primary">Edit Profile</a>
        </div>

        <div class="info-box">
            <h4>📍 Latest Order Address</h4>
            <p><strong>Full Name:</strong> <?= $full_name; ?></p>
            <p><strong>Mobile:</strong> <?= $mobile; ?></p>
            <p><strong>Address:</strong> <?= $address; ?>, <?= $city; ?>,  - <?= $pincode; ?></p>
            <a href="edit_address.php" class="btn btn-warning">✏️ Edit Address</a>
        </div>

        
    </div>
</body>
</html>
