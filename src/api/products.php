<?php
require_once __DIR__ . '/../../config/database.php';
header('Content-Type: application/json');

$rows = $pdo->query(
    'SELECT id, name, brand, category, price, old_price, rating, reviews, badges, image, description, product_date FROM products'
)->fetchAll();

$products = array_map(function (array $r): array {
    return [
        'id' => $r['id'],
        'name' => $r['name'],
        'brand' => $r['brand'],
        'category' => $r['category'],
        'price' => (float) $r['price'],
        'was' => $r['old_price'] !== null ? (float) $r['old_price'] : (float) $r['price'],
        'rating' => $r['rating'] !== null ? (float) $r['rating'] : 0,
        'reviews' => (int) $r['reviews'],
        'badges' => $r['badges'] !== null && $r['badges'] !== '' ? explode(',', $r['badges']) : [],
        'img' => $r['image'],
        'desc' => $r['description'],
        'date' => $r['product_date'],
    ];
}, $rows);

echo json_encode($products);
