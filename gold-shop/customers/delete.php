<?php
require_once '../config/db.php'; requireLogin();
$id = (int)($_GET['id'] ?? 0);
$pdo->prepare("DELETE FROM customers WHERE id=?")->execute([$id]);
header("Location: index.php"); exit;