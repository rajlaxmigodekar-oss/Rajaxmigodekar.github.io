<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$database = "myproject";

// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name'], $_POST['email'], $_POST['message'])) {
    // Escape user input to prevent SQL injection
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $message = $conn->real_escape_string($_POST['message']);

    // Insert into database
    $sql = "INSERT INTO contact_form (name, email, message, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $email, $message);

    
    if ($stmt->execute()) {
        $_SESSION['message'] = "✅ Message Sent Successfully!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "❌ Something went wrong, please try again!";
        $_SESSION['message_type'] = "danger";
    }

    $stmt->close();
    $conn->close();

    // Redirect back to the contact page
    header("Location: contact_form.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Omkara Murtis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(45deg, #E6D6F2, #FFB6C1, #B0E2FF);
            margin: 0;
            padding: 0;
        }
        .header {
            background: url('image2.jpeg') no-repeat center/cover;
            color: white;
            text-align: left;
            padding: 300px 50px;
            position: relative;
            font-size: 50px;
            font-weight: bold;
            text-transform: uppercase;
            opacity: 0;
            animation: fadeInDown 1s ease-in-out forwards;
        }
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-50px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .section-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 30px;
        }
        .section-title::after {
            content: "";
            width: 100px;
            height: 4px;
            background: #007bff;
            display: block;
            margin: 10px auto;
        }
        .about-content {
            text-align: center;
            font-size: 1.2rem;
            max-width: 800px;
            margin: auto;
            opacity: 0;
            animation: fadeInUp 1s ease-in-out 0.5s forwards;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .mission-values {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin-top: 50px;
        }
        .card-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 400px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .card-box:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        .card-box h3 {
            color: #007bff;
        }
        .animate-word {
            color: blue;
            font-weight: bold;
            animation: colorChange 1s infinite alternate;
            font-size: 40px;
        }
        @keyframes colorChange {
            from { color: blue; }
            to { color: green; }
        }
        .card {
            width: 40%;
            background: white;
            backdrop-filter: blur(10px);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(255, 255, 255, 0.2);
            text-align: center;
            margin: 100px auto;
        }
        .card h2 {
            font-size: 28px;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .form-box input,
        .form-box textarea {
            width: 100%;
            background: transparent;
            border: none;
            border-bottom: 2px solid white;
            padding: 10px;
            margin-bottom: 20px;
            color: black;
            font-size: 16px;
        }
        .form-box input:focus, 
        .form-box textarea:focus {
            outline: none;
            border-bottom: 2px solid orange;
        }
        .form-box button {
            width: 100%;
            padding: 12px;
            border: none;
            background: linear-gradient(to right, orange, blue);
            color: white;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s;
        }
        .form-box button:hover {
            background: linear-gradient(to right, blue, orange);
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="header">About Us</div>
    <br/><br/>
    <center><span class="animate-word">Welcome to Omkara Murtis</span></center>
    
    <div class="container">
        <section>
            <p class="about-content">
                Welcome to Omkara Murtis, the ultimate online marketplace for collectors and enthusiasts of idols from our rich traditions. 
                Whether you're looking to purchase a divine piece for your home or find a new custodian for your cherished idol, 
                we provide a safe and authentic platform.
            </p>
        </section>
        
        <section class="mission-values">
            <div class="card-box">
                <h3>Our Mission</h3>
                <p>To create a seamless experience for idol collectors and artisans, bridging the gap between tradition and modern technology.</p>
            </div>
            <div class="card-box">
                <h3>Our Values</h3>
                <ul>
                    <li><strong>Integrity:</strong> Transparent and honest transactions.</li>
                    <li><strong>Quality:</strong> Only the best, authentic idols.</li>
                    <li><strong>Community:</strong> A space for passionate collectors.</li>
                    <li><strong>Security:</strong> Safe and protected transactions.</li>
                </ul>
            </div>
        </section>
    </div>

    <div class="card">
        <h2>Contact Us</h2>
        <form action="contact_form.php" method="POST" class="form-box">
            <input type="text" name="name" placeholder="Your Name" required>
            <input type="email" name="email" placeholder="Your Email" required>
            <textarea name="message" placeholder="Share your thoughts" rows="4" required></textarea>
            <button type="submit">SHARE YOUR FEEDBACK</button>
        </form>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
