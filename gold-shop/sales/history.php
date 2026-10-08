<?php include '../includes/header.php'; include '../includes/sidebar.php';
$rows = $pdo->query("
    SELECT s.*, c.name AS customer_name, p.name AS product_name
    FROM sales s
    LEFT JOIN customers c ON s.customer_id = c.id
    LEFT JOIN products p ON s.product_id = p.id
    ORDER BY s.id DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>
<h1>Sales History</h1>
<table>
    <tr><th>ID</th><th>Date</th><th>Customer</th><th>Product</th><th>Qty</th><th>Total</th></tr>
    <?php foreach ($rows as $r): ?>
        <tr>
            <td><?= $r['id'] ?></td>
            <td><?= $r['sale_date'] ?></td>
            <td><?= htmlspecialchars($r['customer_name'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($r['product_name'] ?? 'N/A') ?></td>
            <td><?= $r['quantity'] ?></td>
            <td>₹<?= number_format($r['total_amount'], 2) ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php include '../includes/footer.php'; ?>