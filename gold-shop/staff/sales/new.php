<?php
$pageTitle = 'New Sale';
require_once __DIR__ . '/../../config/db.php';
requireRole('staff');

$customers = $pdo->query("SELECT * FROM customers ORDER BY name")->fetchAll();
$products  = $pdo->query("SELECT * FROM products WHERE stock_qty > 0 ORDER BY name")->fetchAll();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customerId = (int)$_POST['customer_id'];
    $productId  = (int)$_POST['product_id'];
    $qty        = (int)$_POST['quantity'];

    if ($qty < 1) {
        $error = "Quantity must be at least 1.";
    } else {
        // Fetch product
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id=?");
        $stmt->execute([$productId]);
        $p = $stmt->fetch();

        if (!$p) {
            $error = "Product not found.";
        } elseif ($p['stock_qty'] < $qty) {
            $error = "Not enough stock! Only {$p['stock_qty']} available.";
        } else {
            $total = $p['price_per_gram'] * $p['weight_grams'] * $qty;

            $pdo->beginTransaction();
            try {
                $pdo->prepare("INSERT INTO sales (customer_id, product_id, quantity, total_amount) VALUES (?,?,?,?)")
                    ->execute([$customerId, $productId, $qty, $total]);

                $pdo->prepare("UPDATE products SET stock_qty = stock_qty - ? WHERE id=?")
                    ->execute([$qty, $productId]);

                $pdo->commit();
                $success = "Sale recorded! Total: ₹" . number_format($total, 2);
            } catch (Exception $e) {
                $pdo->rollBack();
                $error = "Sale failed: " . $e->getMessage();
            }
        }
    }
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<h1>💰 New Sale</h1>

<?php if ($error): ?>
    <div style="background:#f8d7da;color:#721c24;padding:12px;border-radius:6px;margin-bottom:15px;">
        ❌ <?= e($error) ?>
    </div>
<?php endif; ?>
<?php if ($success): ?>
    <div style="background:#d4edda;color:#155724;padding:12px;border-radius:6px;margin-bottom:15px;">
        ✅ <?= e($success) ?>
    </div>
<?php endif; ?>

<?php if (empty($customers) || empty($products)): ?>
    <div style="background:#fff3cd;color:#856404;padding:15px;border-radius:8px;">
        ⚠️ You need at least one customer and one product in stock to record a sale.
        <br>Customers: <b><?= count($customers) ?></b> | Products in stock: <b><?= count($products) ?></b>
    </div>
<?php else: ?>
    <form method="POST" class="form" style="max-width:600px;">
        <label style="font-weight:600;color:#555;font-size:13px;">Customer *</label>
        <select name="customer_id" required>
            <option value="">-- Select Customer --</option>
            <?php foreach ($customers as $c): ?>
                <option value="<?= $c['id'] ?>"><?= e($c['name']) ?> (<?= e($c['phone']) ?>)</option>
            <?php endforeach; ?>
        </select>

        <label style="font-weight:600;color:#555;font-size:13px;">Product *</label>
        <select name="product_id" required>
            <option value="">-- Select Product --</option>
            <?php foreach ($products as $p): ?>
                <option value="<?= $p['id'] ?>">
                    <?= e($p['name']) ?> — <?= $p['weight_grams'] ?>g — ₹<?= number_format($p['price_per_gram']*$p['weight_grams'], 2) ?> (stock: <?= $p['stock_qty'] ?>)
                </option>
            <?php endforeach; ?>
        </select>

        <label style="font-weight:600;color:#555;font-size:13px;">Quantity *</label>
        <input name="quantity" type="number" value="1" min="1" required>

        <button type="submit">💾 Record Sale</button>
    </form>
<?php endif; ?>

<?php include __DIR__ . '/../../includes/footer.php'; ?>