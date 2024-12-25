<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Checkout</h2>

        <!-- Payment Form Card -->
        <div class="card mx-auto" style="max-width: 600px;">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Payment Details</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="process_payment.php">
                    <div class="mb-3">
                        <label for="cardName" class="form-label">Cardholder Name</label>
                        <input type="text" class="form-control" id="cardName" name="card_name" placeholder="Enter Cardholder Name" required>
                    </div>
                    <div class="mb-3">
                        <label for="cardNumber" class="form-label">Card Number</label>
                        <input type="text" class="form-control" id="cardNumber" name="card_number" maxlength="16" placeholder="Enter Card Number" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="expiryDate" class="form-label">Expiry Date</label>
                            <input type="month" class="form-control" id="expiryDate" name="expiry_date" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cvv" class="form-label">CVV</label>
                            <input type="text" class="form-control" id="cvv" name="cvv" maxlength="3" placeholder="Enter CVV" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Complete Payment</button>
                </form>
            </div>
        </div>

        <!-- Back to Cart Button -->
        <div class="mt-4 text-center">
            <a href="cart.php" class="btn btn-secondary">Back to Cart</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
