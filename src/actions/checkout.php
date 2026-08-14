<?php
// Checkout — a plain HTML form POSTs here (no fetch/JSON). Prices are
// always read fresh from MySQL using the cart table; the browser is
// never trusted with what anything costs. On success this redirects to
// order-success.php; on failure it redirects back with an error message
// in the query string.
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$redirectBase = safeRedirect('../pages/cart.php');

function failCheckout($redirectBase, $message) {
    $separator = str_contains($redirectBase, '?') ? '&' : '?';
    header('Location: ' . $redirectBase . $separator . 'checkout_error=' . urlencode($message));
    exit;
}

if (!isLoggedIn()) {
    header('Location: ../pages/login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$shippingAddress = trim($_POST['shipping_address'] ?? '');
$paymentMethod = trim($_POST['payment_method'] ?? '');

if (!$shippingAddress || !$paymentMethod) {
    failCheckout($redirectBase, 'Please fill in every field.');
}

// Prices always come from the database, never from the browser.
$items = getCartItems($pdo, $userId);
if (!$items) {
    failCheckout($redirectBase, 'Your cart is empty.');
}

$total = 0.0;
foreach ($items as $item) {
    $total += (float) $item['price'] * (int) $item['quantity'];
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare('INSERT INTO orders (user_id, total_amount, shipping_address, payment_method) VALUES (?, ?, ?, ?)');
    $stmt->execute([$userId, $total, $shippingAddress, $paymentMethod]);
    $orderId = $pdo->lastInsertId();

    $itemStmt = $pdo->prepare('INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)');
    foreach ($items as $item) {
        $itemStmt->execute([$orderId, $item['id'], $item['quantity'], $item['price']]);
    }

    $pdo->prepare('DELETE FROM cart WHERE user_id = ?')->execute([$userId]);

    $pdo->commit();
} catch (Exception $e) {
    $pdo->rollBack();
    failCheckout($redirectBase, 'Could not place order. Please try again.');
}

header('Location: ../pages/order-success.php?id=' . urlencode($orderId));
exit;
