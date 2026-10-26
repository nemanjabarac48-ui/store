<?php
require_once 'includes/data.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $a = $_POST['action'] ?? '';
  $id = (int)($_POST['id'] ?? 0);
  if ($a === 'add' && isset($products[$id])) {
    $size = in_array($_POST['size'] ?? '', $sizeOf($products[$id][1]), true) ? $_POST['size'] : $sizeOf($products[$id][1])[0];
    $key = "$id|$size";
    $_SESSION['cart'][$key] = min(10, ($_SESSION['cart'][$key] ?? 0) + max(1,(int)($_POST['qty'] ?? 1)));
  }
  if ($a === 'update') foreach ((array)($_POST['qty'] ?? []) as $k=>$q) {
    if ((int)$q < 1) unset($_SESSION['cart'][$k]); elseif (isset($_SESSION['cart'][$k])) $_SESSION['cart'][$k] = min(10,(int)$q);
  }
  if ($a === 'remove') unset($_SESSION['cart'][$_POST['key'] ?? '']);
  header('Location: cart.php'); exit;
}
$title = 'Korpa';
require 'includes/header.php';
?>
<main class="wrap narrow">
  <h2 class="sec">Vaša korpa</h2>
  <?php if(!cart()): ?>
    <div class="empty"><p>Korpa je prazna.</p><a class="btn" href="index.php#shop">Izaberi proizvode</a></div>
  <?php else: ?>
  <div class="cartgrid">
    <form method="post" id="upd">
      <input type="hidden" name="action" value="update">
      <?php foreach(cart() as $k=>$q): [$id,$size] = explode('|',$k); $p = $products[(int)$id]; ?>
      <div class="line">
        <?= product_img((int)$id,$p,'ph sm') ?>
        <div><a href="product.php?id=<?= (int)$id ?>"><b><?= e($p[0]) ?></b></a><br><small>Veličina: <?= e($size) ?></small><br><small><?= money($p[2]) ?></small></div>
        <input type="number" name="qty[<?= e($k) ?>]" value="<?= $q ?>" min="0" max="10" aria-label="Količina">
        <b><?= money($p[2]*$q) ?></b>
        <button class="x" form="rm<?= md5($k) ?>" aria-label="Ukloni">×</button>
      </div>
      <?php endforeach; ?>
      <button class="btn ghost" type="submit">Ažuriraj količine</button>
    </form>
    <?php foreach(cart() as $k=>$q): ?>
      <form method="post" id="rm<?= md5($k) ?>"><input type="hidden" name="action" value="remove"><input type="hidden" name="key" value="<?= e($k) ?>"></form>
    <?php endforeach; ?>
    <aside class="sum">
      <h3>Pregled</h3>
      <div><span>Proizvodi</span><span><?= money(cart_sub()) ?></span></div>
      <div><span>Dostava</span><span><?= cart_ship() ? money(cart_ship()) : 'Besplatno' ?></span></div>
      <div class="tot"><span>Ukupno</span><span><?= money(cart_sub()+cart_ship()) ?></span></div>
      <?php if(cart_ship()): ?><small>Još <?= money(FREE_SHIP-cart_sub()) ?> do besplatne dostave.</small><?php endif; ?>
      <a class="btn full" href="checkout.php">Nastavi na plaćanje</a>
    </aside>
  </div>
  <?php endif; ?>
</main>
<?php require 'includes/footer.php'; ?>
