
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

// Fetch categories for filter (for category buttons)
$category_sql = "SELECT * FROM categories ORDER BY category_name ASC";
$category_result = $conn->query($category_sql);

// Get selected category from GET parameters
$selected_category = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;
$selectedCategoryName = "";
if ($selected_category > 0) {
    $catQuery = "SELECT category_name FROM categories WHERE id = $selected_category";
    $catResult = $conn->query($catQuery);
    if($catResult && $catResult->num_rows > 0) {
        $catRow = $catResult->fetch_assoc();
        $selectedCategoryName = $catRow['category_name'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Idols Marketplace</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        /* Global Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;

    
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        }
        .slideshow-container {
            position: relative;
            width: 100%;
            height: 100%;
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            animation: fadeIn 2s ease-in-out;
        }
        .slideshow-container img {
            width: 100%;
            height: 90%;
            position: absolute;
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }
        .slideshow-container img.active {
            opacity: 1;
        }
        
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        .shop-now-btn {
            background: linear-gradient(45deg, #ff6b6b, #ff8e53);
            color: white;
            font-size: 18px;
            font-weight: bold;
            padding: 12px 24px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }
        .shop-now-btn:hover {
            background: linear-gradient(45deg, #ff8e53, #ff6b6b);
            transform: scale(1.05);
            box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.3);
        }
        
        /* Sections */
        .categories, .featured, .new-arrivals {
            margin-top: 600px;
            padding: 80px 20px;
            text-align: center;
            
           background: linear-gradient(45deg, #E6D6F2, #FFB6C1, #B0E2FF);

        }
        .categories h2, .featured h2, .new-arrivals h2 {
            font-size: 2.5rem;
            margin-bottom: 30px;
            color: #333;
            text-transform: uppercase;
        }
        .category-grid, .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            justify-content: center;
        }
        .category-item, .product-item {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            animation: slideIn 1.5s ease-in-out;
        }
        .category-item:hover, .product-item:hover {
            transform: scale(1.05);
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.2);
        }
        .category-item img, .product-item img {

            width: 50%;
            height: 250px;
            border-radius: 10px;
        }

        /* Attractive View Details Button */
        .view-btn {
            background: linear-gradient(45deg, #007BFF, #00C6FF);
            color: white;
            font-size: 16px;
            font-weight: bold;
            padding: 10px 20px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            display: block;
            width: 80%;
            margin: 10px auto 0;
            text-transform: uppercase;
        }
        .view-btn:hover {
            background: linear-gradient(45deg, #00C6FF, #007BFF);
            transform: scale(1.1);
            box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.3);
        }
      

/* Product grid */
.product-grid {
    display: flex;
    gap: 20px;
    padding-bottom: 10px;
}

/* Product Card */
.product-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    width: 100%; 
    max-width: 400px; /* Optimal width */
    min-height: 500px; /* Increased height */
    text-align: center;
    display: flex;

    flex-direction: column; /* Ensures elements align in a vertical layout */
    justify-content: space-between; /* Ensures even spacing */
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
}

/* Hover Effect */
.product-card:hover {
    transform: scale(1.05);
    box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.2);
}

/* Product Image */
.product-image {
    width: 100%;  
    height: 300px;  
    object-fit: contain;  /* Changed from 'cover' to 'contain' */
   margin: auto; 
    display: block;
    background-color: #f8f8f8; /* Optional: Adds a background if the image has transparent areas */
}


/* Card Content */
.card-content {
    padding: 15px;
    display: flex;
    flex-direction: column;
    justify-content: center; /* Centers text properly */
    align-items: center; /* Ensures center alignment */
    flex-grow: 1; /* Allows content to expand properly */
}

/* Title Styling */
.card-content h3 {
    font-size: 20px;
    font-weight: bold;
    margin: 10px 0;
}

/* Price Styling */
.card-content p {
    font-size: 18px;
    color: #333;
    font-weight: bold;
    margin-bottom: 15px;
}



/* Category Title */
.category-title {
    font-size: 14px;
    color: #888;
    text-transform: uppercase;
    margin-bottom: 5px;
}

/* Product Name */
.product-name {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 8px;
}
@import url('https://fonts.googleapis.com/css2?family=Great+Vibes&family=Playfair+Display:wght@700&display=swap');

.slideshow-heading {
    font-family: 'Great Vibes', cursive;
    font-size: 70px;
    font-weight: bold;
    color: #fff;
    text-shadow: 3px 3px 10px rgba(0, 0, 0, 0.7), 0px 0px 20px rgba(255, 215, 0, 0.8);
    position: absolute;
    top: 50%;
    left:20%;
    transform: translate(-50%, -50%);
    z-index: 2;
    
    padding: 10px 20px;
    border-radius: 15px;
}.slideshow-text {
    font-family: 'Playfair Display', serif;
    font-size: 20px;
    color: #fff;
    text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.6);
    max-width: 50%; /* Adjust width for better left alignment */
    position: absolute;
    top: 65%;
    left: 7%; /* Moves text towards the left */
    transform: translateY(-50%); /* Only translate vertically */
    z-index: 2;
  
    padding: 15px 20px;
    border-radius: 12px;
    line-height: 1.5;
    text-align: left; /* Aligns text to the left */
}





 </style>
</head>
<body>

    <?php include 'navbar.php'; ?>
    <section>
<div class="slideshow-container">
        <img src="image1.jpg" class="active"> 
       <img src="image4.jpg">
            <h1 class="slideshow-heading">Welcome to <br/>Omkara Murtis</h1><br/><br/>
            <p class="slideshow-text">Explore our collection and bring home spiritual bliss.</p>
    </div>
    </section>
<br/><br/><br/>
   <section class="categories">
    <br/><br/><br/><br/>
    <h2>Browse by Category</h2>
  <br/><br/><br/>
        <div class="product-grid">  <!-- This is for all product cards -->
            <?php
            while ($category = $category_result->fetch_assoc()) {
                $category_id = $category['id'];
                $category_name = $category['category_name'];

                // Fetch only one product from this category
                $product_sql = "SELECT * FROM products WHERE category_id = $category_id LIMIT 1";
                $product_result = $conn->query($product_sql);

                // Check if there is at least one product
                if ($product_result->num_rows > 0) {
                    $product = $product_result->fetch_assoc();
            ?>
                <div class="product-card">
                    <img src="uploads/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="product-image">
                    <div class="card-content">
                        <h3 class="category-title"><?php echo $category_name; ?></h3>
                    
                        <button class="view-btn" onclick="window.location.href='view_details.php?id=<?php echo $product['id']; ?>'">View Details</button>
                    </div>
                </div>
            <?php 
                }
            } 
            ?>
        </div> 
    </div>
</section><?php include 'trending.php'; ?>
<br/><br/>
<script type="text/javascript">
    let slideIndex = 0;
        const slides = document.querySelectorAll(".slideshow-container img");

        function showSlides() {
            slides.forEach((slide, index) => {
                slide.classList.remove("active");
            });
            slideIndex = (slideIndex + 1) % slides.length;
            slides[slideIndex].classList.add("active");
        }

        setInterval(showSlides, 3000); // 3 सेकंदांनी प्रतिमा बदला
</script>
<?php include 'footer.php'; ?>

</body>
</html>
