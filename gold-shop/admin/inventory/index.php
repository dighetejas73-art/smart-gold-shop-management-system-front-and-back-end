<?php
$pageTitle = 'Inventory';
require_once __DIR__ . '/../../config/db.php';
requireRole('admin');

$rows = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll();

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<h1>📦 Inventory</h1>
<a class="btn" href="add.php">➕ Add Product</a>

<?php if ($rows): ?>
<table>
    <tr>
        <th>ID</th><th>Name</th><th>Category</th>
        <th>Weight(g)</th><th>Purity</th><th>₹/gram</th>
        <th>Total Value</th><th>Stock</th><th>Actions</th>
    </tr>
    <?php foreach ($rows as $r): ?>
        <tr>
            <td><?= $r['id'] ?></td>
            <td><?= e($r['name']) ?></td>
            <td><?= e($r['category']) ?></td>
            <td><?= $r['weight_grams'] ?></td>
            <td><?= e($r['purity']) ?></td>
            <td>₹<?= number_format($r['price_per_gram'],2) ?></td>
            <td>₹<?= number_format($r['price_per_gram'] * $r['weight_grams'],2) ?></td>
            <td><?= $r['stock_qty'] ?></td>
            <td>
                <a href="edit.php?id=<?= $r['id'] ?>">Edit</a>
                <a href="update.php?id=<?= $r['id'] ?>">Stock</a>
                <a href="delete.php?id=<?= $r['id'] ?>" onclick="return confirm('Delete this product?')" style="color:#c0392b;">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
    <p style="color:#888;padding:20px;background:white;border-radius:8px;margin-top:15px;">No products yet.</p>
<?php endif; ?>

<?php include __DIR__ . '/../../includes/footer.php'; ?>