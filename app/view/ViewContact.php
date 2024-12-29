<?php
class ViewContact {
    public function renderContactForm() {
        // Start the session and check for any session messages
        session_start();

        $message_html = '';
        if (isset($_SESSION['message'])) {
            // Get the message and type
            $message = $_SESSION['message'];
            $message_type = $_SESSION['message_type'];

            // Set the message color based on the type
            $color = ($message_type == 'success') ? 'green' : 'red';

            // Prepare the message HTML
            $message_html = "<p style='color: $color; font-weight: bold; text-align: center;'>$message</p>";

            // Clear the session message after displaying it
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
        }

        // Include the navbar from an external file
        ob_start();
        require_once __DIR__ . '/../../navbar.php';
        $navbar_html = ob_get_clean();

        return '
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Customer Support & Help Center</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
            <style>
                body {
                    font-family: Arial, sans-serif;
                }

                .hero-section {
                    background: linear-gradient(to right, #A4B5C4, #F5F5F5);
                    padding: 50px 0;
                    text-align: center;
                    color: #fff;
                }

                .hero-section h1 {
                    font-size: 3rem;
                }

                .form-section,
                .faq-section {
                    padding: 40px 20px;
                }

                .faq-category {
                    margin-bottom: 30px;
                }

                .faq-category h5 {
                    margin-bottom: 15px;
                }

                .feedback-message {
                    color: green;
                    font-weight: bold;
                    margin-bottom: 20px;
                }
            </style>
        </head>

        <body>
            ' . $navbar_html . ' <!-- Include the navbar HTML -->
            ' . $message_html . ' <!-- Display session message here -->

            <!-- Hero Section -->
            <section class="hero-section">
                <div class="container">
                    <h1>Customer Support & Help Center</h1>
                    <p>We are here to help! Contact us or find answers to your questions below.</p>
                </div>
            </section>

            <!-- Contact Form Section -->
            <section class="form-section">
                <div class="container">
                    <form action="http://localhost/Management-Inventory/app/controller/ContactController.php" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Your Name</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter your name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Your Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" name="subject" id="subject" class="form-control" placeholder="Subject" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Your Message</label>
                            <textarea name="message" id="message" class="form-control" placeholder="Write your message here" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send Message</button>
                    </form>
                </div>
            </section>

            <!-- FAQ Section -->
            <section class="faq-section">
                <div class="container">
                    <div class="row">
                        <!-- Shipping Category -->
                        <div class="col-md-4 faq-category">
                            <h5>Shipping</h5>
                            <ul class="list-unstyled">
                                <li><a href="#" class="text-dark">How long does shipping take?</a></li>
                                <li><a href="#" class="text-dark">Can I track my order?</a></li>
                                <li><a href="#" class="text-dark">Do you offer international shipping?</a></li>
                            </ul>
                        </div>

                        <!-- Returns Category -->
                        <div class="col-md-4 faq-category">
                            <h5>Returns</h5>
                            <ul class="list-unstyled">
                                <li><a href="#" class="text-dark">What is your return policy?</a></li>
                                <li><a href="#" class="text-dark">How do I return an item?</a></li>
                                <li><a href="#" class="text-dark">When will I get my refund?</a></li>
                            </ul>
                        </div>

                        <!-- Orders Category -->
                        <div class="col-md-4 faq-category">
                            <h5>Orders</h5>
                            <ul class="list-unstyled">
                                <li><a href="#" class="text-dark">How do I track my order?</a></li>
                                <li><a href="#" class="text-dark">How do I modify my order?</a></li>
                                <li><a href="#" class="text-dark">Can I cancel my order?</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <footer class="bg-light text-center py-3">
                <p>&copy; 2024 Inventory Management System. All rights reserved.</p>
            </footer>

        </body>
        </html>';
    }
}
?>
