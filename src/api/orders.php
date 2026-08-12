<?php
session_start();
require_once __DIR__ . '/../../config/database.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in.']);
    exit;
}

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    http_response_code(400);
    echo json_encode(['error' => 'Your cart is empty.']);
    exit;
}

$body = json_decode(file_get_contents('php://input'), true) ?? [];
$name = trim($body['name'] ?? '');
$email = trim($body['email'] ?? '');
$address = trim($body['address'] ?? '');
$city = trim($body['city'] ?? '');
$zip = trim($body['zip'] ?? '');

if (!$name || !$email || !$address || !$city || !$zip) {
    http_response_code(400);
    echo json_encode(['error' => 'Please fill in every field.']);
    exit;
}

// Prices always come from the database, never from the browser.
$ids = array_keys($cart);
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$stmt = $pdo->prepare("SELECT id, price FROM products WHERE id IN ($placeholders)");
$stmt->execute($ids);
$products = $stmt->fetchAll();

if (!$products) {
    http_response_code(400);
    echo json_encode(['error' => 'Your cart items are no longer available.']);
    exit;
}

$total = 0.0;
foreach ($products as $p) {
    $total += (float) $p['price'] * (int) $cart[$p['id']];
}

try {
    $pdo->beginTransaction();

    $orderId = 'KP-' . time() . '-' . random_int(100, 999);
    $stmt = $pdo->prepare('INSERT INTO orders (id, user_id, total, name, email, address, city, zip) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([$orderId, $_SESSION['user_id'], $total, $name, $email, $address, $city, $zip]);

    $itemStmt = $pdo->prepare('INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)');
    foreach ($products as $p) {
        $itemStmt->execute([$orderId, $p['id'], (int) $cart[$p['id']], $p['price']]);
    }

    $pdo->commit();
} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['error' => 'Could not place order. Please try again.']);
    exit;
}

$_SESSION['cart'] = [];

echo json_encode(['id' => $orderId]);
