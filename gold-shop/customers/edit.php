<?php
require_once '../config/db.php'; requireLogin();
$id = (int)($_GET['id'] ?? 0);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE customers SET name=?, phone=?, email=?, address=? WHERE id=?");
    $stmt->execute([$_POST['name'], $_POST['phone'], $_POST['email'], $_POST['address'], $id]);
    header("Location: index.php"); exit;
}
$c = $pdo->prepare("SELECT * FROM customers WHERE id=?"); $c->execute([$id]);
$c = $c->fetch(PDO::FETCH_ASSOC);
if (!$c) die("Customer not found");
include '../includes/header.php'; include '../includes/sidebar.php';
?>
<h1>Edit Customer</h1>
<form method="POST" class="form">
    <input name="name" value="<?= htmlspecialchars($c['name']) ?>" required>
    <input name="phone" value="<?= htmlspecialchars($c['phone']) ?>">
    <input name="email" value="<?= htmlspecialchars($c['email']) ?>">
    <textarea name="address"><?= htmlspecialchars($c['address']) ?></textarea>
    <button type="submit">Update</button>
</form>
<?php include '../includes/footer.php'; ?>