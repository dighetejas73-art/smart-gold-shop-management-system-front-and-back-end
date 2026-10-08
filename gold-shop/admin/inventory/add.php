<?php
$pageTitle = 'Add Product';
require_once __DIR__ . '/../../config/db.php';
requireRole('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO products (name, category, weight_grams, purity, price_per_gram, stock_qty) VALUES (?,?,?,?,?,?)");
    $stmt->execute([
        trim($_POST['name']), trim($_POST['category']),
        $_POST['weight_grams'], trim($_POST['purity']),
        $_POST['price_per_gram'], $_POST['stock_qty']
    ]);
    header("Location: index.php"); exit;
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<h1>➕ Add Product</h1>
<a class="btn" href="index.php">← Back</a>

<form method="POST" class="form">
    <label style="font-weight:600;color:#555;font-size:13px;">Name *</label>
    <input name="name" required>
    <label style="font-weight:600;color:#555;font-size:13px;">Category</label>
    <input name="category" placeholder="Ring / Chain / Necklace">
    <label style="font-weight:600;color:#555;font-size:13px;">Weight (grams) *</label>
    <input name="weight_grams" type="number" step="0.01" required>
    <label style="font-weight:600;color:#555;font-size:13px;">Purity</label>
    <input name="purity" placeholder="22K / 24K">
    <label style="font-weight:600;color:#555;font-size:13px;">Price per gram (₹) *</label>
    <input name="price_per_gram" type="number" step="0.01" required>
    <label style="font-weight:600;color:#555;font-size:13px;">Stock Quantity *</label>
    <input name="stock_qty" type="number" value="1" min="0" required>
    <button type="submit">💾 Save Product</button>
</form>

<?php include __DIR__ . '/../../includes/footer.php'; ?>