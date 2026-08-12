<?php
session_start();
require_once __DIR__ . '/../../config/database.php';
header('Content-Type: application/json');

function respond(int $status, array $data): void {
    http_response_code($status);
    echo json_encode($data);
    exit;
}

$body = json_decode(file_get_contents('php://input'), true) ?? [];
$name = trim($body['name'] ?? '');
$email = strtolower(trim($body['email'] ?? ''));
$birthdate = $body['birthdate'] ?? '';
$password = $body['password'] ?? '';
$confirm = $body['confirm'] ?? $password;

$today = date('Y-m-d');

if (!preg_match('/^[A-Za-z ]{2,50}$/', $name)) respond(400, ['error' => 'Name must be letters only (2-50 characters).']);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) respond(400, ['error' => 'Please enter a valid email address.']);
if (!$birthdate || $birthdate > $today) respond(400, ['error' => 'Birthdate cannot be in the future.']);
if (strlen($password) < 6) respond(400, ['error' => 'Password must be at least 6 characters.']);
if ($password !== $confirm) respond(400, ['error' => 'Passwords do not match.']);

$stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
$stmt->execute([$email]);
if ($stmt->fetch()) respond(400, ['error' => 'An account with this email already exists.']);

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare('INSERT INTO users (name, email, birthdate, password) VALUES (?, ?, ?, ?)');
$stmt->execute([$name, $email, $birthdate, $hash]);
$userId = (int) $pdo->lastInsertId();

$_SESSION['user_id'] = $userId;
$_SESSION['user_name'] = $name;
$_SESSION['user_email'] = $email;

respond(200, ['id' => $userId, 'name' => $name, 'email' => $email]);
