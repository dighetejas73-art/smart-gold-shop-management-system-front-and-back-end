<?php
require_once __DIR__ . '/../config/db.php';

if (isset($_SESSION['user_id'])) {
    redirectByRole($_SESSION['role'] ?? 'customer');
}

$error   = '';
$success = '';
$mode    = $_GET['mode'] ?? 'login';
$role    = $_GET['role'] ?? 'customer';

// ---------- SIGN UP ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'signup') {
    $mode     = 'signup';
    $role     = $_POST['role'] ?? 'customer';
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if (!in_array($role, ['customer', 'staff'])) $role = 'customer';

    if ($username === '' || $email === '' || $password === '') {
        $error = "All fields are required.";
    } elseif (strlen($username) < 3) {
        $error = "Username must be at least 3 characters.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);

        if ($stmt->fetch()) {
            $error = "Username or email already registered.";
        } else {
            // Plain text password
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $email, $password, $role]);
            $success = ucfirst($role) . " account created! You can now log in.";
            $mode = 'login';
        }
    }
}

// ---------- LOGIN ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'login') {
    $mode     = 'login';
    $role     = $_POST['role'] ?? 'customer';
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = "Please enter username and password.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();

        if (!$user) {
            $error = "Invalid username or password.";
        } elseif ($user['password'] !== $password) {           // plain text compare
            $error = "Invalid username or password.";
        } elseif ($user['role'] !== $role) {
            $error = "This account is registered as <b>" . htmlspecialchars(ucfirst($user['role'])) . "</b>. Please select the correct tab.";
        } else {
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'];
            redirectByRole($user['role']);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= ucfirst($mode) ?> — Gold Shop</title>
    <link rel="stylesheet" href="/gold-shop/assets/style.css">
</head>
<body class="login-body">
    <div class="login-box">
        <h1>🏆 Gold Shop</h1>

        <div class="mode-tabs">
            <a href="?mode=login&role=<?= e($role) ?>"  class="<?= $mode === 'login'  ? 'active' : '' ?>">Sign In</a>
            <a href="?mode=signup&role=<?= e($role) ?>" class="<?= $mode === 'signup' ? 'active' : '' ?>">Sign Up</a>
        </div>

        <?php if ($mode === 'login'): ?>
            <div class="role-tabs">
                <a href="?mode=login&role=admin"    class="<?= $role === 'admin'    ? 'active' : '' ?>">👑 Admin</a>
                <a href="?mode=login&role=staff"    class="<?= $role === 'staff'    ? 'active' : '' ?>">👨‍💼 Staff</a>
                <a href="?mode=login&role=customer" class="<?= $role === 'customer' ? 'active' : '' ?>">🛒 Customer</a>
            </div>
        <?php else: ?>
            <div class="role-tabs">
                <a href="?mode=signup&role=staff"    class="<?= $role === 'staff'    ? 'active' : '' ?>">👨‍💼 Staff</a>
                <a href="?mode=signup&role=customer" class="<?= $role === 'customer' ? 'active' : '' ?>">🛒 Customer</a>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert-error"><?= $error ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert-success"><?= e($success) ?></div>
        <?php endif; ?>

        <?php if ($mode === 'login'): ?>
            <form method="POST" autocomplete="off">
                <input type="hidden" name="action" value="login">
                <input type="hidden" name="role"   value="<?= e($role) ?>">
                <input type="text"     name="username" placeholder="Username or Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Sign In as <?= ucfirst($role) ?></button>
            </form>
            <p class="hint">Don't have an account? <a href="?mode=signup&role=<?= e($role) ?>">Sign up</a></p>
        <?php else: ?>
            <form method="POST" autocomplete="off">
                <input type="hidden" name="action" value="signup">
                <input type="hidden" name="role"   value="<?= e($role) ?>">
                <input type="text"     name="username" placeholder="Username (min 3 chars)" required>
                <input type="email"    name="email"    placeholder="Email" required>
                <input type="password" name="password" placeholder="Password (min 6 chars)" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <button type="submit">Create <?= ucfirst($role) ?> Account</button>
            </form>
            <p class="hint">Already have an account? <a href="?mode=login&role=<?= e($role) ?>">Sign in</a></p>
        <?php endif; ?>

        <p class="hint" style="font-size:12px;color:#999;margin-top:14px;">
            Default admin: <b>admin</b> / <b>admin123</b>
        </p>
    </div>
</body>
</html>