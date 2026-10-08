<?php
require_once '../config/db.php'; requireLogin();
$customers = $pdo->query("SELECT * FROM customers ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
$products  = $pdo->query("SELECT * FROM products WHERE stock_qty > 0 ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customerId = (int)$_POST['customer_id'];
    $productId  = (int)$_POST['product_id'];
    $qty        = (int)$_POST['quantity'];

    // fetch product
    $p = $pdo->prepare("SELECT * FROM products WHERE id=?"); $p->execute([$productId]);
    $p = $p->fetch(PDO::FETCH_ASSOC);

    if ($p && $p['stock_qty'] >= $qty) {
        $total = $p['price_per_gram'] * $p['weight_grams'] * $qty;

        $pdo->beginTransaction();
        $pdo->prepare("INSERT INTO sales (customer_id, product_id, quantity, total_amount) VALUES (?,?,?,?)")
            ->execute([$customerId, $productId, $qty, $total]);
        $pdo->prepare("UPDATE products SET stock_qty = stock_qty - ? WHERE id=?")
            ->execute([$qty, $productId]);
        $pdo->commit();

        header("Location: history.php"); exit;
    } else {
        $error = "Not enough stock!";
    }
}
include '../includes/header.php'; include '../includes/sidebar.php';
?>
<h1>New Sale</h1>
<?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
<form method="POST" class="form">
    <select name="customer_id" required>
        <option value="">-- Select Customer --</option>
        <?php foreach ($customers as $c): ?>
            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
        <?php endforeach; ?>
    </select>
    <select name="product_id" required>
        <option value="">-- Select Product --</option>
        <?php foreach ($products as $p): ?>
            <option value="<?= $p['id'] ?>">
                <?= htmlspecialchars($p['name']) ?> 
                (<?= $p['weight_grams'] ?>g, ₹<?= $p['price_per_gram'] ?>/g, Stock: <?= $p['stock_qty'] ?>)
            </option>
        <?php endforeach; ?>
    </select>
    <input name="quantity" type="number" value="1" min="1" required>
    <button type="submit">Complete Sale</button>
</form>
<?php include '../includes/footer.php'; ?>