<?php
// Enable error reporting for debugging (remove in production)
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

// Initialize popup message
$popupMessage = "";

// Handle category insertion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_category"])) {
    $category_name = trim($_POST["category_name"]);
    
    if (!empty($category_name)) {
        $insert_sql = "INSERT INTO categories (category_name) VALUES (?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("s", $category_name);
        $stmt->execute();
        $popupMessage = "Category added successfully!";
    } else {
        $popupMessage = "Category name is required!";
    }
}

// Handle category deletion
if (isset($_GET["delete_id"])) {
    $delete_id = intval($_GET["delete_id"]);
    $delete_sql = "DELETE FROM categories WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $popupMessage = "Category deleted successfully!";
    } else {
        $popupMessage = "Error deleting category.";
    }
}

// Fetch categories
$category_sql = "SELECT * FROM categories";
$category_result = $conn->query($category_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <style>
        body {
            background: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
        }
        .category-list li {
            background: #e9ecef;
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .delete-btn {
            color: red;
            text-decoration: none;
            font-weight: bold;
        }
        .delete-btn:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<?php include 'anav.php';?>
    <div class="container">
        <h2>Manage Categories</h2>

        <!-- Category Form -->
        <form method="POST" action="">
            <input type="text" name="category_name" class="form-control" placeholder="Category Name" required>
            <button type="submit" name="add_category" class="btn btn-primary mt-2">Add Category</button>
        </form>

        <!-- Existing Categories -->
        <h2 class="mt-4">Existing Categories</h2>
        <ul class="list-group category-list">
            <?php while ($row = $category_result->fetch_assoc()) { ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?= htmlspecialchars($row['category_name']); ?>
                    <a href="?delete_id=<?= $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
                </li>
            <?php } ?>
        </ul>
    </div>

</body>
</html>
<?php $conn->close(); ?>