<?php
require_once '../../includes/functions.php';

$headers = getallheaders();
$token = $headers['Authorization'] ?? '';

if (empty($token)) {
    jsonResponse(['error' => 'Unauthorized'], 401);
}

// In a real application, you would validate the token against the database
if ($token !== 'valid_token') {
    jsonResponse(['error' => 'Invalid token'], 403);
}

jsonResponse(['message' => 'This is a protected endpoint']);