<?php
// Small helpers shared by every page. Needs $pdo from config/database.php.

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function formatPrice($price) {
    return '$' . number_format((float) $price, 2);
}

function getProduct($pdo, $id) {
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([(int) $id]);
    return $stmt->fetch();
}

// Cart lives in the `cart` table (user_id, product_id, quantity) —
// prices are always re-read from products at checkout time, never
// trusted from the browser.
function getCartItems($pdo, $userId) {
    $stmt = $pdo->prepare(
        'SELECT c.id AS cart_id, c.quantity, p.* FROM cart c
         JOIN products p ON c.product_id = p.id
         WHERE c.user_id = ?'
    );
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}

function getCartCount($pdo, $userId) {
    $stmt = $pdo->prepare('SELECT SUM(quantity) FROM cart WHERE user_id = ?');
    $stmt->execute([$userId]);
    return (int) $stmt->fetchColumn();
}

// Only allow redirecting back to a page inside this app.
function safeRedirect($default) {
    $redirect = $_POST['redirect'] ?? $_GET['redirect'] ?? $default;
    if (!preg_match('#^[a-zA-Z0-9_\-./]+\.php(\?[^\s]*)?$#', $redirect)) {
        $redirect = $default;
    }
    return $redirect;
}
