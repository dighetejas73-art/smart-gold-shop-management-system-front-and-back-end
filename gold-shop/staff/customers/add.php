<?php
$pageTitle = 'Add Customer';
require_once __DIR__ . '/../../config/db.php';
requireRole('staff');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO customers (name, phone, email, address) VALUES (?,?,?,?)");
    $stmt->execute([
        trim($_POST['name']),
        trim($_POST['phone']),
        trim($_POST['email']),
        trim($_POST['address']),
    ]);
    header("Location: index.php");
    exit;
}

include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/sidebar.php';
?>

<h1>➕ Add Customer</h1>
<a class="btn" href="index.php">← Back to List</a>

<form method="POST" class="form">
    <label style="font-weight:600;color:#555;font-size:13px;">Name *</label>
    <input name="name" placeholder="Full name" required>

    <label style="font-weight:600;color:#555;font-size:13px;">Phone</label>
    <input name="phone" placeholder="+91 98765 43210">

    <label style="font-weight:600;color:#555;font-size:13px;">Email</label>
    <input name="email" type="email" placeholder="customer@example.com">

    <label style="font-weight:600;color:#555;font-size:13px;">Address</label>
    <textarea name="address" placeholder="Street, city, pincode"></textarea>

    <button type="submit">💾 Save Customer</button>
</form>

<?php include __DIR__ . '/../../includes/footer.php'; ?>