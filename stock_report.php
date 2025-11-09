<?php
session_start();
$conn = new mysqli("localhost", "root", "", "myproject");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch stock reports
$low_stock = $conn->query("SELECT * FROM products WHERE stock > 0 AND stock <= 10");
$out_of_stock = $conn->query("SELECT * FROM products WHERE stock = 0");
$all_stock = $conn->query("SELECT * FROM products");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Reports</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 50%; border-collapse: collapse; margin-top: 20px;  margin-left: 500px;}
       h2, h3{ margin-left: 500px;}
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background: #333; color: white; }
        .low-stock { background: orange; color: black; }
        .out-stock { background: red; color: white; }
    </style>
</head>
<body>
  <?php include 'adminnav.php';?>
<h2>Stock Reports</h2>

<!-- Low Stock Products -->
<h3>⚠️ Low Stock Products (Less than 10)</h3>
<table>
    <tr><th>ID</th><th>Name</th><th>Stock</th></tr>
    <?php while ($row = $low_stock->fetch_assoc()) { ?>
        <tr class="low-stock">
            <td><?= $row["id"] ?></td>
            <td><?= htmlspecialchars($row["name"]) ?></td>
            <td><?= $row["stock"] ?></td>
        </tr>
    <?php } ?>
</table>

<!-- Out of Stock Products -->
<h3>❌ Out of Stock Products</h3>
<table>
    <tr><th>ID</th><th>Name</th><th>Stock</th></tr>
    <?php while ($row = $out_of_stock->fetch_assoc()) { ?>
        <tr class="out-stock">
            <td><?= $row["id"] ?></td>
            <td><?= htmlspecialchars($row["name"]) ?></td>
            <td>0</td>
        </tr>
    <?php } ?>
</table>

<!-- Total Stock Summary -->
<h3>📦 Total Stock Summary</h3>
<table>
    <tr><th>ID</th><th>Name</th><th>Stock</th></tr>
    <?php while ($row = $all_stock->fetch_assoc()) { ?>
        <tr>
            <td><?= $row["id"] ?></td>
            <td><?= htmlspecialchars($row["name"]) ?></td>
            <td><?= $row["stock"] ?></td>
        </tr>
    <?php } ?>
</table>

</body>
</html>
