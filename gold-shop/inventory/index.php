<?php include '../includes/header.php'; include '../includes/sidebar.php';
$rows = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<h1>Inventory</h1>
<a class="btn" href="add.php">➕ Add Product</a>
<table>
    <tr><th>ID</th><th>Name</th><th>Category</th><th>Weight(g)</th><th>Purity</th><th>₹/g</th><th>Stock</th><th>Actions</th></tr>
    <?php foreach ($rows as $r): ?>
        <tr>
            <td><?= $r['id'] ?></td>
            <td><?= htmlspecialchars($r['name']) ?></td>
            <td><?= htmlspecialchars($r['category']) ?></td>
            <td><?= $r['weight_grams'] ?></td>
            <td><?= htmlspecialchars($r['purity']) ?></td>
            <td><?= number_format($r['price_per_gram'],2) ?></td>
            <td><?= $r['stock_qty'] ?></td>
            <td><a href="update.php?id=<?= $r['id'] ?>">Update Stock</a></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php include '../includes/footer.php'; ?>