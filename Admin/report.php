<?php
include '../db.php';
include 'AdminNavBar.php';
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
        
        <!-- Filtering and Search -->
        <div class="row mb-3">
            <div class="col-md-4">
                <input type="text" class="form-control" id="searchInput" placeholder="Search Reports">
            </div>
            <div class="col-md-4">
                <input type="date" class="form-control" id="startDate">
            </div>
            <div class="col-md-4">
                <input type="date" class="form-control" id="endDate">
            </div>
        </div>

        <!-- Reports Table -->
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered" id="reportsTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Report Type</th>
                            <th>Date</th>
                            <th>Details</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Sample Data -->
                        <tr>
                            <td>1</td>
                            <td>Sales Report</td>
                            <td>2024-10-20</td>
                            <td>Monthly sales report for September</td>
                            <td><a href="#" class="btn btn-primary btn-sm">View</a></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Inventory Report</td>
                            <td>2024-10-19</td>
                            <td>Inventory stock level as of October</td>
                            <td><a href="#" class="btn btn-primary btn-sm">View</a></td>
                        </tr>
                        <!-- Add more rows here dynamically -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row mt-5">
            <div class="col-md-6">
                <h3>Sales Overview</h3>
                <canvas id="salesChart"></canvas> <!-- Chart Placeholder -->
            </div>
            <div class="col-md-6">
                <h3>Inventory Overview</h3>
                <canvas id="inventoryChart"></canvas> <!-- Chart Placeholder -->
            </div>
        </div>
    </div>

    <script>
        // Chart.js example
        var salesCtx = document.getElementById('salesChart').getContext('2d');
        var salesChart = new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                datasets: [{
                    label: 'Sales',
                    data: [100, 200, 150, 300, 250, 400, 350],
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            }
        });

        // Inventory Chart Example
        var inventoryCtx = document.getElementById('inventoryChart').getContext('2d');
        var inventoryChart = new Chart(inventoryCtx, {
            type: 'bar',
            data: {
                labels: ['Product A', 'Product B', 'Product C', 'Product D'],
                datasets: [{
                    label: 'Stock',
                    data: [50, 100, 75, 125],
                    backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 206, 86, 0.2)', 'rgba(75, 192, 192, 0.2)'],
                    borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)'],
                    borderWidth: 1
                }]
            }
        });
    </script>
</body>
</html>
