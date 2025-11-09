<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "myproject";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details (username, email, profile image)
$sql_user = "SELECT username, email, profile_image FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user = $result_user->fetch_assoc();

$error_message = "";
$success_message = "";

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = trim($_POST['username']);
    $new_email = trim($_POST['email']);
    
    $profile_image = $user['profile_image']; // Default to current image

    // Validation: Ensure required fields are filled
    if (!empty($new_username) && !empty($new_email)) {
        
        // Handle profile image upload if a new file is selected
        if (!empty($_FILES['profile_image']['name'])) {
            $target_dir = "uploads/";
            $image_name = time() . "_" . basename($_FILES["profile_image"]["name"]); // Unique file name
            $target_file = $target_dir . $image_name;

            // Check file type (allow only jpg, png, gif)
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            if (in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
                if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                    $profile_image = $target_file; // Update with new image path
                } else {
                    $error_message = "Error uploading image.";
                }
            } else {
                $error_message = "Only JPG, JPEG, PNG & GIF files are allowed.";
            }
        }

        // Update user profile
        $update_user = "UPDATE users SET username = ?, email = ?, profile_image = ? WHERE id = ?";
        $stmt_user_update = $conn->prepare($update_user);
        $stmt_user_update->bind_param("sssi", $new_username, $new_email, $profile_image, $user_id);
        
        if ($stmt_user_update->execute()) {
            $_SESSION['success_message'] = "Profile updated successfully.";
            header("Location: profile.php");
            exit();
        } else {
            $error_message = "Failed to update profile.";
        }
    } else {
        $error_message = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(45deg, #E6D6F2, #FFB6C1, #B0E2FF); }
        .profile-picture {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .profile-picture img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #007bff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .info-box {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            margin-top: 20px;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Profile</h2>

        <?php if (!empty($error_message)) { ?>
            <p class="text-danger"><?= $error_message; ?></p>
        <?php } ?>
        <?php if (!empty($_SESSION['success_message'])) { ?>
            <p class="text-success"><?= $_SESSION['success_message']; unset($_SESSION['success_message']); ?></p>
        <?php } ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="profile-picture">
                <img src="<?= !empty($user['profile_image']) ? htmlspecialchars($user['profile_image']) : 'uploads/default-avatar.png'; ?>?t=<?= time(); ?>" 
                     alt="Profile Picture">
            </div>
            <div class="mb-3">
                <label class="form-label">Profile Picture</label>
                <input type="file" name="profile_image" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']); ?>" required>
            </div>

            <!-- Save Button -->
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</body>
</html>
