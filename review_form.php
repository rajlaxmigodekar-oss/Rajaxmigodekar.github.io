<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "myproject";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize message status
$messageStatus = "";

// Handle feedback form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['feedback_submit'])) {
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
    $feedback_text = trim(htmlspecialchars($_POST['feedback_text']));

    if ($product_id > 0 && $user_id > 0 && $rating > 0 && !empty($feedback_text)) {
        $stmt = $conn->prepare("INSERT INTO review (product_id, user_id, rating, review_text, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("iiis", $product_id, $user_id, $rating, $feedback_text);

        if ($stmt->execute()) {
            $messageStatus = "<div class='alert alert-success'>Thank you! Your review has been submitted.</div>";
        } else {
            $messageStatus = "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
        }
        $stmt->close();
    } else {
        $messageStatus = "<div class='alert alert-warning'>All fields are required.</div>";
    }
}

$conn->close();
?>

<!-- Feedback Form -->
<div class="container mt-5">
    <div class="card shadow-lg p-4 border-0" style="max-width: 600px; margin: auto; background: linear-gradient(135deg, #f9f9f9, #ffffff); border-radius: 15px;">
        <h2 class="text-center text-primary fw-bold">Give Your Feedback</h2>
        <p class="text-center text-muted">Your opinion matters to us!</p>
        
        <?php echo $messageStatus; ?>

        <form action="" method="POST">
            <input type="hidden" name="product_id" value="1"> <!-- Replace with dynamic value -->
            <input type="hidden" name="user_id" value="2"> <!-- Replace with dynamic session user -->

            <!-- Star Rating -->
            <div class="mb-3 text-center">
                <label class="form-label fw-semibold">Your Rating</label>
                <div class="star-rating">
                    <input type="radio" id="star5" name="rating" value="5" required><label for="star5" class="fas fa-star"></label>
                    <input type="radio" id="star4" name="rating" value="4"><label for="star4" class="fas fa-star"></label>
                    <input type="radio" id="star3" name="rating" value="3"><label for="star3" class="fas fa-star"></label>
                    <input type="radio" id="star2" name="rating" value="2"><label for="star2" class="fas fa-star"></label>
                    <input type="radio" id="star1" name="rating" value="1"><label for="star1" class="fas fa-star"></label>
                </div>
            </div>

            <!-- Feedback Text -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Your Feedback</label>
                <textarea class="form-control border-0 shadow-sm" name="feedback_text" rows="4" placeholder="Write your feedback..." required></textarea>
            </div>

            <!-- Submit Button -->
            <button type="submit" name="feedback_submit" class="btn btn-primary w-100 shadow">Submit Feedback</button>
        </form>
    </div>
</div>

<style>
/* Star Rating Styling */
.star-rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: center;
    gap: 8px;
    font-size: 32px;
    cursor: pointer;
}
.star-rating input {
    display: none;
}
.star-rating label {
    color: #ccc;
    transition: color 0.3s ease, transform 0.2s ease;
}
.star-rating input:checked ~ label,
.star-rating label:hover,
.star-rating label:hover ~ label {
    color: gold;
    transform: scale(1.2);
}

/* Responsive */
@media (max-width: 600px) {
    .star-rating {
        font-size: 28px;
        gap: 5px;
    }
}
</style>

<!-- Font Awesome for Icons -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
