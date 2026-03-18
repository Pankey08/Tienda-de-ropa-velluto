<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION = [];
session_regenerate_id(true);
setFlashMessage('success', 'Tu sesión se cerró correctamente.');
redirect('index.php');
