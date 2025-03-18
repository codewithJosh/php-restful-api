<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

$url = $_GET['url'] ?? '';

switch ($url) {
    case 'api/v1/register':
        require 'api/v1/register.php';
        break;
    case 'api/v1/login':
        require 'api/v1/login.php';
        break;
    case 'api/v1/protected':
        require 'api/v1/protected.php';
        break;
    default:
        jsonResponse(['error' => 'Endpoint not found'], 404);
        break;
}