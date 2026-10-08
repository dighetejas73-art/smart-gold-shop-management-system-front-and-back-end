<?php
$pageTitle = 'Shop';
require_once __DIR__ . '/../config/db.php';
requireLogin();
requireRole('customer');

$products = $pdo->query("SELECT * FROM products WHERE stock_qty > 0 ORDER BY id DESC")->fetchAll();

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
?>

<h1>🛍️ Shop Our Gold Collection</h1>

<?php if ($products): ?>
    <div class="product-grid">
        <?php foreach ($products as $p): ?>
            <div class="product-card">
                <div class="product-thumb">💍</div>
                <h3><?= e($p['name']) ?></h3>
                <p class="category"><?= e($p['category']) ?> • <?= e($p['purity']) ?></p>
                <p class="weight">Weight: <b><?= $p['weight_grams'] ?> g</b></p>
                <p class="price">₹<?= number_format($p['price_per_gram'] * $p['weight_grams'], 2) ?></p>
                <p class="stock"><?= $p['stock_qty'] ?> in stock</p>
                <button class="btn-buy" onclick="alert('Order feature coming soon!')">Order Now</button>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p style="color:#888;padding:20px;background:white;border-radius:8px;">
        No products available at the moment.
    </p>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>