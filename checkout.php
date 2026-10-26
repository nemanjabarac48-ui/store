<?php
require_once 'includes/data.php';
$done = $_SESSION['done'] ?? null;
if (!cart() && !$done) { header('Location: cart.php'); exit; }
$errors = []; $v = ['name'=>'','email'=>'','phone'=>'','address'=>'','city'=>'','note'=>''];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && cart()) {
  foreach ($v as $k=>$_) $v[$k] = trim($_POST[$k] ?? '');
  if (mb_strlen($v['name']) < 3) $errors['name'] = 'Upiši ime i prezime.';
  if (!filter_var($v['email'], FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Upiši ispravnu email adresu.';
  if (!preg_match('/^[+0-9 \/-]{6,20}$/', $v['phone'])) $errors['phone'] = 'Upiši ispravan broj telefona.';
  if (mb_strlen($v['address']) < 5) $errors['address'] = 'Upiši adresu za dostavu.';
  if (mb_strlen($v['city']) < 2) $errors['city'] = 'Upiši grad.';
  if (!$errors) {
    $items = [];
    foreach (cart() as $k=>$q) { [$id,$s] = explode('|',$k); $items[] = ['id'=>(int)$id,'name'=>$products[(int)$id][0],'size'=>$s,'qty'=>$q,'price'=>$products[(int)$id][2]]; }
    $order = ['no'=>strtoupper(substr(uniqid(),-6)),'date'=>date('c'),'customer'=>$v,'items'=>$items,'total'=>cart_sub()+cart_ship()];
    file_put_contents(__DIR__.'/orders/orders.jsonl', json_encode($order, JSON_UNESCAPED_UNICODE)."\n", FILE_APPEND|LOCK_EX);
    $_SESSION['cart'] = []; $_SESSION['done'] = $order['no'];
    header('Location: checkout.php'); exit;
  }
}
$title = 'Plaćanje';
require 'includes/header.php';
?>
<main class="wrap narrow">
<?php if($done && !cart()): unset($_SESSION['done']); ?>
  <div class="empty"><h2 class="sec">Hvala na porudžbini!</h2><p>Broj porudžbine: <b>#<?= e($done) ?></b>. Potvrdu šaljemo na vaš email, a paket stiže za 2–4 radna dana.</p><a class="btn" href="index.php">Nazad u prodavnicu</a></div>
<?php else: ?>
  <h2 class="sec">Podaci za dostavu</h2>
  <div class="cartgrid">
    <form method="post" class="form" novalidate>
      <?php foreach(['name'=>'Ime i prezime','email'=>'Email','phone'=>'Telefon','address'=>'Adresa','city'=>'Grad'] as $k=>$l): ?>
      <label><?= $l ?>
        <input name="<?= $k ?>" value="<?= e($v[$k]) ?>" class="<?= isset($errors[$k])?'err':'' ?>" <?= $k==='email'?'type="email"':'' ?>>
        <?php if(isset($errors[$k])): ?><em><?= e($errors[$k]) ?></em><?php endif; ?>
      </label>
      <?php endforeach; ?>
      <label>Napomena (nije obavezno)<textarea name="note" rows="3"><?= e($v['note']) ?></textarea></label>
      <p class="pay"><b>Plaćanje pouzećem</b> — platite kuriru prilikom preuzimanja.</p>
      <button class="btn full" type="submit">Potvrdi porudžbinu</button>
    </form>
    <aside class="sum">
      <h3>Vaša porudžbina</h3>
      <?php foreach(cart() as $k=>$q): [$id,$s]=explode('|',$k); $p=$products[(int)$id]; ?>
        <div><span><?= e($p[0]) ?> (<?= e($s) ?>) × <?= $q ?></span><span><?= money($p[2]*$q) ?></span></div>
      <?php endforeach; ?>
      <div><span>Dostava</span><span><?= cart_ship()?money(cart_ship()):'Besplatno' ?></span></div>
      <div class="tot"><span>Ukupno</span><span><?= money(cart_sub()+cart_ship()) ?></span></div>
    </aside>
  </div>
<?php endif; ?>
</main>
<?php require 'includes/footer.php'; ?>
