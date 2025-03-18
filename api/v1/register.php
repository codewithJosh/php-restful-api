<?php
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

try {
    $input = json_decode(file_get_contents('php://input'), true);

    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($input['username'], $input['password'])) {
        handleError('Invalid request', 400);
    }

    // Sanitize inputs
    $username = sanitizeInput($input['username']);
    $password = sanitizeInput($input['password']);

    // Validate inputs
    if (!validateUsername($username)) {
        handleError('Username must be alphanumeric and 3-20 characters long', 400);
    }

    if (!validatePassword($password)) {
        handleError('Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one number', 400);
    }

    // Hash the password
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);
    if (!$passwordHash) {
        handleError('Failed to hash password', 500);
    }

    // Insert into the database
    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    if (!$stmt->execute([$username, $passwordHash])) {
        handleError('Registration failed', 500);
    }

    jsonResponse(['message' => 'User registered successfully'], 201);
} catch (PDOException $e) {
    handleError('Database error: ' . $e->getMessage(), 500);
} catch (Exception $e) {
    handleError('An unexpected error occurred', 500);
}