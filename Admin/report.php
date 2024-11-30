<?php
// Include database connection file
include '../db.php'; 
include 'AdminNavbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- For charts -->
    <title>Admin Reports</title>
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Reports Dashboard</h1>


        <!-- Charts Section -->
        <div class="row mt-5">
            <div class="col-md-6">
                <h3>Sales Overview</h3>
                <canvas id="salesChart"></canvas> <!-- Chart Placeholder -->
            </div>
            <div class="col-md-6">
                <h3>Stock Overview</h3>
                <canvas id="stockChart"></canvas> <!-- Canvas for stock chart -->
            </div>
        </div>
    </div>

    <script>
// After fetching the stock data
$.getJSON('report_data.php', function(data) { //AJAX is used to fetch data from the server without reloading the entire web page
    // Sales Chart
    var salesCtx = document.getElementById('salesChart').getContext('2d');
    var salesChart = new Chart(salesCtx, {
        type: 'bar',
        data: {
            labels: data.sales.labels,
            datasets: [{
                label: 'Sales Revenue',
                data: data.sales.data,
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

    // Stock Chart
    var stockCtx = document.getElementById('stockChart').getContext('2d');
    var stockChart = new Chart(stockCtx, {
        type: 'bar',
        data: {
            labels: data.stock.labels,
            datasets: [{
                label: 'Stock Levels',
                data: data.stock.data,
                backgroundColor: 'rgba(255, 206, 86, 0.6)',
                borderColor: 'rgba(255, 206, 86, 1)',
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

    // Check for low stock levels
    data.stock.data.forEach((quantity, index) => {
        if (quantity <= 10) {
            alert("Warning: Low stock for " + data.stock.labels[index] + "! Only " + quantity + " left.");
        }
    });
});

    </script>

</body>
</html>
