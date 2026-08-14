<?php
// Add/remove/set-quantity for the session cart. Plain HTML forms POST
// here (no fetch/JSON) and this redirects straight back to whichever
// page the form was submitted from.
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isLoggedIn()) {
    header('Location: ../pages/login.php');
    exit;
}

$cart = getCart();

$action = $_POST['action'] ?? '';
$id = (string) ($_POST['id'] ?? '');

if ($action === 'add' && $id !== '') {
    $qty = max(1, (int) ($_POST['qty'] ?? 1));
    $cart[$id] = ($cart[$id] ?? 0) + $qty;
} elseif ($action === 'remove' && $id !== '') {
    unset($cart[$id]);
} elseif ($action === 'setQty' && $id !== '') {
    $qty = max(1, (int) ($_POST['qty'] ?? 1));
    if (isset($cart[$id])) {
        $cart[$id] = $qty;
    }
}

$_SESSION['cart'] = $cart;

header('Location: ' . safeRedirect('../pages/index.php'));
exit;
