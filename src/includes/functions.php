<?php
// Small helpers shared by every page. Needs $conn from config/database.php.

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Call at the top of every admin/*.php page.
function requireAdmin() {
    if (!isLoggedIn()) {
        header('Location: ../pages/login.php');
        exit;
    }
    if (!isAdmin()) {
        header('Location: ../pages/index.php');
        exit;
    }
}

function formatPrice($price) {
    return 'NPR ' . number_format((float) $price, 2);
}

// Run a SELECT with optional bound params, get back a mysqli_result.
// $types is the bind_param type string, e.g. 'si' for (string, int).
function dbQuery($conn, $sql, $types = '', $params = []) {
    $stmt = $conn->prepare($sql);
    if ($types !== '') {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    return $stmt->get_result();
}

// Run an INSERT/UPDATE/DELETE with optional bound params, get back the
// mysqli_stmt (so callers can read ->insert_id or ->affected_rows).
function dbExec($conn, $sql, $types = '', $params = []) {
    $stmt = $conn->prepare($sql);
    if ($types !== '') {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    return $stmt;
}

function getProduct($conn, $id) {
    return dbQuery($conn, 'SELECT * FROM products WHERE id = ?', 'i', [(int) $id])->fetch_assoc();
}

// Cart lives in the `cart` table (user_id, product_id, quantity) —
// prices are always re-read from products at checkout time, never
// trusted from the browser.
function getCartItems($conn, $userId) {
    $sql = 'SELECT c.id AS cart_id, c.quantity, p.* FROM cart c
            JOIN products p ON c.product_id = p.id
            WHERE c.user_id = ?';
    return dbQuery($conn, $sql, 'i', [$userId])->fetch_all(MYSQLI_ASSOC);
}

function getCartCount($conn, $userId) {
    $row = dbQuery($conn, 'SELECT SUM(quantity) AS total FROM cart WHERE user_id = ?', 'i', [$userId])->fetch_assoc();
    return (int) ($row['total'] ?? 0);
}

// Only allow redirecting back to a page inside this app.
function safeRedirect($default) {
    $redirect = $_POST['redirect'] ?? $_GET['redirect'] ?? $default;
    if (!preg_match('#^[a-zA-Z0-9_\-./]+\.php(\?[^\s]*)?$#', $redirect)) {
        $redirect = $default;
    }
    return $redirect;
}
