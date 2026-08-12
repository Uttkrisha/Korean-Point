<?php
/* One shared PDO connection for the whole app. Every PHP file that needs
   the database does require_once __DIR__ . '/../../config/database.php'
   (path depends on how deep the file is) and then uses $pdo. */

$DB_HOST = 'localhost';
$DB_NAME = 'korean_point';
$DB_USER = 'root';
$DB_PASS = ''; // XAMPP's MySQL root user has no password by default —
                // if you set one in phpMyAdmin/MySQL, put it here.

try {
    $pdo = new PDO(
        "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    die(json_encode(['error' => 'Database connection failed.']));
}
