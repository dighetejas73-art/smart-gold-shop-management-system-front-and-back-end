<?php
$pageTitle = 'View Inventory';
require_once __DIR__ . '/../../config/db.php';
requireRole('staff');

$rows = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll();

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<h1>📦 Inventory (View Only)</h1>

<div style="background:#fff3cd;border:1px solid #ffc107;color:#856404;padding:12px 16px;border-radius:8px;margin-bottom:15px;">
    🔒 <b>Read-only mode:</b> You can view products but cannot add, edit, or delete them. Contact an admin for inventory changes.
</div>

<?php if ($rows): ?>
<table>
    <tr>
        <th>ID</th><th>Name</th><th>Category</th>
        <th>Weight (g)</th><th>Purity</th>
        <th>₹/gram</th><th>Total Value</th><th>Stock</th>
    </tr>
    <?php foreach ($rows as $r): ?>
        <tr>
            <td><?= $r['id'] ?></td>
            <td><?= e($r['name']) ?></td>
            <td><?= e($r['category']) ?></td>
            <td><?= $r['weight_grams'] ?></td>
            <td><?= e($r['purity']) ?></td>
            <td>₹<?= number_format($r['price_per_gram'], 2) ?></td>
            <td>₹<?= number_format($r['price_per_gram'] * $r['weight_grams'], 2) ?></td>
            <td>
                <?php if ($r['stock_qty'] <= 2): ?>
                    <span style="color:#c0392b;font-weight:bold;"><?= $r['stock_qty'] ?> ⚠️</span>
                <?php elseif ($r['stock_qty'] <= 5): ?>
                    <span style="color:#f39c12;font-weight:bold;"><?= $r['stock_qty'] ?></span>
                <?php else: ?>
                    <span style="color:#27ae60;font-weight:bold;"><?= $r['stock_qty'] ?></span>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
    <p style="color:#888;padding:20px;background:white;border-radius:8px;margin-top:15px;">
        No products in inventory.
    </p>
<?php endif; ?>

<?php include __DIR__ . '/../../includes/footer.php'; ?>