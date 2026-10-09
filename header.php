<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>F-Taxi Telecaller Assessment</title>
<link rel="stylesheet" href="<?= isset($base_path) ? $base_path : '' ?>assets/css/style.css?v=6">
</head>
<body>
<header class="topbar">
  <div class="brand"><img src="<?= isset($base_path) ? $base_path : '' ?>assets/images/ftaxi-logo.webp" alt="F-Taxi" class="brand-logo"><span>Telecaller Assessment</span></div>
  <nav>
    <?php if (!empty($_SESSION['employee_id'])): ?>
      <a href="<?= isset($base_path) ? $base_path : '' ?>employee/dashboard.php">Dashboard</a>
      <a href="<?= isset($base_path) ? $base_path : '' ?>logout.php">Logout</a>
    <?php elseif (!empty($_SESSION['admin_id'])): ?>
      <a href="<?= isset($base_path) ? $base_path : '' ?>admin/dashboard.php">Admin Dashboard</a>
      <a href="<?= isset($base_path) ? $base_path : '' ?>logout.php">Logout</a>
    <?php endif; ?>
  </nav>
</header>
<main class="container">
