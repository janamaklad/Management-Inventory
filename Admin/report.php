<?php
// Include database connection file
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
                        <?php
                        // Example data fetch for sales reports
                        $sql = "SELECT id, report_type, report_date, details FROM reports";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                    <td>{$row['id']}</td>
                                    <td>{$row['report_type']}</td>
                                    <td>{$row['report_date']}</td>
                                    <td>{$row['details']}</td>
                                    <td><a href='#' class='btn btn-primary btn-sm'>View</a></td>
                                  </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No reports found.</td></tr>";
                        }
                        ?>
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
                <h3>Stock Overview</h3>
                <canvas id="stockChart"></canvas> <!-- Canvas for stock chart -->
            </div>
        </div>
    </div>

    <script>
    // Fetch the data from report_data.php
    $.getJSON('report_data.php', function(data) {
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
    });
    </script>

</body>
</html>
