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
$items = getCartItems($conn, $userId);
if (!$items) {
    failCheckout($redirectBase, 'Your cart is empty.');
}

$total = 0.0;
foreach ($items as $item) {
    $total += (float) $item['price'] * (int) $item['quantity'];
}

try {
    $conn->begin_transaction();

    dbExec(
        $conn,
        'INSERT INTO orders (user_id, total_amount, shipping_address, payment_method) VALUES (?, ?, ?, ?)',
        'idss',
        [$userId, $total, $shippingAddress, $paymentMethod]
    );
    $orderId = $conn->insert_id;

    foreach ($items as $item) {
        dbExec(
            $conn,
            'INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)',
            'iiid',
            [$orderId, $item['id'], $item['quantity'], $item['price']]
        );

        // Only decrements if enough stock is still available — guards
        // against two people buying the last unit at the same time.
        $stockStmt = dbExec(
            $conn,
            'UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?',
            'iii',
            [$item['quantity'], $item['id'], $item['quantity']]
        );
        if ($stockStmt->affected_rows === 0) {
            throw new Exception('Not enough stock for ' . $item['name'] . '.');
        }
    }

    dbExec($conn, 'DELETE FROM cart WHERE user_id = ?', 'i', [$userId]);

    $conn->commit();
} catch (mysqli_sql_exception $e) {
    $conn->rollback();
    failCheckout($redirectBase, 'Could not place order. Please try again.');
} catch (Exception $e) {
    $conn->rollback();
    failCheckout($redirectBase, $e->getMessage());
}

header('Location: ../pages/order-success.php?id=' . urlencode($orderId));
exit;
