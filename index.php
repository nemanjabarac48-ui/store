<?php
$title = 'Muška odjeća';
require 'includes/header.php';
$cat = $_GET['cat'] ?? '';
$sort = $_GET['sort'] ?? '';
$list = array_filter($products, fn($p) => !$cat || $p[1] === $cat);
if ($sort === 'asc') uasort($list, fn($a,$b)=>$a[2]<=>$b[2]);
if ($sort === 'desc') uasort($list, fn($a,$b)=>$b[2]<=>$a[2]);
?>
<?php if(!$cat): ?>
<section class="hero">
  <div class="hero-text">
    <h1>Odjeća sa<br>pravim krojem.</h1>
    <p>Sakoi, košulje i pletivo od prirodnih materijala. Jednostavni komadi koji se kombinuju međusobno i traju.</p>
    <a class="btn" href="#shop">Pogledaj kolekciju</a>
  </div>
  <div class="hero-art" aria-hidden="true">
    <div style="background:#2f3640"></div><div style="background:#c9b99a"></div><div style="background:#5b1f2b"></div>
  </div>
</section>
<section class="strip">
  <div><b>Besplatna dostava</b><span>preko <?= FREE_SHIP ?> <?= CUR ?></span></div>
  <div><b>Povrat 30 dana</b><span>bez objašnjavanja</span></div>
  <div><b>Plaćanje pouzećem</b><span>platite kad stigne</span></div>
</section>
<?php endif; ?>

<main id="shop" class="wrap">
  <div class="bar">
    <h2><?= $cat ? e($cat) : 'Kolekcija' ?> <small><?= count($list) ?> proizvoda</small></h2>
    <form method="get">
      <?php if($cat): ?><input type="hidden" name="cat" value="<?= e($cat) ?>"><?php endif; ?>
      <select name="sort" onchange="this.form.submit()" aria-label="Sortiraj">
        <option value="">Sortiraj</option>
        <option value="asc" <?= $sort==='asc'?'selected':'' ?>>Cijena: niža prvo</option>
        <option value="desc" <?= $sort==='desc'?'selected':'' ?>>Cijena: viša prvo</option>
      </select>
    </form>
  </div>
  <div class="grid">
    <?php foreach($list as $id=>$p) echo card($id,$p); ?>
  </div>
</main>
<?php require 'includes/footer.php'; ?>
