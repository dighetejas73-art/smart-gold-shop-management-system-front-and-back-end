<?php include '../includes/header.php'; include '../includes/sidebar.php';

$from = $_GET['from'] ?? date('Y-m-01');
$to   = $_GET['to']   ?? date('Y-m-d');

$stmt = $pdo->prepare("
    SELECT s.*, c.name AS customer_name, p.name AS product_name
    FROM sales s
    LEFT JOIN customers c ON s.customer_id = c.id
    LEFT JOIN products p ON s.product_id = p.id
    WHERE DATE(s.sale_date) BETWEEN ? AND ?
    ORDER BY s.sale_date DESC
");
$stmt->execute([$from, $to]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = array_sum(array_column($rows, 'total_amount'));
?>
<h1>Sales Report</h1>
<form method="GET" class="form-inline">
    From: <input type="date" name="from" value="<?= $from ?>">
    To:   <input type="date" name="to" value="<?= $to ?>">
    <button type="submit">Filter</button>
</form>
<p><strong>Total Revenue: ₹<?= number_format($total, 2) ?></strong> (<?= count($rows) ?> sales)</p>
<table>
    <tr><th>Date</th><th>Customer</th><th>Product</th><th>Qty</th><th>Total</th></tr>
    <?php foreach ($rows as $r): ?>
        <tr>
            <td><?= $r['sale_date'] ?></td>
            <td><?= htmlspecialchars($r['customer_name'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($r['product_name'] ?? 'N/A') ?></td>
            <td><?= $r['quantity'] ?></td>
            <td>₹<?= number_format($r['total_amount'], 2) ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php include '../includes/footer.php'; ?>