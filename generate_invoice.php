<?php
require('fpdf/fpdf.php'); // Include FPDF library

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "myproject";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id'])) {
    $order_id = intval($_POST['order_id']);

    $sql = "SELECT 
                o.id AS order_id, o.created_at, u.username AS buyer_name, u.email, 
                p.name AS product_name, p.image, oi.price 
            FROM orders o
            JOIN users u ON o.user_id = u.id
            JOIN order_items oi ON o.id = oi.order_id
            JOIN products p ON oi.product_id = p.id
            WHERE o.id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        // Create PDF Invoice
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(190, 10, "🧾 Invoice - Order #{$row['order_id']}", 0, 1, 'C');
        
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(190, 10, "Date: " . date('d-m-Y', strtotime($row['created_at'])), 0, 1, 'C');
        
        $pdf->Ln(10);
        $pdf->Cell(50, 10, "👤 Buyer Name: " . $row['buyer_name'], 0, 1);
        $pdf->Cell(50, 10, "📧 Email: " . $row['email'], 0, 1);
        
        $pdf->Ln(10);
        $pdf->Cell(50, 10, "📛 Product Name: " . $row['product_name'], 0, 1);
        $pdf->Cell(50, 10, "💰 Price: ₹" . number_format($row['price'], 2), 0, 1);
        
        $pdf->Ln(10);
        $pdf->Cell(50, 10, "✅ Payment Status: Paid", 0, 1);

        $pdf->Output("D", "Invoice_Order_" . $row['order_id'] . ".pdf"); // Download invoice
    } else {
        echo "❌ No order found!";
    }

    $stmt->close();
}
$conn->close();
?>
