<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Murti Request</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #ff9a9e, #fad0c4);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            width: 480px;
            height: 600px;
            transition: transform 0.3s ease;
        }
        .form-container:hover {
            transform: scale(1.02);
        }
        h2 {
            text-align: center;
            color: #333;
            font-weight: 600;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            transition: 0.3s;
        }
        input:focus, select:focus, textarea:focus {
            border-color: #ff6600;
            box-shadow: 0px 0px 8px rgba(255, 102, 0, 0.5);
            outline: none;
        }
        button {
            background: linear-gradient(135deg, #ff7e5f, #ff6600);
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            cursor: pointer;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            transition: 0.3s;
        }
        button:hover {
            background: linear-gradient(135deg, #ff6600, #ff4500);
            box-shadow: 0px 5px 10px rgba(255, 102, 0, 0.4);
        }
        .form-group {
            position: relative;
        }
        .form-group label {
            font-size: 14px;
            color: #555;
            position: absolute;
            top: -8px;
            left: 10px;
            background: white;
            padding: 2px 5px;
            border-radius: 5px;
            font-weight: 500;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Request Custom Murti</h2>
    <form action="submit_request.php" method="POST">
        
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>

        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" required>
        </div>

        <div class="form-group">
            <label>Color Preference</label>
            <select name="color">
                <option value="Gold">Gold</option>
                <option value="White">White</option>
                <option value="Black">Black</option>
                <option value="Marble">Marble</option>
            </select>
        </div>

        <div class="form-group">
            <label>Size (in inches)</label>
            <input type="number" name="size" required>
        </div>

        <div class="form-group">
            <label>Price Range (₹)</label>
            <input type="text" name="price" required>
        </div>

        <div class="form-group">
            <label>Additional Details</label>
            <textarea name="details" rows="4"></textarea>
        </div>

        <button type="submit">Submit Request</button>
    </form>
</div>

</body>
</html>
