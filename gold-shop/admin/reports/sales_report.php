<?php
$pageTitle = 'Sales Report';
require_once __DIR__ . '/../../config/db.php';
requireRole('admin');

$from = $_GET['from'] ?? date('Y-m-01');
$to   = $_GET['to']   ?? date('Y-m-d');

$stmt = $pdo->prepare("
    SELECT s.*, c.name AS customer_name, p.name AS product_name
    FROM sales s
    LEFT JOIN customers c ON s.customer_id = c.id
    LEFT JOIN products  p ON s.product_id  = p.id
    WHERE DATE(s.sale_date) BETWEEN ? AND ?
    ORDER BY s.sale_date DESC
");
$stmt->execute([$from, $to]);
$rows = $stmt->fetchAll();

$total = array_sum(array_column($rows, 'total_amount'));

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<h1>📈 Sales Report</h1>

<form method="GET" class="form-inline" style="background:white;padding:15px;border-radius:10px;margin-bottom:15px;">
    <label>From:</label>
    <input type="date" name="from" value="<?= e($from) ?>">
    <label>To:</label>
    <input type="date" name="to" value="<?= e($to) ?>">
    <button type="submit">🔍 Filter</button>
</form>

<div class="cards" style="margin-bottom:20px;">
    <div class="card"><h3>Sales Count</h3><p><?= count($rows) ?></p></div>
    <div class="card"><h3>Total Revenue</h3><p>₹<?= number_format($total,2) ?></p></div>
</div>

<?php if ($rows): ?>
<table>
    <tr><th>Date</th><th>Customer</th><th>Product</th><th>Qty</th><th>Total</th></tr>
    <?php foreach ($rows as $r): ?>
        <tr>
            <td><?= $r['sale_date'] ?></td>
            <td><?= e($r['customer_name'] ?? 'N/A') ?></td>
            <td><?= e($r['product_name'] ?? 'N/A') ?></td>
            <td><?= $r['quantity'] ?></td>
            <td>₹<?= number_format($r['total_amount'],2) ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
    <p style="color:#888;padding:20px;background:white;border-radius:8px;">No sales in this date range.</p>
<?php endif; ?>

<?php include __DIR__ . '/../../includes/footer.php'; ?>