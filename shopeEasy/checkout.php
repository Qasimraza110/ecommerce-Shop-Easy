<?php
session_start();
require_once 'includes/header.php';
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Get cart items
$stmt = $conn->prepare("
    SELECT c.*, p.price, p.name
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$cart_items = $result->fetch_all(MYSQLI_ASSOC);

if (empty($cart_items)) {
    $_SESSION['error'] = "Your cart is empty.";
    header('Location: cart.php');
    exit();
}

// Calculate total
$total = 0;
foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}

// If form submitted, process order
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shipping_address = trim($_POST['shipping_address'] ?? '');
    $shipping_city = trim($_POST['shipping_city'] ?? '');
    $shipping_state = trim($_POST['shipping_state'] ?? '');
    $shipping_zip = trim($_POST['shipping_zip'] ?? '');
    $shipping_country = trim($_POST['shipping_country'] ?? '');
    $payment_method = trim($_POST['payment_method'] ?? '');

    // Basic validation
    if ($shipping_address && $shipping_city && $shipping_state && $shipping_zip && $shipping_country && $payment_method) {
        // Create order
        $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, shipping_address, shipping_city, shipping_state, shipping_zip, shipping_country, payment_method) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("dsssssss", $user_id, $total, $shipping_address, $shipping_city, $shipping_state, $shipping_zip, $shipping_country, $payment_method);
        $stmt->execute();
        $order_id = $conn->insert_id;

        // Insert order items
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        foreach ($cart_items as $item) {
            $stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
            $stmt->execute();
        }

        // Clear cart
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        // Redirect to order confirmation
        header('Location: order_confirmation.php?id=' . $order_id);
        exit();
    } else {
        $error = "Please fill all fields.";
    }
}
?>

<div class="container mt-5">
    <h1 class="mb-4">Checkout</h1>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <h4>Your Cart</h4>
    <table class="table mb-4">
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cart_items as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                <td><strong>$<?php echo number_format($total, 2); ?></strong></td>
            </tr>
        </tfoot>
    </table>

    <h4>Shipping & Payment</h4>
    <form method="POST" class="row g-3">
        <div class="col-md-6">
            <label for="shipping_address" class="form-label">Address</label>
            <input type="text" class="form-control" id="shipping_address" name="shipping_address" required>
        </div>
        <div class="col-md-6">
            <label for="shipping_city" class="form-label">City</label>
            <input type="text" class="form-control" id="shipping_city" name="shipping_city" required>
        </div>
        <div class="col-md-4">
            <label for="shipping_state" class="form-label">State</label>
            <input type="text" class="form-control" id="shipping_state" name="shipping_state" required>
        </div>
        <div class="col-md-4">
            <label for="shipping_zip" class="form-label">ZIP Code</label>
            <input type="text" class="form-control" id="shipping_zip" name="shipping_zip" required>
        </div>
        <div class="col-md-4">
            <label for="shipping_country" class="form-label">Country</label>
            <input type="text" class="form-control" id="shipping_country" name="shipping_country" required>
        </div>
        <div class="col-md-12">
            <label class="form-label">Payment Method</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="payment_method" id="visa" value="Visa" required>
                <label class="form-check-label" for="visa">Visa (Card Payment)</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="payment_method" id="jazzcash" value="JazzCash" required>
                <label class="form-check-label" for="jazzcash">JazzCash</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="payment_method" id="easypaisa" value="Easypaisa" required>
                <label class="form-check-label" for="easypaisa">Easypaisa</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="payment_method" id="cod" value="Cash on Delivery" required>
                <label class="form-check-label" for="cod">Cash on Delivery</label>
            </div>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-success">Place Order</button>
        </div>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?> 