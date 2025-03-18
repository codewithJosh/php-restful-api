<?php
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($input['username'], $input['password'])) {
    jsonResponse(['error' => 'Invalid request'], 400);
}

$username = $input['username'];
$password = password_hash($input['password'], PASSWORD_BCRYPT);

$stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
if ($stmt->execute([$username, $password])) {
    jsonResponse(['message' => 'User registered successfully'], 201);
} else {
    jsonResponse(['error' => 'Registration failed'], 500);
}