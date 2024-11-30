<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FreshMart Inventory</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <h2>Inventory Stock Levels</h2>
  
  <form method="POST" action="">
    <table class="table table-bordered">
      <thead class="thead-dark">
        <tr>
          <th scope="col">Product</th>
          <th scope="col">Current Stock</th>
          <th scope="col">Reorder Threshold</th>
          <th scope="col">Supplier Email</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Product 1</td>
          <td><input type="number" class="form-control" name="stock[]" value="10"></td>
          <td><input type="number" class="form-control" name="threshold[]" value="20"></td>
          <td><input type="email" class="form-control" name="supplier_email[]" value="supplier1@example.com"></td>
        </tr>
        <tr>
          <td>Product 2</td>
          <td><input type="number" class="form-control" name="stock[]" value="25"></td>
          <td><input type="number" class="form-control" name="threshold[]" value="15"></td>
          <td><input type="email" class="form-control" name="supplier_email[]" value="supplier2@example.com"></td>
        </tr>
      </tbody>
    </table>
    <button type="submit" class="btn btn-primary">Check Reorder</button>
  </form>

  <?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Get form data
      $stocks = $_POST['stock'];
      $thresholds = $_POST['threshold'];
      $supplier_emails = $_POST['supplier_email'];
      
      // Loop through the products to check if they are below the reorder threshold
      for ($i = 0; $i < count($stocks); $i++) {
          $current_stock = intval($stocks[$i]);
          $reorder_threshold = intval($thresholds[$i]);
          $supplier_email = $supplier_emails[$i];
          
          // Check if current stock is below reorder threshold
          if ($current_stock < $reorder_threshold) {
              // Send email notification to supplier
              $subject = "Reorder Notification";
              $message = "The stock of your product is below the reorder threshold. Current stock: $current_stock. Please send additional stock.";
              $headers = "From: inventory@example.com";
              
              // Uncomment the following line to send the email in a live environment
              // mail($supplier_email, $subject, $message, $headers);
              
              echo "<p>Email notification sent to supplier at $supplier_email for reorder of Product " . ($i + 1) . ".</p>";
          } else {
              echo "<p>Stock is sufficient for Product " . ($i + 1) . ".</p>";
          }
      }
  }
  ?>

</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
