<?php
session_start();
require_once __DIR__ . '/../../config/database.php';
header('Content-Type: application/json');

$body = json_decode(file_get_contents('php://input'), true) ?? [];
$email = strtolower(trim($body['email'] ?? ''));
$password = $body['password'] ?? '';

$stmt = $pdo->prepare('SELECT id, name, email, password FROM users WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid email or password.']);
    exit;
}

$_SESSION['user_id'] = (int) $user['id'];
$_SESSION['user_name'] = $user['name'];
$_SESSION['user_email'] = $user['email'];

echo json_encode(['id' => (int) $user['id'], 'name' => $user['name'], 'email' => $user['email']]);
