<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuickFix - Správa závad</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">QuickFix</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link" href="index.php">Přehled závad</a>
        </li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li class="nav-item">
              <a class="nav-link" href="nahlasit.php">Nahlásit závadu</a>
            </li>
        <?php endif; ?>
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
            <li class="nav-item">
              <a class="nav-link text-warning fw-bold" href="admin.php">Administrace / Správa</a>
            </li>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav">
        <?php if (isset($_SESSION['user_id'])): ?>
            <li class="nav-item">
              <span class="navbar-text me-3">Přihlášen: <strong><?= htmlspecialchars($_SESSION['user_jmeno']) ?></strong></span>
            </li>
            <li class="nav-item">
              <a class="btn btn-outline-light btn-sm" href="logout.php">Odhlásit se</a>
            </li>
        <?php else: ?>
            <li class="nav-item">
              <a class="nav-link" href="login.php">Přihlásit se</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="register.php">Registrace</a>
            </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<div class="container">