<?php
include '../db.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


header('Content-Type: application/json');

// Fetching Sales Data (Sales Revenue Calculation)
$salesQuery = "SELECT 
                    p.ProductName, 
                    SUM(o.quantity * p.Price) AS total_revenue
               FROM 
                    orders o
               JOIN 
                    products p ON o.product_id = p.ID
               WHERE 
                    o.status = 'completed'
               GROUP BY 
                    p.ProductName";

$salesResult = $conn->query($salesQuery);

$salesData = [];
$salesLabels = [];

if ($salesResult->num_rows > 0) {
    while ($row = $salesResult->fetch_assoc()) {
        $salesLabels[] = $row['ProductName'];
        $salesData[] = $row['total_revenue'];
    }
}

// Fetching Stock Data (Existing Product Quantities)
$stockQuery = "SELECT 
                   ProductName, 
                   Quantity 
               FROM 
                   products";
$stockResult = $conn->query($stockQuery);

$stockData = [];
$stockLabels = [];

if ($stockResult->num_rows > 0) {
    while ($row = $stockResult->fetch_assoc()) {
        $stockLabels[] = $row['ProductName'];
        $stockData[] = $row['Quantity'];
    }
}

// Prepare the final response in JSON format
$data = [
    'sales' => [
        'labels' => $salesLabels,
        'data' => $salesData,
    ],
    'stock' => [
        'labels' => $stockLabels,
        'data' => $stockData,
    ],
];

echo json_encode($data);

$conn->close();
?>
