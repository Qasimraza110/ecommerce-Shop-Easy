<?php
require_once 'includes/header.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit;
}

// Check if order ID is provided
if (!isset($_GET['id'])) {
    header("location: index.php");
    exit;
}

$order_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Get order details
$sql = "SELECT o.*, u.username, u.email 
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        WHERE o.id = ? AND o.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("location: index.php");
    exit;
}

$order = $result->fetch_assoc();

// Get order items
$sql = "SELECT oi.*, p.name, p.image_url 
        FROM order_items oi 
        JOIN products p ON oi.product_id = p.id 
        WHERE oi.order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items = $stmt->get_result();
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    <h2 class="mt-3">Thank You for Your Order!</h2>
                    <p class="lead">Your order has been successfully placed.</p>
                    <p>Order #<?php echo $order_id; ?></p>
                </div>
            </div>
            
            <div class="card shadow mt-4">
                <div class="card-body">
                    <h4 class="card-title mb-4">Order Details</h4>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Shipping Information</h5>
                            <p>
                                <?php echo htmlspecialchars($order['shipping_address']); ?><br>
                                <?php echo htmlspecialchars($order['shipping_city']); ?>, 
                                <?php echo htmlspecialchars($order['shipping_state']); ?> 
                                <?php echo htmlspecialchars($order['shipping_zip']); ?><br>
                                <?php echo htmlspecialchars($order['shipping_country']); ?>
                            </p>
                        </div>
                        
                        <div class="col-md-6">
                            <h5>Payment Information</h5>
                            <p>
                                Payment Method: <?php echo ucfirst($order['payment_method']); ?><br>
                                Order Status: <?php echo ucfirst($order['status']); ?><br>
                                Order Date: <?php echo date('F j, Y', strtotime($order['created_at'])); ?>
                            </p>
                        </div>
                    </div>
                    
                    <h5 class="mb-3">Order Items</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($item = $items->fetch_assoc()): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php if ($item['image_url']): ?>
                                                    <img src="<?php echo $item['image_url']; ?>" alt="<?php echo $item['name']; ?>" 
                                                         class="img-thumbnail me-2" style="width: 50px;">
                                                <?php endif; ?>
                                                <?php echo $item['name']; ?>
                                            </div>
                                        </td>
                                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                    <td>$<?php echo number_format($order['total_amount'] - 10 - ($order['total_amount'] * 0.1), 2); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Shipping:</strong></td>
                                    <td>$10.00</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Tax:</strong></td>
                                    <td>$<?php echo number_format($order['total_amount'] * 0.1, 2); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td><strong>$<?php echo number_format($order['total_amount'], 2); ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div class="d-grid gap-2 mt-4">
                        <a href="index.php" class="btn btn-primary">Continue Shopping</a>
                        <a href="profile.php" class="btn btn-outline-secondary">View Order History</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?> 