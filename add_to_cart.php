<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "myproject";

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname); // ✅ FIXED CONNECTION

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if product_id and quantity are set
if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    // Fetch product details safely using prepared statements
    $stmt = $conn->prepare("SELECT id, name, price, image FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();

        // Create cart if it doesn't exist
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Check if product is already in the cart
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $product_id) {
                $item['quantity'] += $quantity; // Increase quantity
                $found = true;
                break;
            }
        }

        // If not found, add new product to cart
        if (!$found) {
            $_SESSION['cart'][] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => $quantity
            ];
        }

        header("Location: cart.php");
        exit();
    } else {
        echo "❌ Product not found.";
    }
} else {
    echo "❌ Invalid request.";
}
?>
