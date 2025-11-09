<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "myproject";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch Categories with Products
$category_sql = "SELECT c.id AS category_id, c.category_name, 
                 p.id AS product_id, p.name AS product_name, p.image, p.price
                 FROM categories c
                 LEFT JOIN products p ON c.id = p.category_id
                 ORDER BY c.id, p.id DESC";
$category_result = $conn->query($category_sql);

// Organizing Data in a Structured Array
$categories = [];
while ($row = $category_result->fetch_assoc()) {
    $categories[$row['category_id']]['name'] = $row['category_name'];
    if ($row['product_id']) {
        $categories[$row['category_id']]['products'][] = [
            'id' => $row['product_id'],
            'name' => $row['product_name'],
            'image' => $row['image'],
            'price' => $row['price']
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories & Products</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .category-card {
            border: none;
            background: white;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin-bottom: 30px;
            padding: 15px;
        }
        .category-header {
            background: linear-gradient(to right, #007bff, #00c6ff);
            color: white;
            padding: 15px;
            border-radius: 10px 10px 0 0;
            font-weight: bold;
            text-align: center;
        }
        .product-card {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s;
            background: white;
        }
        .product-card:hover {
            transform: scale(1.05);
        }
        .product-card img {
            height: 220px;
            object-fit: cover;
        }
        .product-info {
            padding: 15px;
            text-align: center;
        }
        .product-title {
            font-size: 18px;
            font-weight: bold;
            color: #343a40;
        }
        .product-price {
            font-size: 16px;
            color: #28a745;
            font-weight: bold;
        }
        .btn-view {
            background: #28a745;
            color: white;
            font-weight: bold;
            width: 100%;
            border-radius: 5px;
        }
        .btn-view:hover {
            background: #218838;
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <h2 class="text-center text-primary mb-4 fw-bold">🛍️ Browse by Categories</h2>

    <?php foreach ($categories as $category_id => $category) { ?>
        <div class="category-card p-3">
            <div class="category-header"><?= htmlspecialchars($category['name']); ?></div>
            <div class="row mt-3">
                <?php if (!empty($category['products'])) { ?>
                    <?php foreach ($category['products'] as $product) { ?>
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                            <div class="card product-card">
                                <img src="uploads/<?= htmlspecialchars($product['image'] ?: 'default.png'); ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']); ?>">
                                <div class="product-info">
                                    <h5 class="product-title"><?= htmlspecialchars($product['name']); ?></h5>
                                    <p class="product-price">₹<?= number_format($product['price'], 2); ?></p>
                                    <a href="product_detail.php?id=<?= $product['id']; ?>" class="btn btn-view">View Product</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <p class="text-muted text-center">No products available in this category.</p>
                <?php } ?>
            </div>
        </div>
    <?php } ?>

</div>

</body>
</html>

<?php $conn->close(); ?>
