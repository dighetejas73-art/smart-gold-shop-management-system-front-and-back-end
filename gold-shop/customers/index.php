<?php include '../includes/header.php'; include '../includes/sidebar.php';
$rows = $pdo->query("SELECT * FROM customers ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<h1>Customers</h1>
<a class="btn" href="add.php">➕ Add Customer</a>
<table>
    <tr><th>ID</th><th>Name</th><th>Phone</th><th>Email</th><th>Address</th><th>Actions</th></tr>
    <?php foreach ($rows as $r): ?>
        <tr>
            <td><?= $r['id'] ?></td>
            <td><?= htmlspecialchars($r['name']) ?></td>
            <td><?= htmlspecialchars($r['phone']) ?></td>
            <td><?= htmlspecialchars($r['email']) ?></td>
            <td><?= htmlspecialchars($r['address']) ?></td>
            <td>
                <a href="edit.php?id=<?= $r['id'] ?>">Edit</a> |
                <a href="delete.php?id=<?= $r['id'] ?>" onclick="return confirm('Delete?')">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php include '../includes/footer.php'; ?>