<?php
// Small helpers shared by every page. Needs $pdo from config/database.php.

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function formatPrice($price) {
    return 'NPR ' . number_format((float) $price, 2);
}

function getProduct($pdo, $id) {
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([(int) $id]);
    return $stmt->fetch();
}

// Session cart is [product_id => quantity]. Prices are always re-read
// from the database at checkout time, never trusted from the browser.
function getCart() {
    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    return $_SESSION['cart'];
}

function getCartCount() {
    $total = 0;
    foreach (getCart() as $qty) {
        $total += (int) $qty;
    }
    return $total;
}

// Only allow redirecting back to a page inside this app.
function safeRedirect($default) {
    $redirect = $_POST['redirect'] ?? $_GET['redirect'] ?? $default;
    if (!preg_match('#^[a-zA-Z0-9_\-./]+\.php(\?[^\s]*)?$#', $redirect)) {
        $redirect = $default;
    }
    return $redirect;
}
