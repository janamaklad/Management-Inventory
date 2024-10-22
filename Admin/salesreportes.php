<?php
// Include database connection file
include '../db.php';  // Ensure db.php contains the $conn variable

// Initialize arrays for Chart.js data
$salesData = [];
$salesLabels = [];

// Fetch sales report data for the current month
$sql = "SELECT o.product_id, p.ProductName, o.quantity, p.price, o.quantity * p.price AS total_sales, MONTHNAME(o.order_date) as month
        FROM orders o
        JOIN products p ON o.product_id = p.id
        WHERE MONTH(o.order_date) = MONTH(CURDATE())";
$result = $conn->query($sql);

// Check for SQL errors
if ($conn->error) {
    die("SQL Error: " . $conn->error);
}

// Start HTML output
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Sales Report</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Include Chart.js -->
</head>
<body>
    <div class="container">
        <h1 class="my-4">Sales Report for <?php echo date('F Y'); ?></h1>

        <?php
        // Check if data exists
        if ($result->num_rows > 0) {
            echo "<table class='table table-bordered table-striped'>";
            echo "<thead class='thead-dark'><tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total Sales</th>
                  </tr></thead><tbody>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['ProductName']}</td>
                        <td>{$row['quantity']}</td>
                        <td>{$row['price']}</td>
                        <td>{$row['total_sales']}</td>
                      </tr>";
                // Add data to arrays for Chart.js
                $salesLabels[] = $row['ProductName'];
                $salesData[] = $row['total_sales'];
            }
            echo "</tbody></table>";
        } else {
            echo "<div class='alert alert-warning'>No data found for the sales report.</div>";
        }
        ?>

        <!-- Canvas for Chart.js -->
        <h2 class="my-4">Sales Chart</h2>
        <canvas id="salesChart"></canvas>
    </div>

    <!-- Add Bootstrap JS and jQuery for full functionality -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Chart.js code to display the chart -->
    <script>
    var ctx = document.getElementById('salesChart').getContext('2d');
    var salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($salesLabels); ?>, // X-axis labels
            datasets: [{
                label: 'Total Sales',
                data: <?php echo json_encode($salesData); ?>, // Y-axis data
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    </script>
</body>
</html>
