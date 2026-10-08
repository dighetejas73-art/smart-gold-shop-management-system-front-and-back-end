<aside class="sidebar">
    <h2>🏆 Gold Shop</h2>
    <p style="font-size:12px;color:#aaa;margin-bottom:10px;">
        Logged in as <b style="color:#d4af37;"><?= e($_SESSION['username']) ?></b><br>
        Role: <b style="color:#d4af37;"><?= e(ucfirst($_SESSION['role'])) ?></b>
    </p>

    <nav>
    <?php if (hasRole('admin')): ?>
        <a href="/gold-shop/admin/dashboard.php">📊 Dashboard</a>
        <a href="/gold-shop/admin/customers/index.php">👥 Customers</a>
        <a href="/gold-shop/admin/inventory/index.php">📦 Inventory</a>
        <a href="/gold-shop/admin/sales/new.php">💰 New Sale</a>
        <a href="/gold-shop/admin/sales/history.php">🧾 Sales History</a>
        <a href="/gold-shop/admin/reports/sales_report.php">📈 Reports</a>
        <a href="/gold-shop/admin/users.php">🔑 Manage Users</a>

    <?php elseif (hasRole('staff')): ?>
        <a href="/gold-shop/staff/dashboard.php">📊 Dashboard</a>
        <a href="/gold-shop/staff/customers/index.php">👥 Customers</a>
        <a href="/gold-shop/staff/inventory/index.php">📦 View Inventory</a>
        <a href="/gold-shop/staff/sales/new.php">💰 New Sale</a>
        <a href="/gold-shop/staff/sales/history.php">🧾 Sales History</a>

    <?php else: // customer ?>
        <a href="/gold-shop/shop/index.php">🛍️ Shop</a>
        <a href="/gold-shop/shop/orders.php">🧾 My Orders</a>
        <a href="/gold-shop/shop/profile.php">👤 My Profile</a>
    <?php endif; ?>

        <a href="/gold-shop/auth/logout.php" class="logout">🚪 Logout</a>
    </nav>
</aside>
<main class="content">