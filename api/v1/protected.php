<?php
require_once '../../includes/functions.php';

try {
    $headers = getallheaders();
    $token = $headers['Authorization'] ?? '';

    // Sanitize the token
    $token = sanitizeInput($token);

    if (empty($token)) {
        handleError('Unauthorized', 401);
    }

    // In a real application, you would validate the token against the database
    if ($token !== 'valid_token') {
        handleError('Invalid token', 403);
    }

    jsonResponse(['message' => 'This is a protected endpoint']);
} catch (Exception $e) {
    handleError('An unexpected error occurred', 500);
}