<?php
$pageTitle = 'My Profile';
require_once __DIR__ . '/../config/db.php';
requireLogin();
requireRole('customer');

$userId = $_SESSION['user_id'];
$success = '';
$error   = '';

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email   = trim($_POST['email'] ?? '');
    $phone   = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email.";
    } else {
        // Update only the columns that exist
        try {
            $stmt = $pdo->prepare("UPDATE users SET email = ?, phone = ?, address = ? WHERE id = ?");
            $stmt->execute([$email, $phone, $address, $userId]);
            $success = "Profile updated successfully!";
        } catch (Exception $e) {
            // phone/address column might not exist
            $stmt = $pdo->prepare("UPDATE users SET email = ? WHERE id = ?");
            $stmt->execute([$email, $userId]);
            $success = "Profile updated (email only — add phone & address columns to enable full profile).";
        }
    }
}

// Fetch current user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
?>

<h1>👤 My Profile</h1>

<?php if ($success): ?>
    <div style="background:#d4edda;color:#155724;padding:12px;border-radius:6px;margin-bottom:15px;border:1px solid #c3e6cb;">
        <?= e($success) ?>
    </div>
<?php endif; ?>
<?php if ($error): ?>
    <div style="background:#f8d7da;color:#721c24;padding:12px;border-radius:6px;margin-bottom:15px;border:1px solid #f5c6cb;">
        <?= e($error) ?>
    </div>
<?php endif; ?>

<form method="POST" class="form" style="max-width:600px;">
    <label style="font-weight:600;color:#555;font-size:13px;">Username (read-only)</label>
    <input type="text" value="<?= e($user['username']) ?>" disabled style="background:#f5f5f5;cursor:not-allowed;">

    <label style="font-weight:600;color:#555;font-size:13px;">Role (read-only)</label>
    <input type="text" value="<?= e(ucfirst($user['role'])) ?>" disabled style="background:#f5f5f5;cursor:not-allowed;">

    <label style="font-weight:600;color:#555;font-size:13px;">Email</label>
    <input type="email" name="email" value="<?= e($user['email'] ?? '') ?>" required>

    <label style="font-weight:600;color:#555;font-size:13px;">Phone</label>
    <input type="text" name="phone" value="<?= e($user['phone'] ?? '') ?>" placeholder="+91 98765 43210">

    <label style="font-weight:600;color:#555;font-size:13px;">Address</label>
    <textarea name="address" placeholder="Your delivery address"><?= e($user['address'] ?? '') ?></textarea>

    <button type="submit">💾 Save Changes</button>
</form>

<div style="margin-top:25px;padding:20px;background:white;border-radius:10px;border-left:4px solid #b8860b;max-width:600px;">
    <h3 style="color:#b8860b;margin-bottom:10px;">Account Details</h3>
    <p style="color:#666;line-height:1.8;">
        <b>Member since:</b> <?= e($user['created_at'] ?? 'N/A') ?><br>
        <b>Account type:</b> <?= e(ucfirst($user['role'])) ?><br>
        <b>User ID:</b> #<?= e($user['id']) ?>
    </p>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>