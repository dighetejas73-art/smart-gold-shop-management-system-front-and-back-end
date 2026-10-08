<?php
$pageTitle = 'Admin Dashboard';
require_once __DIR__ . '/../config/db.php';
requireRole('admin');

// ============ STATS ============
$totalCustomers = $pdo->query("SELECT COUNT(*) FROM customers")->fetchColumn();
$totalProducts  = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalSales     = $pdo->query("SELECT COUNT(*) FROM sales")->fetchColumn();
$revenue        = $pdo->query("SELECT COALESCE(SUM(total_amount),0) FROM sales")->fetchColumn();
$totalUsers     = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

// ============ RECENT SALES ============
$recentSales = $pdo->query("
    SELECT s.*, c.name AS customer_name, p.name AS product_name
    FROM sales s
    LEFT JOIN customers c ON s.customer_id = c.id
    LEFT JOIN products  p ON s.product_id  = p.id
    ORDER BY s.id DESC LIMIT 5
")->fetchAll();

// ============ LOW STOCK ============
$lowStock = $pdo->query("
    SELECT * FROM products WHERE stock_qty <= 2 ORDER BY stock_qty ASC LIMIT 5
")->fetchAll();

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
?>

<h1>👑 Admin Dashboard</h1>

<div class="cards">
    <div class="card"><h3>Customers</h3><p><?= $totalCustomers ?></p></div>
    <div class="card"><h3>Products</h3><p><?= $totalProducts ?></p></div>
    <div class="card"><h3>Sales</h3><p><?= $totalSales ?></p></div>
    <div class="card"><h3>Revenue</h3><p>₹<?= number_format($revenue, 2) ?></p></div>
    <div class="card"><h3>Users</h3><p><?= $totalUsers ?></p></div>
</div>

<h2 style="margin-top:35px;color:#1e272e;font-size:1.2rem;">🕒 Recent Sales</h2>
<?php if ($recentSales): ?>
<table>
    <tr><th>ID</th><th>Date</th><th>Customer</th><th>Product</th><th>Qty</th><th>Total</th></tr>
    <?php foreach ($recentSales as $s): ?>
        <tr>
            <td><?= $s['id'] ?></td>
            <td><?= $s['sale_date'] ?></td>
            <td><?= e($s['customer_name'] ?? 'N/A') ?></td>
            <td><?= e($s['product_name'] ?? 'N/A') ?></td>
            <td><?= $s['quantity'] ?></td>
            <td>₹<?= number_format($s['total_amount'], 2) ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
    <p style="color:#888;padding:20px;background:white;border-radius:8px;">
        No sales yet. <a href="/gold-shop/admin/sales/new.php" style="color:#b8860b;font-weight:600;">Record your first sale →</a>
    </p>
<?php endif; ?>

<h2 style="margin-top:35px;color:#1e272e;font-size:1.2rem;">⚠️ Low Stock Alerts</h2>
<?php if ($lowStock): ?>
<table>
    <tr><th>Product</th><th>Category</th><th>Stock Left</th><th>Action</th></tr>
    <?php foreach ($lowStock as $p): ?>
        <tr>
            <td><?= e($p['name']) ?></td>
            <td><?= e($p['category']) ?></td>
            <td style="color:#c0392b;font-weight:bold;"><?= $p['stock_qty'] ?></td>
            <td><a href="/gold-shop/admin/inventory/update.php?id=<?= $p['id'] ?>">Restock</a></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
    <p style="color:#888;padding:20px;background:white;border-radius:8px;">
        ✅ All products are well-stocked.
    </p>
<?php endif; ?>

<h2 style="margin-top:35px;color:#1e272e;font-size:1.2rem;">⚡ Quick Actions</h2>
<div class="cards" style="margin-top:15px;">
    <a href="/gold-shop/admin/inventory/add.php" class="card" style="text-decoration:none;text-align:center;">
        <p style="font-size:2rem;margin-bottom:5px;">📦</p>
        <h3>Add Product</h3>
    </a>
    <a href="/gold-shop/admin/customers/add.php" class="card" style="text-decoration:none;text-align:center;">
        <p style="font-size:2rem;margin-bottom:5px;">👥</p>
        <h3>Add Customer</h3>
    </a>
    <a href="/gold-shop/admin/sales/new.php" class="card" style="text-decoration:none;text-align:center;">
        <p style="font-size:2rem;margin-bottom:5px;">💰</p>
        <h3>New Sale</h3>
    </a>
    <a href="/gold-shop/admin/users.php" class="card" style="text-decoration:none;text-align:center;">
        <p style="font-size:2rem;margin-bottom:5px;">🔑</p>
        <h3>Manage Users</h3>
    </a>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>