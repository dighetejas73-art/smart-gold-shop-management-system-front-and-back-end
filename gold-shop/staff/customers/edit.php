<?php
$pageTitle = 'Edit Customer';
require_once __DIR__ . '/../../config/db.php';
requireRole('staff');

$id = (int)($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE customers SET name=?, phone=?, email=?, address=? WHERE id=?");
    $stmt->execute([
        trim($_POST['name']),
        trim($_POST['phone']),
        trim($_POST['email']),
        trim($_POST['address']),
        $id,
    ]);
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM customers WHERE id=?");
$stmt->execute([$id]);
$c = $stmt->fetch();

if (!$c) {
    die("Customer not found.");
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<h1>✏️ Edit Customer</h1>
<a class="btn" href="index.php">← Back to List</a>

<form method="POST" class="form">
    <label style="font-weight:600;color:#555;font-size:13px;">Name *</label>
    <input name="name" value="<?= e($c['name']) ?>" required>

    <label style="font-weight:600;color:#555;font-size:13px;">Phone</label>
    <input name="phone" value="<?= e($c['phone']) ?>">

    <label style="font-weight:600;color:#555;font-size:13px;">Email</label>
    <input name="email" type="email" value="<?= e($c['email']) ?>">

    <label style="font-weight:600;color:#555;font-size:13px;">Address</label>
    <textarea name="address"><?= e($c['address']) ?></textarea>

    <button type="submit">💾 Update Customer</button>
</form>

<?php include __DIR__ . '/../../includes/footer.php'; ?>