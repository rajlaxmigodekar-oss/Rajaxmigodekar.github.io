<?php
// Enable error reporting for debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "myproject";

// Establish database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: Please try again later.");
}

$popupMessage = "";

// Check if product ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid product ID.");
}
$product_id = $_GET['id'];

// Fetch product details
$product_sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($product_sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    die("Product not found.");
}

// Fetch categories
$category_sql = "SELECT id, category_name FROM categories";
$category_result = $conn->query($category_sql);

// Handle update request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_id = $_POST["category_id"];
    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);
    $price = $_POST["price"];
    $material = trim($_POST["material"]);
    $size = trim($_POST["size"]);
     $stock = $_POST['stock']; 

    // Handle image upload
    $image = $product['image']; // Keep the existing image if no new image is uploaded
    if (!empty($_FILES["image"]["name"])) {
        $targetDir = "uploads/";
        $image = basename($_FILES["image"]["name"]);
        $targetFilePath = $targetDir . $image;
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
            $popupMessage = "Error uploading image.";
            $image = $product['image']; // Prevent overwriting with empty value
        }
    }

    if (!empty($name) && !empty($category_id) && !empty($price) && !empty($size) && isset($stock)){
        try {
            $update_sql = "UPDATE products SET category_id=?, name=?, description=?, price=?, material=?, size=?, image=?,stock=? WHERE id=?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("issdsssii", $category_id, $name, $description, $price, $material, $size, $image,$stock,$product_id);
            if ($stmt->execute()) {
                header("Location:add_product.php?message=updated");
                exit();
            } else {
                $popupMessage = "Error updating product.";
            }
        } catch (mysqli_sql_exception $e) {
            $popupMessage = "Error updating product. Please try again.";
        }
    } else {
        $popupMessage = "All required fields must be filled!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
   <?php include 'adminnav.php'; ?>

    <div class="container">
        <h2 class="text-center mb-4">Edit Product</h2>
        <div class="card p-3 mb-4">
            <form method="POST" action="" enctype="multipart/form-data">
                <label class="form-label">Select Category:</label>
                <select class="form-control mb-2" name="category_id" required>
                    <?php while ($row = $category_result->fetch_assoc()) { ?>
                        <option value="<?= htmlspecialchars($row['id']) ?>" <?= ($product['category_id'] == $row['id']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($row['category_name']) ?>
                        </option>
                    <?php } ?>
                </select>
                <input class="form-control mb-2" type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
                <textarea class="form-control mb-2" name="description" required><?= htmlspecialchars($product['description']) ?></textarea>
                <input class="form-control mb-2" type="number" name="price" step="0.01" value="<?= $product['price'] ?>" required>
                <input class="form-control mb-2" type="text" name="material" value="<?= htmlspecialchars($product['material']) ?>">
                <input class="form-control mb-2" type="text" name="size" value="<?= htmlspecialchars($product['size']) ?>" required>
                <label>Current Image:</label>
                <br>
                <img src="uploads/<?= htmlspecialchars($product['image']) ?>" width="100" alt="Product Image">
                <input class="form-control mt-2" type="file" name="image" accept="image/*">

                <input class="form-control mb-2" type="number" name="stock" value="<?= htmlspecialchars($product['stock']) ?>" required>

                <button class="btn btn-primary mt-3" type="submit">Update Product</button>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <?php if (!empty($popupMessage)) { ?>
        <script>
            alert("<?= htmlspecialchars($popupMessage); ?>");
        </script>
    <?php } ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>  
