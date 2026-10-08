<?php
$pageTitle = 'Edit Product';
require_once __DIR__ . '/../../config/db.php';
requireRole('admin');

$id = (int)($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE products SET name=?, category=?, weight_grams=?, purity=?, price_per_gram=? WHERE id=?");
    $stmt->execute([
        trim($_POST['name']), trim($_POST['category']),
        $_POST['weight_grams'], trim($_POST['purity']),
        $_POST['price_per_gram'], $id
    ]);
    header("Location: index.php"); exit;
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE id=?"); $stmt->execute([$id]);
$p = $stmt->fetch();
if (!$p) die("Product not found.");

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<h1>✏️ Edit Product</h1>
<a class="btn" href="index.php">← Back</a>

<form method="POST" class="form">
    <label style="font-weight:600;color:#555;font-size:13px;">Name *</label>
    <input name="name" value="<?= e($p['name']) ?>" required>
    <label style="font-weight:600;color:#555;font-size:13px;">Category</label>
    <input name="category" value="<?= e($p['category']) ?>">
    <label style="font-weight:600;color:#555;font-size:13px;">Weight (grams)</label>
    <input name="weight_grams" type="number" step="0.01" value="<?= $p['weight_grams'] ?>">
    <label style="font-weight:600;color:#555;font-size:13px;">Purity</label>
    <input name="purity" value="<?= e($p['purity']) ?>">
    <label style="font-weight:600;color:#555;font-size:13px;">Price per gram</label>
    <input name="price_per_gram" type="number" step="0.01" value="<?= $p['price_per_gram'] ?>">
    <button type="submit">💾 Update Product</button>
</form>

<?php include __DIR__ . '/../../includes/footer.php'; ?>