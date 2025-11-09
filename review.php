<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "myproject";

// Connect to database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure product ID is passed
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid product request.");
}

$product_id = intval($_GET['id']);

// Fetch reviews
$reviews_sql = "SELECT r.rating, r.review_text, u.username, r.created_at FROM review r 
                JOIN users u ON r.user_id = u.id WHERE r.product_id = ? ORDER BY r.created_at DESC";
$stmt = $conn->prepare($reviews_sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

// Display reviews
if ($result->num_rows > 0) {
    echo "<h3>Customer Reviews</h3>";
    while ($review = $result->fetch_assoc()) {
        echo "<div class='review-box'>";
        echo "<strong>" . htmlspecialchars($review['username']) . "</strong> ";
        echo "<span class='text-muted'>(" . date("d M Y", strtotime($review['created_at'])) . ")</span><br>";
        echo "<span class='stars'>" . str_repeat("⭐", $review['rating']) . "</span>";
        echo "<p>" . htmlspecialchars($review['review_text']) . "</p>";
        echo "<hr>";
        echo "</div>";
    }
} else {
    echo "<p>No reviews yet.</p>";
}

$stmt->close();
$conn->close();
?>
<div class="container mt-4">
    <h3>Submit Your Review</h3>
    <form action="save_review.php" method="POST">
        <input type="hidden" name="product_id" value="<?= $product_id; ?>">
        <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?? 0; ?>">

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
