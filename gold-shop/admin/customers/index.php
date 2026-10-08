<?php
$pageTitle = 'Customers';
require_once __DIR__ . '/../../config/db.php';
requireRole('admin');

$rows = $pdo->query("SELECT * FROM customers ORDER BY id DESC")->fetchAll();

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<h1>👥 Customers</h1>
<a class="btn" href="add.php">➕ Add Customer</a>

<?php if ($rows): ?>
<table>
    <tr><th>ID</th><th>Name</th><th>Phone</th><th>Email</th><th>Address</th><th>Actions</th></tr>
    <?php foreach ($rows as $r): ?>
        <tr>
            <td><?= $r['id'] ?></td>
            <td><?= e($r['name']) ?></td>
            <td><?= e($r['phone']) ?></td>
            <td><?= e($r['email']) ?></td>
            <td><?= e($r['address']) ?></td>
            <td>
                <a href="edit.php?id=<?= $r['id'] ?>">Edit</a>
                <a href="delete.php?id=<?= $r['id'] ?>" onclick="return confirm('Delete this customer?')" style="color:#c0392b;">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
    <p style="color:#888;padding:20px;background:white;border-radius:8px;margin-top:15px;">No customers yet.</p>
<?php endif; ?>

<?php include __DIR__ . '/../../includes/footer.php'; ?>