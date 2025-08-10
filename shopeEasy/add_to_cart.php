<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Check if product_id and quantity are provided
if (!isset($_POST['product_id']) || !isset($_POST['quantity'])) {
    header('Location: products.php');
    exit();
}

$product_id = $_POST['product_id'];
$quantity = (int)$_POST['quantity'];
$user_id = $_SESSION['user_id'];

// Validate quantity
if ($quantity < 1) {
    $_SESSION['error'] = "Invalid quantity.";
    header('Location: products.php');
    exit();
}

// Check if product exists and has enough stock
$stmt = $conn->prepare("SELECT stock FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    $_SESSION['error'] = "Product not found.";
    header('Location: products.php');
    exit();
}

if ($product['stock'] < $quantity) {
    $_SESSION['error'] = "Not enough stock available.";
    header('Location: products.php');
    exit();
}

// Check if product is already in cart
$stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$result = $stmt->get_result();
$cart_item = $result->fetch_assoc();

if ($cart_item) {
    // Update quantity if product is already in cart
    $new_quantity = $cart_item['quantity'] + $quantity;
    if ($new_quantity > $product['stock']) {
        $_SESSION['error'] = "Not enough stock available.";
        header('Location: products.php');
        exit();
    }
    
    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
    $stmt->bind_param("ii", $new_quantity, $cart_item['id']);
    $stmt->execute();
} else {
    // Add new item to cart
    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $user_id, $product_id, $quantity);
    $stmt->execute();
}

$_SESSION['success'] = "Product added to cart successfully.";
header('Location: cart.php');
exit(); 