<?php
$pageTitle = 'Update Stock';
require_once __DIR__ . '/../../config/db.php';
requireRole('admin');

$id = (int)($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE products SET stock_qty=? WHERE id=?");
    $stmt->execute([(int)$_POST['stock_qty'], $id]);
    header("Location: index.php"); exit;
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE id=?"); $stmt->execute([$id]);
$p = $stmt->fetch();
if (!$p) die("Product not found.");

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<h1>📦 Update Stock — <?= e($p['name']) ?></h1>
<a class="btn" href="index.php">← Back</a>

<form method="POST" class="form">
    <p style="color:#666;margin-bottom:8px;">
        Current stock: <b style="color:#b8860b;font-size:1.2rem;"><?= $p['stock_qty'] ?></b>
    </p>
    <label style="font-weight:600;color:#555;font-size:13px;">New Stock Quantity *</label>
    <input name="stock_qty" type="number" value="<?= $p['stock_qty'] ?>" min="0" required>
    <button type="submit">💾 Update Stock</button>
</form>

<?php include __DIR__ . '/../../includes/footer.php'; ?>