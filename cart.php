<?php
session_start();

// Handle cart update when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["index"]) && isset($_POST["quantity"])) {
    $index = $_POST["index"];
    $new_quantity = intval($_POST["quantity"]);

    if ($new_quantity > 0 && isset($_SESSION['cart'][$index])) {
        $_SESSION['cart'][$index]['quantity'] = $new_quantity;
        $_SESSION['message'] = "Cart updated successfully! ✅";
    }

    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
             background: linear-gradient(45deg, #E6D6F2, #FFB6C1, #B0E2FF);
        }
        .cart-container {
            max-width: 900px;
            margin-top: 150px;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .table img {
            border-radius: 5px;
        }
        .btn-primary, .btn-danger, .btn-secondary {
            border-radius: 20px;
            padding: 8px 16px;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container cart-container">
        <h2 class="text-center my-4">🛒 Your Shopping Cart</h2>

        <?php if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) { ?>
            <div class="alert alert-warning text-center">Your cart is empty.</div>
        <?php } else { ?>
            <?php if (isset($_SESSION['message'])) { ?>
                <div class="alert alert-success text-center">
                    <?php 
                    echo $_SESSION['message']; 
                    unset($_SESSION['message']); 
                    ?>
                </div>
            <?php } ?>

            <table class="table table-hover table-bordered text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total = 0;
                    foreach ($_SESSION['cart'] as $index => $item) {
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                        $imgSrc = !empty($item['image']) ? 'uploads/' . htmlspecialchars($item['image']) : 'uploads/default-placeholder.png';
                    ?>
                        <tr>
                            <td><img src="<?php echo $imgSrc; ?>" width="60" alt="Product Image"></td>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td>₹<?php echo number_format($item['price'], 2); ?></td>
                            <td>
                                <form method="post" action="" class="d-flex justify-content-center align-items-center">
                                    <input type="hidden" name="index" value="<?php echo $index; ?>">
                                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" class="form-control w-50 me-2">
                                    <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                </form>
                            </td>
                            <td>₹<?php echo number_format($subtotal, 2); ?></td>
                            <td>
                                <a href="remove_from_cart.php?index=<?php echo $index; ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Remove this item from the cart?');">Remove</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <h3 class="text-end">Total: ₹<?php echo number_format($total, 2); ?></h3>
            <div class="text-center mt-4">
                <a href="checkout.php" class="btn btn-success me-2">Proceed to Checkout</a>
                <a href="Products.php" class="btn btn-secondary">Continue Shopping</a>
            </div>
        <?php } ?>
    </div>
    <br/><br/>
    <br/><br/><br/>
    <br/>
    <?php include 'footer.php';?>
</body>
</html>