<?php
session_start();

if (isset($_GET['index']) && isset($_SESSION['cart'][$_GET['index']])) {
    unset($_SESSION['cart'][$_GET['index']]); // Remove item from cart
}

// Redirect back to cart page
header("Location: cart.php");
exit();
?>
