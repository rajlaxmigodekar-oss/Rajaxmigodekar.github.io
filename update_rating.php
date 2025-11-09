<?php
// update_rating.php

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "myproject";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['product_id'], $_POST['rating'])) {
    $product_id = intval($_POST['product_id']);
    $rating = floatval($_POST['rating']);  // किंवा integer म्हणून देखील

    // अपडेट क्वेरी: येथे assume केले आहे की products टेबलमध्ये 'rating' कॉलम आहे
    $update_sql = "UPDATE products SET rating = $rating WHERE id = $product_id";
    if ($conn->query($update_sql) === TRUE) {
        // यशस्वी अपडेट झाल्यावर प्रॉडक्ट डिटेल पेजवर redirect करा
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    } else {
        echo "Error updating rating: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}
?>
