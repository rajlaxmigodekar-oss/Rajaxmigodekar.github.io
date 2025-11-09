<?php
session_start();
// Database Connection
$conn = new mysqli("localhost", "root", "", "myproject");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize error message
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate Input
    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        // Fetch admin details
        $sql = "SELECT * FROM admin_login WHERE email = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();

        if ($admin && password_verify($password, $admin['password'])) {
            // Set session variables
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_email'] = $admin['email'];

            // Redirect to admin dashboard
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "Invalid email or password.";
        }
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
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 350px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .error {
            color: red;
            text-align: center;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="admin_login.php" method="POST">
            <label for="email">Email:</label>
            <input type="text" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" name="login">Login</button>
        </form>
    </div>
</body>
</html>
