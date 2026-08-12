<?php
/* Shared data loader for every gated page — queries MySQL for the product
   catalog and builds the current session's cart directly in PHP. No fetch()
   calls: the results are embedded straight into each page as inline JSON
   for catalog.js/cart.js to render. Requires $pdo (config/database.php)
   and session_start() to already have run. */

$products = array_map(function (array $r): array {
    return [
        'id' => $r['id'],
        'name' => $r['name'],
        'brand' => $r['brand'],
        'category' => $r['category'],
        'price' => (float) $r['price'],
        'badges' => $r['badges'] !== null && $r['badges'] !== '' ? explode(',', $r['badges']) : [],
        'img' => $r['image'],
        'desc' => $r['description'],
        'date' => $r['product_date'],
    ];
}, $pdo->query(
    'SELECT id, name, brand, category, price, badges, image, description, product_date FROM products'
)->fetchAll());

$categories = json_decode(file_get_contents(__DIR__ . '/../data/categories.json'), true);

$productsById = [];
foreach ($products as $p) {
    $productsById[$p['id']] = $p;
}

$cartItems = [];
$cartSubtotal = 0.0;
$cartCount = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $pid => $qty) {
        if (!isset($productsById[$pid])) continue;
        $p = $productsById[$pid];
        $qty = (int) $qty;
        $cartItems[] = ['id' => $p['id'], 'name' => $p['name'], 'price' => $p['price'], 'img' => $p['img'], 'qty' => $qty];
        $cartSubtotal += $p['price'] * $qty;
        $cartCount += $qty;
    }
}
$cartSubtotal = round($cartSubtotal, 2);
