<?php
require_once __DIR__ . '/config/db.php';

if (isset($_SESSION['user_id'])) {
    redirectByRole($_SESSION['role'] ?? 'customer');
}

header("Location: /gold-shop/auth/login.php");
exit;