<?php
require_once 'includes/data.php';
$id = (int)($_GET['id'] ?? 0);
if (!isset($products[$id])) { header('Location: index.php'); exit; }
$p = $products[$id];
$title = $p[0];
require 'includes/header.php';
$related = array_filter($products, fn($x,$k)=>$x[1]===$p[1] && $k!==$id, ARRAY_FILTER_USE_BOTH);
$related = $related ?: array_slice(array_diff_key($products,[$id=>1]),0,3,true);
?>
<main class="wrap">
  <p class="crumbs"><a href="index.php">Početna</a> / <a href="index.php?cat=<?= urlencode($p[1]) ?>"><?= e($p[1]) ?></a> / <?= e($p[0]) ?></p>
  <div class="pdp">
    <?= product_img($id,$p,'ph big') ?>
    <div class="info">
      <h1><?= e($p[0]) ?></h1>
      <p class="price lg"><?= money($p[2]) ?> <?php if($p[3]): ?><s><?= money($p[3]) ?></s><?php endif; ?></p>
      <p class="desc"><?= e($p[6]) ?></p>
      <form method="post" action="cart.php">
        <input type="hidden" name="action" value="add">
        <input type="hidden" name="id" value="<?= $id ?>">
        <fieldset class="sizes"><legend>Veličina</legend>
          <?php foreach($sizeOf($p[1]) as $i=>$s): ?>
            <label><input type="radio" name="size" value="<?= e($s) ?>" <?= $i===1?'checked':'' ?>><span><?= e($s) ?></span></label>
          <?php endforeach; ?>
        </fieldset>
        <div class="qtyrow">
          <input type="number" name="qty" value="1" min="1" max="10" aria-label="Količina">
          <button class="btn full" type="submit">Dodaj u korpu</button>
        </div>
      </form>
      <ul class="facts">
        <li>Dostava za 2–4 radna dana</li>
        <li>Besplatan povrat u roku od 30 dana</li>
        <li>Preporuka za pranje: 30°, na naličju</li>
      </ul>
    </div>
  </div>
  <h2 class="sec">Uz ovo ide</h2>
  <div class="grid"><?php foreach(array_slice($related,0,4,true) as $k=>$x) echo card($k,$x); ?></div>
</main>
<?php require 'includes/footer.php'; ?>
