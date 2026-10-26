<?php
session_start();
const STORE = 'KROJ';
const CUR = '€';
const FREE_SHIP = 100;
const SHIP = 4.5;

// Proizvodi: [naziv, kategorija, cijena, stara cijena (0 = nema), boja, oznaka, opis]
// Slike: stavi fajl u assets/img/ (npr. 1.jpg) — ako ga nema, prikazuje se boja proizvoda.
$products = [
 1 => ['Slim fit sako, antracit','Odijela',189,229,'#2f3640','Novo','Sako od vunene mješavine, polu-podstavljen, sa dva dugmeta.'],
 2 => ['Vunene pantalone, tamno plave','Pantalone',79,0,'#1c2a44','','Klasičan kroj sa pregibom, visoki struk i fini sjaj tkanine.'],
 3 => ['Košulja Oxford, bijela','Košulje',44,0,'#e8e8e4','Bestseler','Pamuk Oxford, button-down okovratnik, kroj koji se ne gužva.'],
 4 => ['Lanena košulja, pješčana','Košulje',52,65,'#c9b99a','Akcija','Lagan lan za ljeto, opušten ali uredan izgled.'],
 5 => ['Polo majica, maslinasta','Majice',36,0,'#59603f','','Pique pamuk, dva dugmeta, vezeni detalj na grudima.'],
 6 => ['Majica od pamuka, crna','Majice',22,0,'#16181c','Bestseler','Gust pamuk 220 g, ravan kroj, ne gubi oblik nakon pranja.'],
 7 => ['Džemper merino, bordo','Pletivo',69,89,'#5b1f2b','Akcija','Fini merino, okrugli izrez, topao a lagan.'],
 8 => ['Kardigan sa dugmadima, siva','Pletivo',74,0,'#8d9096','','Debelo pletivo, drvena dugmad, dva džepa.'],
 9 => ['Chino pantalone, bež','Pantalone',58,0,'#b8a687','','Elastični pamuk, kroj slim-tapered.'],
 10 => ['Jeans, tamno indigo','Pantalone',64,0,'#22304a','Novo','Selvedge denim, ravna nogavica, bez pretjeranog pranja.'],
 11 => ['Vuneni kaput, ugljeno crni','Jakne',219,0,'#1d1f23','Novo','Dugačak kaput od vune i kašmira, jednoredno zakopčavanje.'],
 12 => ['Bomber jakna, zelena','Jakne',98,129,'#2e4636','Akcija','Lagana jakna sa rebrastim manžetama i skrivenim džepom.'],
];
$categories = ['Odijela','Košulje','Majice','Pletivo','Pantalone','Jakne'];
$sizeOf = fn($cat) => $cat==='Pantalone' ? ['30','32','34','36','38'] : ($cat==='Odijela' ? ['46','48','50','52','54'] : ['S','M','L','XL','XXL']);

function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function money($n){ return number_format($n, 2, ',', '.') . ' ' . CUR; }
function cart(){ return $_SESSION['cart'] ?? []; }
function cart_count(){ return array_sum(cart()); }
function cart_sub(){ global $products; $t=0; foreach(cart() as $k=>$q){ $t += $products[(int)explode('|',$k)[0]][2]*$q; } return $t; }
function cart_ship(){ $s=cart_sub(); return ($s==0||$s>=FREE_SHIP)?0:SHIP; }
function product_img($id,$p,$class='ph'){
  $f = "assets/img/$id.jpg";
  $bg = file_exists(__DIR__."/../$f") ? "background-image:url('$f');" : '';
  $badge = $p[5] ? '<span class="badge">'.e($p[5]).'</span>' : '';
  return '<div class="'.$class.'" style="background-color:'.e($p[4]).';'.$bg.'">'.$badge.'</div>';
}
function card($id,$p){
  $old = $p[3] ? '<s>'.money($p[3]).'</s>' : '';
  return '<a class="card" href="product.php?id='.$id.'">'.product_img($id,$p).
   '<div class="meta"><span class="cat">'.e($p[1]).'</span><h3>'.e($p[0]).'</h3><p class="price">'.money($p[2]).' '.$old.'</p></div></a>';
}
