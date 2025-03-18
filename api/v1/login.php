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
        handleError('Invalid username', 400);
    }

    if (empty($password)) {
        handleError('Password is required', 400);
    }

    // Fetch user from the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    if (!$stmt->execute([$username])) {
        handleError('Database error', 500);
    }

    $user = $stmt->fetch();
    if (!$user || !password_verify($password, $user['password'])) {
        handleError('Invalid credentials', 401);
    }

    // Generate a token
    $token = generateToken();
    if (!$token) {
        handleError('Failed to generate token', 500);
    }

    jsonResponse(['token' => $token]);
} catch (PDOException $e) {
    handleError('Database error: ' . $e->getMessage(), 500);
} catch (Exception $e) {
    handleError('An unexpected error occurred', 500);
}