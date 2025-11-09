<?php 
session_start();
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "myproject";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if 'id' is set in GET parameters
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $product_id = intval($_GET['id']);
    $product_sql = "
    SELECT p.*, c.category_name 
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id 
    WHERE p.id = $product_id";

    $product_result = $conn->query($product_sql);

    if ($product_result && $product_result->num_rows > 0) {
        $product = $product_result->fetch_assoc();
    } else {
        die("<h2 style='color:red; text-align:center;'>Product not found.</h2>");
    }
} else {
    die("<h2 style='color:red; text-align:center;'>Invalid product request.</h2>");
}

// Fetch average rating and latest review
$rating_sql = "
    SELECT 
        AVG(rating) AS avg_rating, 
        (SELECT review_text FROM review WHERE product_id = $product_id ORDER BY created_at DESC LIMIT 1) AS latest_review 
    FROM review 
    WHERE product_id = $product_id";

$rating_result = $conn->query($rating_sql);
$avg_rating = 0;
$latest_review = "No reviews yet.";

if ($rating_result && $rating_result->num_rows > 0) {
    $rating_data = $rating_result->fetch_assoc();
    $avg_rating = round($rating_data['avg_rating'], 1);
    $latest_review = !empty($rating_data['latest_review']) ? htmlspecialchars($rating_data['latest_review']) : "No reviews yet.";
}

// Set image source
$imgSrc = !empty($product['image']) ? 'uploads/' . $product['image'] : 'uploads/default-placeholder.png';


// related product section
$category_id = $product['category_id']; // Get the category of the current product
$product_id = $product['id']; // Exclude the current product

$related_sql = "SELECT * FROM products WHERE category_id = ? AND id != ? ORDER BY RAND() LIMIT 4";
$stmt = $conn->prepare($related_sql);
$stmt->bind_param("ii", $category_id, $product_id);
$stmt->execute();
$related_result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Buy Product - <?php echo htmlspecialchars($product['name']); ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <style>
    body { 
    
       background: linear-gradient(45deg, #E6D6F2, #FFB6C1, #B0E2FF);
      font-family: 'Poppins', sans-serif; 
    }
    .buy-card {

      max-width: 1000px; 
      margin: 50px auto; 
      background: #fff; 
      border-radius: 15px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15); 
      overflow: hidden; 
      display: flex;
      flex-wrap: wrap; 
      animation: fadeIn 1s ease-in-out;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(50px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .image-container { 
      flex: 1 1 50%; 
      overflow: hidden; 
      position: relative; 
    }
    .image-container img { 
      width: 100%; 
      transition: transform 0.2s ease-out; 
    }
    .image-container:hover img { 
      transform: scale(1.2); 
    }
    .info-container { 
      flex: 1 1 50%; 
      padding: 50px; 
      font-size: 20px;
    }
    .h3 { 
      font-weight: bold; 
      color: #333; 
    }
    .price { 
      font-size: 1.8rem; 
      color: #28a745; 
      font-weight: bold; 
    }
    .btn-custom { 
      background: #007bff; 
      color: #fff; 
      padding: 12px 50px; 
      border-radius: 30px; 
    }
    .btn-custom:hover { 
      background: #0056b3; 
    }

    .star-rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: start;
        font-size: 1.5rem;
    }
    .star-rating input {
        display: none;
    }
    .star-rating label {
        color: #ddd;
        cursor: pointer;
        transition: color 0.3s ease-in-out;
        padding: 5px;
    }
    .star-rating input:checked ~ label,
    .star-rating label:hover,
    .star-rating label:hover ~ label {
        color: #ffc107;
    }
    .form-control {
        border: 2px solid #ced4da;
        transition: 0.3s;
    }
    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 10px rgba(0, 123, 255, 0.2);
    }
    .p{font-size: 15px}
    .re-products{margin-left: 100px;}



   .product-card {
    width: 100%; /* Ensures it takes the full column width */
    max-width: 500px; /* Increases the card size */
    height: 380px; /* Increases the height */
    transition: transform 0.3s ease-in-out, box-shadow 0

    
    .product-card:hover {
        transform: scale(1.05);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    .product-img {
        height: 400px;
        object-fit: cover;
        border-radius: 8px 8px 0 0;
    }

    .btn-info {
        background-color: #17a2b8;
        border: none;
    }

    .btn-info:hover {
        background-color: #138496;
    }
  </style>
</head>

<body>
  <?php include 'navbar.php'; ?>
         <a href="Products.php" class="btn mt-2" 
   style="margin-left: 500px; margin-top: 100px; background-color: lightskyblue; border-color: #FFB6C1; color: white; padding: 10px 20px; font-size: 17px; text-decoration: none; display: inline-block; text-align: center;">
   🔙 Back to Products
</a>
  <div class="container">
    <div class="buy-card">
      <div class="image-container">
        <img src="<?php echo $imgSrc; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
      </div>
      <div class="info-container">
        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
        <p class="price">₹<?php echo number_format($product['price'], 2); ?></p>
        <!-- Display Product Description from Database -->
        <p><?php echo htmlspecialchars($product['description']); ?></p>
        <!-- Display other product details using $product -->
        <p class="mb-1">
        <strong>Category:</strong><?php echo htmlspecialchars($product['category_name'] ?? 'N/A'); ?>
        </p>
        <p class="mb-1">
    <strong>Size:</strong> <?php echo isset($product['size']) ? htmlspecialchars($product['size'])  : 'N/A'; ?>
</p>

         <p class="mb-1">
          <strong>Material:</strong> <?php echo htmlspecialchars($product['material'] ?? 'N/A'); ?>
        </p>
      <form action="add_to_cart.php" method="post" onsubmit="return validateStock()">
    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">

    <form action="add_to_cart.php" method="post">
    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">

    <label>Quantity:</label>
    <input type="number" id="quantity" name="quantity" value="1" min="1" 
           max="<?php echo $available_stock; ?>" required>
      <!-- Action on the buy button -->
    <?php if (!isset($_SESSION['user_id'])) { ?>
    <!-- Redirect to login if user is not logged in -->
    <a href="login.php" class="btn btn-warning">🛒 Buy Now</a>
<?php } elseif ($product['stock'] > 0) { ?>
    <br/><br/>
    <!-- Proceed to checkout if logged in and stock is available -->
   <button type="submit" class="btn mt-2" 
   style="background-color: black; border-color: #87CEEB; color: white; width: 200px; height: 50px; font-size: 20px; margin-left: 100px;">
   🛒 Buy Now
</button>
<br/><br/>
<?php } else { ?>
    <!-- Show disabled button if out of stock -->
    <button type="button" class="btn btn-danger" disabled>Out of Stock</button>
<?php } ?>

<section>
 <!--stock mange --->
  <?php 
$product_id = $_GET['id'];
$query = "SELECT stock FROM products WHERE id = $product_id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if ($row['stock'] > 0) {
    echo "<p style='color: green;'>Product is available!</p>";
} else {
    echo "<p style='color: red;'><b>Out of stock!</b></p>";
}?>
</section>
<section>
<!-- Display Product Rating -->
<p class="mb-1">
    <strong>Average Rating:</strong>
    <?php 
    for ($i = 1; $i <= 5; $i++) {
        echo ($i <= $avg_rating) ? '<span class="fa fa-star text-warning"></span>' : '<span class="fa fa-star text-muted"></span>';
    }
    ?>
    (<?= $avg_rating; ?>)

</form>
      </div>
    </div>
  </div>

<!-- Review Submission Form -->
<div class="container mt-4">
    <h3>Submit Your Review</h3>
    <form id="reviewForm" action="save_review.php" method="POST">
        <input type="hidden" name="product_id" value="<?php echo isset($product_id) ? $product_id : ''; ?>">
        <input type="hidden" name="user_id" value="<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0; ?>">

        <!-- Star Rating -->
        <div class="mb-3">
            <label class="form-label"><strong>Rating:</strong></label>
            <div class="star-rating">
                <input type="radio" id="star5" name="rating" value="5"><label for="star5" class="fas fa-star"></label>
                <input type="radio" id="star4" name="rating" value="4"><label for="star4" class="fas fa-star"></label>
                <input type="radio" id="star3" name="rating" value="3"><label for="star3" class="fas fa-star"></label>
                <input type="radio" id="star2" name="rating" value="2"><label for="star2" class="fas fa-star"></label>
                <input type="radio" id="star1" name="rating" value="1"><label for="star1" class="fas fa-star"></label>
            </div>
        </div>

        <!-- Review Text -->
        <div class="mb-3">
            <label class="form-label"><strong>Write your review:</strong></label>
            <textarea name="review_text" class="form-control rounded-3" rows="4" placeholder="Share your experience..." required></textarea>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Submit Review</button>
    </form>
</div>
</section>
<section class="re-products">
 <?php if ($related_result->num_rows > 0) { ?>
    <h1 class="mt-4 text-center text-primary">🔗 Related Products</h1><br/>
    <div class="row justify-content-center">
        <?php while ($row = $related_result->fetch_assoc()) { ?>
            <div class="col-md-3">
                <div class="card shadow-sm mb-3 border-0 rounded-lg overflow-hidden product-card">
                    <img src="uploads/<?= htmlspecialchars($row['image']); ?>" class="card-img-top product-img" alt="<?= htmlspecialchars($row['name']); ?>">
                    <div class="card-body text-center">
                        <h5 class="card-title text-dark"><?= htmlspecialchars($row['name']); ?></h5>
                        <p class="text-danger font-weight-bold">₹<?= number_format($row['price'], 2); ?></p>
                        <a href="view_details.php?id=<?= $row['id']; ?>" class="btn btn-info btn-sm rounded-pill px-3">
                            🔍 View Details
                        </a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
<?php } ?>

<style>
    .product-card {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        background: #fff;
    }
    
    .product-card:hover {
        transform: scale(1.05);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    .product-img {
        height: 200px;
        object-fit: cover;
        border-radius: 8px 8px 0 0;
    }

    .btn-info {
        background-color: #17a2b8;
        border: none;
    }

    .btn-info:hover {
        background-color: #138496;
    }
</style>

</div>
</section>
<?php include'footer.php';?>
  
</html>
</body>
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function () {
    const stars = document.querySelectorAll(".star-rating label");
    const radioButtons = document.querySelectorAll(".star-rating input");

    // Function to reset all stars to default color
    function resetStars() {
        stars.forEach(star => star.style.color = "#ddd");
    }

    // Function to highlight stars up to the selected/starred index
    function highlightStars(index) {
        resetStars();
        for (let i = 0; i <= index; i++) {
            stars[i].style.color = "#ffc107";
        }
    }

    stars.forEach((star, index) => {
        star.addEventListener("mouseover", function () {
            highlightStars(index);
        });

        star.addEventListener("mouseout", function () {
            const checkedIndex = Array.from(radioButtons).findIndex(rb => rb.checked);
            highlightStars(checkedIndex);
        });

        star.addEventListener("click", function () {
            radioButtons[index].checked = true;
        });
    });
});



    function validateQuantity() {
    let quantity = document.getElementById("quantity").value;
    let maxStock = <?php echo $available_stock; ?>;
    
    if (quantity > maxStock) {
        document.getElementById("stockMessage").style.display = "inline";
        return false; // Prevent form submission
    }
    return true; // Allow submission
}
</script>