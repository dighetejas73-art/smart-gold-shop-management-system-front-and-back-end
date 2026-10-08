<?php
require_once __DIR__ . '/../../config/db.php';
requireRole('admin');

$id = (int)($_GET['id'] ?? 0);
$pdo->prepare("DELETE FROM customers WHERE id=?")->execute([$id]);
header("Location: index.php"); exit;