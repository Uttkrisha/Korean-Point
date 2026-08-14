<?php
// Checkout — a plain HTML form POSTs here (no fetch/JSON). Prices are
// always read fresh from MySQL using the session cart; the browser is
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

$cart = getCart();
if (empty($cart)) {
    failCheckout($redirectBase, 'Your cart is empty.');
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');
$city = trim($_POST['city'] ?? '');

if (!$name || !$email || !$phone || !$address || !$city) {
    failCheckout($redirectBase, 'Please fill in every field.');
}

// Prices always come from the database, never from the browser.
$ids = array_keys($cart);
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$stmt = $pdo->prepare("SELECT id, price FROM products WHERE id IN ($placeholders)");
$stmt->execute($ids);
$products = $stmt->fetchAll();

if (!$products) {
    failCheckout($redirectBase, 'Your cart items are no longer available.');
}

$total = 0.0;
foreach ($products as $p) {
    $total += (float) $p['price'] * (int) $cart[$p['id']];
}

try {
    $pdo->beginTransaction();

    $orderId = 'KP-' . time() . '-' . random_int(100, 999);
    $stmt = $pdo->prepare('INSERT INTO orders (id, user_id, total, name, email, phone, address, city, zip) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([$orderId, $_SESSION['user_id'], $total, $name, $email, $phone, $address, $city, '']);

    $itemStmt = $pdo->prepare('INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)');
    foreach ($products as $p) {
        $itemStmt->execute([$orderId, $p['id'], (int) $cart[$p['id']], $p['price']]);
    }

    $pdo->commit();
} catch (Exception $e) {
    $pdo->rollBack();
    failCheckout($redirectBase, 'Could not place order. Please try again.');
}

$_SESSION['cart'] = [];

header('Location: ../pages/order-success.php?id=' . urlencode($orderId));
exit;
