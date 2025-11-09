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

// Handle delete product
if (isset($_GET["delete"])) {
    $product_id = $_GET["delete"];
    $delete_sql = "DELETE FROM products WHERE id=?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $product_id);
    if ($stmt->execute()) {
        $popupMessage = "Product deleted successfully!";
    } else {
        $popupMessage = "Error deleting product.";
    }
}

// Handle product insertion and update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_id = $_POST["category_id"];
    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);
    $price = $_POST["price"];
    $material = trim($_POST["material"]);
    $size = trim($_POST["size"]);
    $stock = $_POST['stock']; // ✅ Stock input corrected
    $product_id = isset($_POST["product_id"]) ? $_POST["product_id"] : null;
    $image = "";

    // Handle file upload
    if (!empty($_FILES["image"]["name"])) {
        $targetDir = "uploads/";
        $image = basename($_FILES["image"]["name"]);
        $targetFilePath = $targetDir . $image;
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
            $popupMessage = "Error uploading image.";
        }
    }

    if (!empty($name) && !empty($category_id) && !empty($price) && !empty($size) && isset($stock)) {
        if ($product_id) {
            // Get the existing image
            $existing_img_sql = "SELECT image FROM products WHERE id=?";
            $stmt = $conn->prepare($existing_img_sql);
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $stmt->bind_result($existing_image);
            $stmt->fetch();
            $stmt->close();

            // If no new image uploaded, keep the existing one
            if (empty($image)) {
                $image = $existing_image;
            }

            // Update product
            $update_sql = "UPDATE products SET category_id=?, name=?, description=?, price=?, material=?, size=?, image=?, stock=? WHERE id=?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("issdsssii", $category_id, $name, $description, $price, $material, $size, $image, $stock, $product_id);
            $stmt->execute();
            $popupMessage = "Product updated successfully!";
        } else {
            // Insert new product
            $insert_sql = "INSERT INTO products (category_id, name, description, price, material, size, image, stock, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param("issdsssi", $category_id, $name, $description, $price, $material, $size, $image, $stock);
            $stmt->execute();
        }
    } else {
        $popupMessage = "All required fields must be filled!";
    }
}

// Reduce stock when order is placed
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_order'])) {
    $order_id = $_POST['order_id'];
    
    // Fetch ordered items
    $sql_order_items = "SELECT product_id, quantity FROM order_items WHERE order_id = ?";
    $stmt = $conn->prepare($sql_order_items);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $product_id = $row['product_id'];
        $quantity = $row['quantity'];

      
    }
    $stmt->close();
}

// Fetch categories
$category_sql = "SELECT id, category_name FROM categories";
$category_result = $conn->query($category_sql);

// Fetch products with category names
$product_sql = "SELECT p.*, c.category_name FROM products p JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC";
$product_result = $conn->query($product_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<style type="text/css">
    .container{
        margin-left:300px ;
    }
</style>
<body>
   <?php include 'anav.php';?>

    <div class="container">
        <h2 class="text-center mb-4">Manage Products</h2>

        <?php if (!empty($popupMessage)) : ?>
            <div class="alert alert-info"><?= htmlspecialchars($popupMessage) ?></div>
        <?php endif; ?>

       <div class="card shadow-lg p-4 mb-5 bg-white rounded">
    <h3 class="text-center text-primary fw-bold mb-3">🛍️ Add / Edit Product</h3>
    <form method="POST" action="" enctype="multipart/form-data">
        <input type="hidden" name="product_id" id="product_id">

        <div class="mb-3">
            <label class="form-label fw-semibold">Select Category:</label>
            <select class="form-select" name="category_id" id="category_id" required>
                <option value="">-- Choose Category --</option>
                <?php while ($row = $category_result->fetch_assoc()) { ?>
                    <option value="<?= htmlspecialchars($row['id']) ?>"><?= htmlspecialchars($row['category_name']) ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Product Name:</label>
            <input class="form-control" type="text" name="name" id="name" placeholder="Enter product name" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Description:</label>
            <textarea class="form-control" name="description" id="description" placeholder="Enter product description"></textarea>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Price (₹):</label>
                <input class="form-control" type="number" name="price" id="price" step="0.01" placeholder="Enter price" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Size (in inches):</label>
                <input class="form-control" type="text" name="size" id="size" placeholder="E.g. 12 inches" required>

            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Material (Optional):</label>
            <input class="form-control" type="text" name="stock" id="material" placeholder="Enter material">
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Product Image:</label>
            <input class="form-control" type="file" name="image" id="image" accept="image/*" onchange="previewImage(event)">
            <img id="imagePreview" class="mt-2 rounded shadow" src="#" alt="Preview" style="display: none; max-width: 150px;">
        </div>
         <div class="mb-3">
    <label class="form-label fw-semibold">Stock</label>
    <input class="form-control" type="number" name="stock" id="stock" placeholder="Enter stock" min="0" required>
</div>


        <div class="d-grid">
            <button class="btn btn-success fw-bold btn-lg shadow-sm" type="submit">💾 Save Product</button>
        </div>
    </form>
</div>
 <h4 class="mb-3">Existing Products</h4>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Size</th>
                <th>Material</th>
                     <th>Stock</th>
                
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $product_result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><img src="uploads/<?= htmlspecialchars($row['image'] ?: 'default.png'); ?>" width="50"></td>
                        <td><?= htmlspecialchars($row['name']); ?></td>
                        <td><?= htmlspecialchars($row['category_name']); ?></td>
                        <td>₹<?= number_format($row['price'], 2); ?></td>
                        <td><?= htmlspecialchars($row['size']); ?></td>
                        <td><?= htmlspecialchars($row['material']); ?></td>
                        <td><?= htmlspecialchars($row['stock']); ?></td> 
                        


                        <td>
                            <a href="edit_product.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">✏️ Edit</a>


                            <a href="?delete=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</body>

</html>
<?php $conn->close(); ?>
