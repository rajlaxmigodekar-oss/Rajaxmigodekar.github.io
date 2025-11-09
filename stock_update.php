<?php
session_start();

// 🔹 Database Connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "myproject";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ✅ Handle Stock Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'], $_POST['stock'])) {
    $product_id = intval($_POST['product_id']);
    $new_stock = intval($_POST['stock']);

    if ($new_stock < 0) {
        echo "<script>alert('Stock cannot be negative!'); window.location.href='update_stock.php';</script>";
        exit;
    }

    // 🔹 Secure Query
    $stmt = $conn->prepare("UPDATE products SET stock = ? WHERE id = ?");
    $stmt->bind_param("ii", $new_stock, $product_id);
    
    if ($stmt->execute()) {
        echo "<script>
                alert('Stock updated successfully!');
                window.location.href = window.location.href;
              </script>";
    } else {
        echo "<script>alert('Error updating stock!');</script>";
    }
    $stmt->close();
}

// 🔹 Fetch Products
$product_sql = "SELECT id, name, stock FROM products";
$product_result = $conn->query($product_sql);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Stock (Admin Only)</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">📦 Update Product Stock (Admin Only)</h2>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Product Name</th>
                    <th>Current Stock</th>
                    <th>Update Stock</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $product_result->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['stock']) ?></td>
                    <td>
                        <form method="POST" action="" onsubmit="return confirmUpdate();">
                            <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                            <input type="number" name="stock" min="0" class="form-control" required value="<?= htmlspecialchars($row['stock']) ?>">
                            <button type="submit" class="btn btn-primary mt-2">Update</button>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script>
        function confirmUpdate() {
            return confirm("Are you sure you want to update the stock?");
        }
    </script>
</body>
</html>
