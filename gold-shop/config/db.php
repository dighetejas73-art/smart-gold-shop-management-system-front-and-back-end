<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = 'localhost';
$db   = 'gold_shop';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}

// ---- Auto-create admin (plain text password) ----
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = 'admin'");
    $stmt->execute();
    if ($stmt->fetchColumn() == 0) {
        $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'admin')")
            ->execute(['admin', 'admin123']);
    }
} catch (Exception $e) { }

// ---- Helpers ----
function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /gold-shop/auth/login.php");
        exit;
    }
}

function requireRole($roles) {
    requireLogin();
    $roles = (array) $roles;
    if (!in_array($_SESSION['role'] ?? '', $roles, true)) {
        http_response_code(403);
        die("403 — Access denied.");
    }
}

function redirectByRole($role) {
    switch ($role) {
        case 'admin':    header("Location: /gold-shop/admin/dashboard.php"); break;
        case 'staff':    header("Location: /gold-shop/staff/dashboard.php"); break;
        default:         header("Location: /gold-shop/shop/index.php");      break;
    }
    exit;
}

function hasRole($roles) {
    $roles = (array) $roles;
    return in_array($_SESSION['role'] ?? '', $roles, true);
}

function e($text) {
    return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
}
?>