<?php
$pageTitle = 'My Orders';
require_once __DIR__ . '/../config/db.php';
requireLogin();
requireRole('customer');

$userId = $_SESSION['user_id'];

// Try to fetch orders if the orders table exists
$orders = [];
try {
    $stmt = $pdo->prepare("
        SELECT o.*, COUNT(oi.id) AS item_count
        FROM orders o
        LEFT JOIN order_items oi ON oi.order_id = o.id
        WHERE o.customer_id = ?
        GROUP BY o.id
        ORDER BY o.id DESC
    ");
    $stmt->execute([$userId]);
    $orders = $stmt->fetchAll();
} catch (Exception $e) {
    // orders table might not exist yet
    $orders = [];
}

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
?>

<h1>🧾 My Orders</h1>

<?php if (empty($orders)): ?>
    <div style="background:white;padding:40px;border-radius:12px;text-align:center;box-shadow:0 2px 10px rgba(0,0,0,0.06);">
        <div style="font-size:64px;margin-bottom:15px;">📭</div>
        <h2 style="color:#555;margin-bottom:10px;">No orders yet</h2>
        <p style="color:#888;margin-bottom:20px;">You haven't placed any orders. Browse our collection and place your first order!</p>
        <a href="/gold-shop/shop/index.php" class="btn">🛍️ Start Shopping</a>
    </div>
<?php else: ?>
    <table>
        <tr>
            <th>Order #</th>
            <th>Date</th>
            <th>Items</th>
            <th>Total</th>
            <th>Status</th>
        </tr>
        <?php foreach ($orders as $o): ?>
            <tr>
                <td>#<?= $o['id'] ?></td>
                <td><?= $o['created_at'] ?></td>
                <td><?= $o['item_count'] ?></td>
                <td>₹<?= number_format($o['total_amount'], 2) ?></td>
                <td>
                    <?php
                        $colors = [
                            'pending'   => '#f39c12',
                            'approved'  => '#3498db',
                            'completed' => '#27ae60',
                            'cancelled' => '#c0392b',
                        ];
                        $color = $colors[$o['status']] ?? '#888';
                    ?>
                    <span style="background:<?= $color ?>;color:white;padding:3px 10px;border-radius:12px;font-size:12px;text-transform:capitalize;">
                        <?= e($o['status']) ?>
                    </span>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>