<?php
require_once __DIR__ . '/includes/functions.php';

$stmt = db()->prepare('SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON c.id = p.category_id WHERE p.id = ? AND p.is_available = 1');
$stmt->execute([(int) ($_GET['id'] ?? 0)]);
$p = $stmt->fetch();
if (!$p) {
    flash('warning', 'That product was not found.');
    header('Location: products.php');
    exit;
}
$pageTitle = $p['name'];
$rel = db()->prepare('SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON c.id = p.category_id WHERE p.category_id = ? AND p.id <> ? AND p.is_available = 1 LIMIT 4');
$rel->execute([$p['category_id'], $p['id']]);
$related = $rel->fetchAll();
require_once __DIR__ . '/includes/header.php';
?>
<section class="section">
  <div class="container">
    <nav aria-label="breadcrumb"><ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="index.php">Home</a></li>
      <li class="breadcrumb-item"><a href="products.php?category=<?= (int) $p['category_id'] ?>"><?= e($p['category_name']) ?></a></li>
      <li class="breadcrumb-item active"><?= e($p['name']) ?></li>
    </ol></nav>
    <div class="row g-4 align-items-center">
      <div class="col-md-6"><div class="detail-img"><img src="<?= image_url($p['image']) ?>" alt="<?= e($p['name']) ?>"></div></div>
      <div class="col-md-6">
        <small class="text-muted"><?= e($p['category_name']) ?></small>
        <h1 class="detail-title"><?= e($p['name']) ?></h1>
        <div class="product-price fs-3 my-2">
          <span class="price"><?= price($p['price']) ?></span>
          <?php if ($p['old_price']): ?><del><?= price($p['old_price']) ?></del><?php endif; ?>
        </div>
        <p><?= nl2br(e($p['description'])) ?></p>
        <form method="post" action="cart.php" class="d-flex flex-wrap gap-2 mt-3">
          <input type="hidden" name="action" value="add">
          <input type="hidden" name="product_id" value="<?= (int) $p['id'] ?>">
          <input type="hidden" name="redirect" value="product-details.php?id=<?= (int) $p['id'] ?>">
          <div class="qty-box">
            <button type="button" class="qty-btn" data-step="-1" aria-label="Decrease">−</button>
            <input type="number" name="qty" value="1" min="1" max="50" class="qty-input" aria-label="Quantity">
            <button type="button" class="qty-btn" data-step="1" aria-label="Increase">+</button>
          </div>
          <button class="btn btn-brown btn-lg" type="submit">Add to Cart</button>
          <a class="btn btn-whatsapp btn-lg" target="_blank" rel="noopener" href="<?= e(whatsapp_link('Hello Bharat Bakery, I want to order: ' . $p['name'] . ' (' . price($p['price']) . ')')) ?>">💬 WhatsApp</a>
        </form>
      </div>
    </div>
    <?php if ($related): ?>
      <h2 class="section-title mt-5">You may also like</h2>
      <div class="row g-3 g-lg-4 mt-1"><?php foreach ($related as $r) { product_card($r); } ?></div>
    <?php endif; ?>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
