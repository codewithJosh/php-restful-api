<?php
function jsonResponse($data, $statusCode = 200) {
    header('Content-Type: application/json');
    http_response_code($statusCode);
    echo json_encode($data);
    exit;
}

function generateToken() {
    return bin2hex(random_bytes(32));
}

// Sanitize input data
function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    return htmlspecialchars(strip_tags(trim($data)));
}

// Validate username (alphanumeric, 3-20 characters)
function validateUsername($username) {
    return preg_match('/^[a-zA-Z0-9]{3,20}$/', $username);
}

// Validate password (at least 8 characters, 1 uppercase, 1 lowercase, 1 number)
function validatePassword($password) {
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password);
}