<?php
require_once '../config/db.php'; requireLogin();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO products (name, category, weight_grams, purity, price_per_gram, stock_qty) VALUES (?,?,?,?,?,?)");
    $stmt->execute([$_POST['name'], $_POST['category'], $_POST['weight_grams'], $_POST['purity'], $_POST['price_per_gram'], $_POST['stock_qty']]);
    header("Location: index.php"); exit;
}
include '../includes/header.php'; include '../includes/sidebar.php';
?>
<h1>Add Product</h1>
<form method="POST" class="form">
    <input name="name" placeholder="Product Name" required>
    <input name="category" placeholder="Category (Ring, Chain...)">
    <input name="weight_grams" type="number" step="0.01" placeholder="Weight (g)" required>
    <input name="purity" placeholder="Purity (22K, 24K...)">
    <input name="price_per_gram" type="number" step="0.01" placeholder="Price per gram" required>
    <input name="stock_qty" type="number" placeholder="Stock Quantity" value="1" required>
    <button type="submit">Save</button>
</form>
<?php include '../includes/footer.php'; ?>