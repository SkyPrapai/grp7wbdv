<?php
session_start();
include 'db_connection.php';

$order_number = isset($_GET['order_number']) ? $_GET['order_number'] : 'N/A';
$total_price = 0;

// Calculate total_price by retrieving order items before generating the PayPal form
$sql_items = "SELECT order_items.quantity, order_items.price
              FROM order_items
              JOIN orders ON order_items.order_id = orders.order_id
              WHERE orders.order_number = ?";
$stmt_items = $conn->prepare($sql_items);
if ($stmt_items === false) {
    die("Failed to prepare statement: " . htmlspecialchars($conn->error));
}
$stmt_items->bind_param("s", $order_number);
$stmt_items->execute();
$stmt_items->bind_result($quantity, $price);
while ($stmt_items->fetch()) {
    $subtotal = $price * $quantity;
    $total_price += $subtotal; // Accumulate total price
}
$stmt_items->close();

// Ensure payment method is retrieved from the session
$payment_method = isset($_SESSION['payment_method']) ? $_SESSION['payment_method'] : 'N/A';
if ($payment_method == "PayPal") {
    $business_email = 'upliftpagebookstore07@gmail.com'; // Your business PayPal email
    $return_url = 'http://localhost/WBDV_GRP7/process_payment.php'; // URL to return after successful payment
    $cancel_url = 'http://yourwebsite.com/payment.php'; // URL if payment is cancelled

    // PayPal Redirection Form
    echo '
    <form id="paypal_form" action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
        <input type="hidden" name="cmd" value="_xclick">
        <input type="hidden" name="business" value="' . $business_email . '">
        <input type="hidden" name="item_name" value="Purchase from Uplift Page Bookstore">
        <input type="hidden" name="amount" value="' . number_format($total_price, 2, '.', '') . '">
        <input type="hidden" name="currency_code" value="PHP">
        <input type="hidden" name="return" value="' . $return_url . '">
        <input type="hidden" name="cancel_return" value="' . $cancel_url . '">
    </form>
    <script type="text/javascript">
        document.getElementById("paypal_form").submit(); // Auto-submit the form
    </script>';
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation | Uplift Bookstore</title>
    <link rel="stylesheet" href="orderconfirmation_styles.css"> <!-- Link to your CSS -->
</head>
<body>
    <div class="confirmation-container">
        <h2>Uplift Page Bookstore</h2>
        <p>120 MacArthur Hwy, Valenzuela, 1440 Metro Manila</p>
        <p>Phone: (02) 1234 5678</p>
        <h3>Customer Receipt</h3>
        <hr>
        <p><strong>Order Number:</strong> <?php echo htmlspecialchars($order_number); ?></p>
        <p><strong>Customer Name:</strong> <?php echo htmlspecialchars($_SESSION['firstname']) . ' ' . htmlspecialchars($_SESSION['lastname']); ?></p>
        <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($_SESSION['phone_number']); ?></p>
        <p><strong>Shipping Address:</strong> <?php echo htmlspecialchars($_SESSION['address']); ?></p>
        <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($payment_method); ?></p>
        <hr>
        <table>
            <tr>
                <th>Book Title</th>
                <th>Quantity</th>
                <th>Price (₱)</th>
                <th>Subtotal (₱)</th>
            </tr>
            <?php
            // Retrieve order items from the database
            $sql_items = "SELECT books.book_title, order_items.quantity, order_items.price
                          FROM order_items
                          JOIN books ON order_items.book_id = books.id
                          JOIN orders ON order_items.order_id = orders.order_id
                          WHERE orders.order_number = ?";
            $stmt_items = $conn->prepare($sql_items);
            if ($stmt_items === false) {
                die("Failed to prepare statement: " . htmlspecialchars($conn->error));
            }
            $stmt_items->bind_param("s", $order_number);
            $stmt_items->execute();
            $stmt_items->store_result(); // Store the result set
            $stmt_items->bind_result($book_title, $quantity, $price);
            if ($stmt_items->num_rows > 0) {
                while ($stmt_items->fetch()) {
                    $subtotal = $price * $quantity;
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($book_title) . "</td>";
                    echo "<td>" . htmlspecialchars($quantity) . "</td>";
                    echo "<td>₱" . number_format($price, 2) . "</td>";
                    echo "<td>₱" . number_format($subtotal, 2) . "</td>";
                    echo "</tr>";
                    $total_price += $subtotal; // Accumulate total price
                }
            } else {
                echo "<tr><td colspan='4'>No order items found. Check if the order ID is correct.</td></tr>";
            }
            $stmt_items->close();
            ?>
            <tr>
                <td colspan='2' class='total'>Total</td>
                <td>₱<?php echo number_format($total_price, 2); ?></td>
            </tr>
        </table>
        <hr>
        <p>Thank you for shopping with Uplift Page Bookstore!</p>
        <p>This is a computer-generated receipt and does not require a signature.</p>
        <p><a href="homepage.php" class="return-home">Return to Home</a></p>
    </div>
</body>
</html>
