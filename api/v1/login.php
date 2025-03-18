<?php
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($input['username'], $input['password'])) {
    jsonResponse(['error' => 'Invalid request'], 400);
}

$username = $input['username'];
$password = $input['password'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    $token = generateToken();
    // In a real application, you would store this token in the database
    jsonResponse(['token' => $token]);
} else {
    jsonResponse(['error' => 'Invalid credentials'], 401);
}