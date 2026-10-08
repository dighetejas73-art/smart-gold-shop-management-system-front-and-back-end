<?php
$pageTitle = 'Sales History';
require_once __DIR__ . '/../../config/db.php';
requireRole('staff');

$rows = $pdo->query("
    SELECT s.*, c.name AS customer_name, p.name AS product_name
    FROM sales s
    LEFT JOIN customers c ON s.customer_id = c.id
    LEFT JOIN products  p ON s.product_id  = p.id
    ORDER BY s.id DESC
")->fetchAll();

$totalRevenue = array_sum(array_column($rows, 'total_amount'));

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<h1>🧾 Sales History</h1>

<div class="cards" style="margin-bottom:20px;">
    <div class="card"><h3>Total Sales</h3><p><?= count($rows) ?></p></div>
    <div class="card"><h3>Total Revenue</h3><p>₹<?= number_format($totalRevenue, 2) ?></p></div>
</div>

<?php if ($rows): ?>
<table>
    <tr>
        <th>ID</th><th>Date</th><th>Customer</th>
        <th>Product</th><th>Qty</th><th>Total</th>
    </tr>
    <?php foreach ($rows as $r): ?>
        <tr>
            <td><?= $r['id'] ?></td>
            <td><?= $r['sale_date'] ?></td>
            <td><?= e($r['customer_name'] ?? 'N/A') ?></td>
            <td><?= e($r['product_name'] ?? 'N/A') ?></td>
            <td><?= $r['quantity'] ?></td>
            <td>₹<?= number_format($r['total_amount'], 2) ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
    <p style="color:#888;padding:20px;background:white;border-radius:8px;">
        No sales yet. <a href="new.php">Record the first sale →</a>
    </p>
<?php endif; ?>

<?php include __DIR__ . '/../../includes/footer.php'; ?>