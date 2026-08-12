<?php
session_start();
require_once __DIR__ . '/../../config/database.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in.']);
    exit;
}

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // [productId => qty]
}

function buildCartResponse(PDO $pdo, array $cart): array {
    if (empty($cart)) return ['items' => [], 'subtotal' => 0, 'count' => 0];

    $ids = array_keys($cart);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT id, name, price, image FROM products WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $products = $stmt->fetchAll();

    $items = [];
    $subtotal = 0.0;
    $count = 0;
    foreach ($products as $p) {
        $qty = (int) $cart[$p['id']];
        $items[] = [
            'id' => $p['id'],
            'name' => $p['name'],
            'price' => (float) $p['price'],
            'img' => $p['image'],
            'qty' => $qty,
        ];
        $subtotal += (float) $p['price'] * $qty;
        $count += $qty;
    }
    return ['items' => $items, 'subtotal' => round($subtotal, 2), 'count' => $count];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $body = json_decode(file_get_contents('php://input'), true) ?? [];
    $action = $body['action'] ?? '';
    $id = (string) ($body['id'] ?? '');

    if ($action === 'add' && $id !== '') {
        $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
    } elseif ($action === 'remove' && $id !== '') {
        unset($_SESSION['cart'][$id]);
    } elseif ($action === 'setQty' && $id !== '') {
        $qty = max(1, (int) ($body['qty'] ?? 1));
        if (isset($_SESSION['cart'][$id])) $_SESSION['cart'][$id] = $qty;
    } elseif ($action === 'clear') {
        $_SESSION['cart'] = [];
    }
}

echo json_encode(buildCartResponse($pdo, $_SESSION['cart']));
