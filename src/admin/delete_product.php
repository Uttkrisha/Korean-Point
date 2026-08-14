<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$id = (int) ($_POST['id'] ?? 0);

if ($id > 0) {
    try {
        dbExec($conn, 'DELETE FROM products WHERE id = ?', 'i', [$id]);
    } catch (mysqli_sql_exception $e) {
        // Products referenced by past order_items can't be deleted (no ON DELETE CASCADE on that FK).
        header('Location: products.php?delete_error=' . urlencode('Cannot delete: this product is part of an existing order.'));
        exit;
    }
}

header('Location: products.php');
exit;
