<?php
/* Add/remove/set-quantity for the session cart. Plain HTML forms POST
   here (no fetch/JSON) and this redirects straight back to whichever
   page the form was submitted from. */
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/login.php');
    exit;
}

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // [productId => qty]
}

$action = $_POST['action'] ?? '';
$id = (string) ($_POST['id'] ?? '');

if ($action === 'add' && $id !== '') {
    $qty = max(1, (int) ($_POST['qty'] ?? 1));
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $qty;
} elseif ($action === 'remove' && $id !== '') {
    unset($_SESSION['cart'][$id]);
} elseif ($action === 'setQty' && $id !== '') {
    $qty = max(1, (int) ($_POST['qty'] ?? 1));
    if (isset($_SESSION['cart'][$id])) $_SESSION['cart'][$id] = $qty;
}

// Only allow redirecting back to a page within this app — never to an
// attacker-supplied external URL.
$redirect = $_POST['redirect'] ?? '../pages/index.php';
if (!preg_match('#^[a-zA-Z0-9_\-./]+\.php(\?[^\s]*)?$#', $redirect)) {
    $redirect = '../pages/index.php';
}

header('Location: ' . $redirect);
exit;
