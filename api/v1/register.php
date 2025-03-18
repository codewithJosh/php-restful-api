<?php
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($input['username'], $input['password'])) {
    jsonResponse(['error' => 'Invalid request'], 400);
}

// Sanitize inputs
$username = sanitizeInput($input['username']);
$password = sanitizeInput($input['password']);

// Validate inputs
if (!validateUsername($username)) {
    jsonResponse(['error' => 'Username must be alphanumeric and 3-20 characters long'], 400);
}

if (!validatePassword($password)) {
    jsonResponse(['error' => 'Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one number'], 400);
}

// Hash the password
$passwordHash = password_hash($password, PASSWORD_BCRYPT);

// Insert into the database
$stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
if ($stmt->execute([$username, $passwordHash])) {
    jsonResponse(['message' => 'User registered successfully'], 201);
} else {
    jsonResponse(['error' => 'Registration failed'], 500);
}