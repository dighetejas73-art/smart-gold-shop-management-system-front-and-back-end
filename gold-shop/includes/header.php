<?php
require_once __DIR__ . '/../config/db.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gold Shop — <?= e($pageTitle ?? 'Dashboard') ?></title>
    <link rel="stylesheet" href="/gold-shop/assets/style.css">
</head>
<body>
<div class="app">