<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Navbar</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .navbar {
            background-color: #333;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
        }
        .navbar .logo {
            font-size: 24px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .navbar ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 20px;
        }
        .navbar ul li {
            position: relative;
        }
        .navbar ul li a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            padding: 8px 16px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        .navbar ul li a:hover {
            background-color: #ff416c;
        }
        .navbar .dropdown {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #444;
            width: 200px;
            border-radius: 5px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }
        .navbar ul li:hover .dropdown {
            display: block;
        }
        .navbar .dropdown a {
            display: block;
            padding: 10px 20px;
        }
        .navbar .dropdown a:hover {
            background-color: #ff416c;
        }
        .logout-btn {
            background-color: #e74c3c;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .logout-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

<div class="navbar">
    <div class="logo">Idol Admin</div>
    <ul>
        <li><a href="admin_dashboard.php">Dashboard</a></li>
        <li> Category
        <div class="dropdown">
        <a href="add_category.php">Categories</a>
       <a href="Categories.php">Category products</a>
       </div></li>
        <li><a href="add_product.php">Products</a></li>
        <li><a href="order_manage.php">Orders</a></li>
        <li><a href="manage_user.php">Users</a></li>
        <li>Reports
                    <div class="dropdown">
                <a href="stock_report.php">Stock Reports</a>
                 <a href="sales_report.php">Sales Reports</a>
                  <a href="orders_report.php">Orders Reports</a>
                   <a href="customer_report.php">Customer Reports</a>
                    <a href="customer_interest_report.php">Customer Interest Reports</a>
                     <a href="payment_report.php">Payment Reports</a>
               </div></li>
        
        <li>
            <a href="#">Account</a>
            <div class="dropdown">
                <a href="admin_p.php">Profile</a>
                <a href="change_password.php">Change Password</a>
                <a href="admin_logout.php" class="logout-btn">Logout</a>
            </div>
        </li>
    </ul>
</div>

</body>
</html>
