<?php require_once __DIR__.'/data.php'; ?>
<!DOCTYPE html>
<html lang="sr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title ?? 'Muška odjeća') ?> — <?= STORE ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,600&family=Instrument+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="topbar">Besplatna dostava za porudžbine preko <?= FREE_SHIP ?> <?= CUR ?> · Povrat u roku od 30 dana</div>
<header class="head">
  <a class="logo" href="index.php"><?= STORE ?></a>
  <nav>
    <a href="index.php#shop">Sve</a>
    <?php foreach($categories as $c): ?><a href="index.php?cat=<?= urlencode($c) ?>#shop"><?= e($c) ?></a><?php endforeach; ?>
  </nav>
  <a class="cartlink" href="cart.php">Korpa <b><?= cart_count() ?></b></a>
</header>
