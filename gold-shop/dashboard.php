<?php include 'includes/header.php'; include 'includes/sidebar.php'; 

$totalCustomers = $pdo->query("SELECT COUNT(*) FROM customers")->fetchColumn();
$totalProducts  = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalSales     = $pdo->query("SELECT COUNT(*) FROM sales")->fetchColumn();
$revenue        = $pdo->query("SELECT COALESCE(SUM(total_amount),0) FROM sales")->fetchColumn();
?>
<h1>Dashboard</h1>
<div class="cards">
    <div class="card"><h3>Customers</h3><p><?= $totalCustomers ?></p></div>
    <div class="card"><h3>Products</h3><p><?= $totalProducts ?></p></div>
    <div class="card"><h3>Sales</h3><p><?= $totalSales ?></p></div>
    <div class="card"><h3>Revenue</h3><p>₹<?= number_format($revenue, 2) ?></p></div>
</div>
<?php include 'includes/footer.php'; ?>