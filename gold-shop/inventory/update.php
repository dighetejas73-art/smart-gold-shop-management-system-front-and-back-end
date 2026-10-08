<?php
require_once '../config/db.php'; requireLogin();
$id = (int)($_GET['id'] ?? 0);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE products SET stock_qty=? WHERE id=?");
    $stmt->execute([$_POST['stock_qty'], $id]);
    header("Location: index.php"); exit;
}
$p = $pdo->prepare("SELECT * FROM products WHERE id=?"); $p->execute([$id]);
$p = $p->fetch(PDO::FETCH_ASSOC);
if (!$p) die("Product not found");
include '../includes/header.php'; include '../includes/sidebar.php';
?>
<h1>Update Stock — <?= htmlspecialchars($p['name']) ?></h1>
<form method="POST" class="form">
    <input name="stock_qty" type="number" value="<?= $p['stock_qty'] ?>" required>
    <button type="submit">Update</button>
</form>
<?php include '../includes/footer.php'; ?>