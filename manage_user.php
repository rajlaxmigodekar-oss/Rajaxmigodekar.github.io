<?php
session_start();
$conn = new mysqli("localhost", "root", "", "myproject");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch users
$result = $conn->query("SELECT id, username, email, mobile_no, status FROM users");

// Delete user
if (isset($_GET["delete_id"])) {
    $id = intval($_GET["delete_id"]);
    $conn->query("DELETE FROM users WHERE id = $id");
    header("Location: manage_user.php");
    exit();
}

// Block/Unblock user
if (isset($_GET["block_id"])) {
    $id = intval($_GET["block_id"]);
    $new_status = ($_GET["status"] === "active") ? "blocked" : "active";
    $conn->query("UPDATE users SET status='$new_status' WHERE id = $id");
    header("Location: manage_user.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   < <title>Manage Users</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background: #333; color: white; }
        a { padding: 5px 10px; text-decoration: none; border-radius: 5px; }
        .delete { background: red; color: white; }
        .block { background: orange; color: white; }
      </style>
</head>
<body>
<?php include 'anav.php';?>
<center><h2>Manage Users</h2></center>
<table style="margin-left:150px ;">
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Email</th>
        <th>Mobile No</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= $row["id"] ?></td>
            <td><?= htmlspecialchars($row["username"]) ?></td>
            <td><?= htmlspecialchars($row["email"]) ?></td>
            <td><?= htmlspecialchars($row["mobile_no"]) ?></td>
            <td><?= ucfirst($row["status"]) ?></td>
            <td>
            
                <a href="?block_id=<?= $row["id"] ?>&status=<?= $row["status"] ?>" class="block">
                    <?= ($row["status"] === "active") ? "Block" : "Unblock" ?>
                </a>
            </td>
        </tr>
    <?php } ?>
</table>

</body>
</html>
