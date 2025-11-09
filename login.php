<?php
session_start();

// Database connection
$servername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbname = "myproject";

$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$login_error = $register_error = "";

// Handle Login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login_submit'])) {
    $login_username = trim($_POST['login_username'] ?? "");
    $login_password = trim($_POST['login_password'] ?? "");

    if (empty($login_username) || empty($login_password)) {
        $login_error = "Please enter both username and password!";
    } else {
        $sql = "SELECT id, username, password, status FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $login_username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $username, $hashed_password, $status);
            $stmt->fetch();

            if ($status === "blocked") {
                $login_error = "Your account is blocked!";
            } elseif (password_verify($login_password, $hashed_password)) {
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = "Login successful! Welcome, $username.";
                header("Location: index.php");
                exit();
            } else {
                $login_error = "Invalid password!";
            }
        } else {
            $login_error = "User not found!";
        }
        $stmt->close();
    }
}

// Handle Registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register_submit'])) {
    $register_username = trim($_POST['register_username'] ?? "");
    $register_email = trim($_POST['register_email'] ?? "");
    $register_mobile = trim($_POST['register_mobile'] ?? "");
    $register_password = trim($_POST['register_password'] ?? "");
    $hashed_password = password_hash($register_password, PASSWORD_DEFAULT);

    if (empty($register_username) || empty($register_email) || empty($register_mobile) || empty($register_password)) {
        $register_error = "All fields are required!";
    } elseif (!preg_match('/^[a-zA-Z0-9_]{3,16}$/', $register_username)) {
        $register_error = "Invalid username! Use 3-16 alphanumeric characters.";
    } elseif (!filter_var($register_email, FILTER_VALIDATE_EMAIL)) {
        $register_error = "Invalid email format!";
    } elseif (!preg_match('/^[0-9]{10}$/', $register_mobile)) {
        $register_error = "Invalid! Mobile number should be 10 digits.";
    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $register_password)) {
        $register_error = "Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, one number, and one special character!";
    } else {
        $check_sql = "SELECT id FROM users WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("ss", $register_username, $register_email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $register_error = "Username or Email already exists!";
        } else {
            $insert_sql = "INSERT INTO users (username, email, mobile_no, password, status, created_at) VALUES (?, ?, ?, ?, 'active', NOW())";
            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param("ssss", $register_username, $register_email, $register_mobile, $hashed_password);

            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Registration successful! You can now log in.";
                header("Location: index.php?register_success=1");
                exit();
            } else {
                $register_error = "Registration failed. Try again!";
            }
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login & Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {

       
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh; /* Adjusted height */
    font-family: Arial, sans-serif;
    background-image: url('Ganesh utsav.jpeg');
    background-size: 65%;
    background-repeat: no-repeat;
    background-position: center;
}

.form-container {
    margin-left:1;
    margin-top: 50px;
    width: 30%; /* Increased width */
    padding: 50px; /* Increased padding */
    border-radius: 30px;
     animation: fadeIn 0.8s ease-in-out;
    text-align: center;
    max-width: 800px; /* Increased max-width */
    color: white;
    position: absolute;
    left: 25%; /* Left side ला हलवण्यासाठी */
    
}

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
.h2 {
    color: #333;
}

.form-control {
    background: #fff; /* Solid background */
    border: 1px solid blue;
    color: black;
}

.form-control::placeholder {
    color: #888;
}

.btn-primary, .btn-success {
    width: 100%;
    transition: 0.3s;
    font-weight: bold;
}

.btn-primary:hover, .btn-success:hover {
    transform: scale(1.05);
}


.switch {
    cursor: pointer;
    text-decoration: underline;
    color: white;
    font-weight: bold

}

.alert {
    color: #000;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 5px;
    padding: 10px;
}
.buy-card{ margin-top:100px ;}
.back-arrow {
    display: block;
    margin-top: 30%;
    text-align: center;
    font-size: 18px;
    font-weight: bold;
    color: skyblue;
    text-decoration: none;
    transition: 0.3s;
}

.back-arrow:hover {
    color: deepskyblue;
    transform: scale(1.1);
}


    </style>
</head>
<body>


    <div class="form-container">
        <!-- Login Form -->
        <div id="loginForm">
            <h1>Login</h1>
            <br/> <br/> <br/>
            <form action="" method="post">
                <input type="text" name="login_username" class="form-control mb-3" placeholder="Username" required>
                <input type="password" name="login_password" class="form-control mb-3" placeholder="Password" required>
                <button type="submit" name="login_submit" class="btn btn-primary">Login</button>
            </form>
            <p class="mt-3"><h5>Don't have an account? </h5><span class="sw600pch" onclick="toggleAuth()"><b>Register</b></span></p>
        </div>

        <!-- Register Form -->
        <div id="registerForm" style="display: none; opacity: 0;">
            <h1>Register</h1>
             <br/> <br/> <br/>
            <form action="" method="post">
                <input type="text" name="register_username" class="form-control mb-3" placeholder="Username" required>
                <input type="email" name="register_email" class="form-control mb-3" placeholder="Email" required>
                <input type="text" name="register_mobile" class="form-control mb-3" placeholder="Mobile No" required pattern="[0-9]{10}">
                <input type="password" name="register_password" class="form-control mb-3" placeholder="Password" required>
                <button type="submit" name="register_submit" class="btn btn-success" >Register</button>
            </form>
            <p class="mt-3" ><h4>Already have an account?</h4> <span class="switch" onclick="toggleAuth()"><b>Login</b></span></p>
        </div>
    </div>
    

    <!-- Error Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Error</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="errorMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleAuth() {
            if ($("#loginForm").is(":visible")) {
                $("#loginForm").fadeOut(300, function() {
                    $("#registerForm").css("opacity", 0).show().animate({opacity: 1}, 300);
                });
            } else {
                $("#registerForm").fadeOut(300, function() {
                    $("#loginForm").css("opacity", 0).show().animate({opacity: 1}, 300);
                });
            }
        }
        function toggleAuth() {
        if ($("#loginForm").is(":visible")) {
            $("#loginForm").fadeOut(300, function() {
                $("#registerForm").css({"opacity": 0, "transform": "translateY(-20px)"}).show().animate({opacity: 1, transform: "translateY(0)"}, 300);
            });
        } else {
            $("#registerForm").fadeOut(300, function() {
                $("#loginForm").css({"opacity": 0, "transform": "translateY(-20px)"}).show().animate({opacity: 1, transform: "translateY(0)"}, 300);
            });
        }
    }

        document.addEventListener("DOMContentLoaded", function() {
            let errorMessage = "<?php echo addslashes($login_error ?: $register_error); ?>";
            if (errorMessage.trim() !== "") {
                document.getElementById("errorMessage").innerText = errorMessage;
                var errorModal = new bootstrap.Modal(document.getElementById("errorModal"));
                errorModal.show();
            }
        });

    </script>

</body>
</html>
