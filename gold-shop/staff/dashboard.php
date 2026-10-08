<?php
$pageTitle = 'Staff Dashboard';
require_once __DIR__ . '/../config/db.php';
requireRole('staff');

$totalCustomers = $pdo->query("SELECT COUNT(*) FROM customers")->fetchColumn();
$totalProducts  = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();

$mySales = $pdo->query("SELECT COUNT(*) FROM sales")->fetchColumn();

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
?>

<h1>👨‍💼 Staff Dashboard</h1>

<div class="cards">
    <div class="card"><h3>Customers</h3><p><?= $totalCustomers ?></p></div>
    <div class="card"><h3>Products in Stock</h3><p><?= $totalProducts ?></p></div>
    <div class="card"><h3>Total Sales</h3><p><?= $mySales ?></p></div>
</div>

<div style="margin-top:35px;padding:20px;background:white;border-radius:10px;border-left:4px solid #d4af37;">
    <h3 style="color:#b8860b;margin-bottom:10px;">What you can do:</h3>
    <ul style="line-height:1.9;color:#555;">
        <li>✅ View customer list</li>
        <li>✅ Add new customers</li>
        <li>✅ View inventory (read-only)</li>
        <li>✅ Create new sales</li>
        <li>❌ Cannot edit inventory</li>
        <li>❌ Cannot view reports</li>
    </ul>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>