<?php
session_start();
header('Content-Type: application/json'); // JSON response

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "myproject";

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

if ($product_id > 0) {
    // Check if product is already liked
    $check_sql = "SELECT id FROM user_likes WHERE user_id = ? AND product_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    if ($check_stmt) {
        $check_stmt->bind_param("ii", $user_id, $product_id);
        $check_stmt->execute();
        $check_stmt->store_result();
        
        if ($check_stmt->num_rows > 0) {
            // Product is already liked -> Remove it from watchlist
            $check_stmt->close(); // Close previous statement
            
            $delete_sql = "DELETE FROM user_likes WHERE user_id = ? AND product_id = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            if ($delete_stmt) {
                $delete_stmt->bind_param("ii", $user_id, $product_id);
                if ($delete_stmt->execute()) {
                    echo json_encode(['status' => 'removed', 'message' => 'Removed from Watchlist']);
                } else {
                    echo json_encode(['error' => 'Failed to remove']);
                }
                $delete_stmt->close();
            } else {
                echo json_encode(['error' => 'Prepare statement failed for delete']);
            }
        } else {
            // Product is not liked -> Add to watchlist
            $check_stmt->close(); // Close previous statement

            $insert_sql = "INSERT INTO user_likes (user_id, product_id) VALUES (?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            if ($insert_stmt) {
                $insert_stmt->bind_param("ii", $user_id, $product_id);
                if ($insert_stmt->execute()) {
                    echo json_encode(['status' => 'added', 'message' => 'Added to Watchlist']);
                } else {
                    echo json_encode(['error' => 'Failed to add']);
                }
                $insert_stmt->close();
            } else {
                echo json_encode(['error' => 'Prepare statement failed for insert']);
            }
        }
    } else {
        echo json_encode(['error' => 'Prepare statement failed for check']);
    }
} else {
    echo json_encode(['error' => 'Invalid product ID']);
}

// Close database connection
$conn->close();
?>
