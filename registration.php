<?php 
// Include database connection details 
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

// Check if form is submitted 
if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    // Get form data 
    $name = trim($_POST['name']); 
    $mobile_no = trim($_POST['mobile_no']); 
    $email_id = trim($_POST['email_id']); 
    $password = trim($_POST['password']); 
    $confirm_password = trim($_POST['confirm_password']); 

    // Validate passwords match 
    if ($password !== $confirm_password) { 
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        // Hash the password for security 
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); 

        // Prepare and bind SQL query 
        $stmt = $conn->prepare("INSERT INTO registration (name, mobile_no, email_id, password) VALUES (?, ?, ?, ?)"); 
        $stmt->bind_param("ssss", $name, $mobile_no, $email_id, $hashed_password); 

        // Execute the query 
        if ($stmt->execute()) { 
            echo "<script>alert('Registration Successful!'); window.location.href='login.php';</script>"; 
        } else { 
            echo "<script>alert('Error: " . $stmt->error . "');</script>"; 
        } 

        // Close the statement and connection 
        $stmt->close(); 
    }
}

$conn->close(); 
?>
<!DOCTYPE html> 
<html lang="en"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>User Registration</title> 

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body {
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .container {
            display: flex;
            justify-content: center; /* Horizontally center */
            align-items: center; /* Vertically center */
            min-height: 100vh; /* Take up at least the full height of the screen */
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-grow: 1;
        }
        .card {
            width: 400px;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            background: white;
        }
        .form-control {
            border-radius: 10px;
        }
        .btn-primary {
            background: #6a11cb;
            border: none;
            transition: 0.3s ease-in-out;
        }
        .btn-primary:hover {
            background: #2575fc;
        }
        .footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: auto;
        }
    </style>
</head> 
<body> 
    <?php include 'navbar.php'; ?>

    <div class="container"> 
        <div class="card">
            <h2 class="text-center mb-4">Sign Up</h2> 
            <form action="registration.php" method="POST" onsubmit="return validateForm();"> 
                <div class="mb-3"> 
                    <label for="name" class="form-label">Full Name</label> 
                    <input type="text" class="form-control" id="name" name="name" required> 
                </div> 
                <div class="mb-3"> 
                    <label for="mobile_no" class="form-label">Mobile Number</label> 
                    <input type="text" class="form-control" id="mobile_no" name="mobile_no" required pattern="\d{10}" title="Enter a valid 10-digit mobile number"> 
                </div> 
                <div class="mb-3"> 
                    <label for="email_id" class="form-label">Email ID</label> 
                    <input type="email" class="form-control" id="email_id" name="email_id" required> 
                </div> 
                <div class="mb-3"> 
                    <label for="password" class="form-label">Password</label> 
                    <input type="password" class="form-control" id="password" name="password" required minlength="6" title="Password must be at least 6 characters long"> 
                </div> 
                <div class="mb-3"> 
                    <label for="confirm_password" class="form-label">Confirm Password</label> 
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required> 
                </div> 
                <button type="submit" class="btn btn-primary w-100">Register</button> 
            </form> 
            <p class="text-center mt-3">Already have an account? <a href="login.php"><b>Login here</b></a></p>
        </div>
    </div> 

    <script> 
        function validateForm() { 
            const password = document.getElementById("password").value; 
            const confirmPassword = document.getElementById("confirm_password").value; 

            if (password !== confirmPassword) { 
                alert("Passwords do not match."); 
                return false; 
            } 
            return true; 
        } 
    </script> 

    <div class="footer">
        <?php include 'footer.php'; ?>
    </div>

</body> 
</html>
