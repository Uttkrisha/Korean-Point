<?php
// Add/remove/set-quantity in the `cart` table. Plain HTML forms POST
// here (no fetch/JSON) and this redirects straight back to whichever
// page the form was submitted from.
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isLoggedIn()) {
    header('Location: ../pages/login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';
$productId = (int) ($_POST['id'] ?? 0);

if ($action === 'add' && $productId > 0) {
    $qty = max(1, (int) ($_POST['qty'] ?? 1));

    $existing = dbQuery($conn, 'SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?', 'ii', [$userId, $productId])->fetch_assoc();

    if ($existing) {
        dbExec($conn, 'UPDATE cart SET quantity = ? WHERE id = ?', 'ii', [$existing['quantity'] + $qty, $existing['id']]);
    } else {
        dbExec($conn, 'INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)', 'iii', [$userId, $productId, $qty]);
    }
} elseif ($action === 'remove' && $productId > 0) {
    dbExec($conn, 'DELETE FROM cart WHERE user_id = ? AND product_id = ?', 'ii', [$userId, $productId]);
} elseif ($action === 'setQty' && $productId > 0) {
    $qty = max(1, (int) ($_POST['qty'] ?? 1));
    dbExec($conn, 'UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?', 'iii', [$qty, $userId, $productId]);
}

header('Location: ' . safeRedirect('../pages/index.php'));
exit;
