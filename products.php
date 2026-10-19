<?php
$pageTitle = 'Our Products';
require_once __DIR__ . '/includes/header.php';

$categories = db()->query('SELECT * FROM categories ORDER BY sort_order')->fetchAll();
$catId  = (int) ($_GET['category'] ?? 0);
$search = trim($_GET['q'] ?? '');

$sql = 'SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON c.id = p.category_id WHERE p.is_available = 1';
$params = [];
if ($catId > 0) { $sql .= ' AND p.category_id = ?'; $params[] = $catId; }
if ($search !== '') { $sql .= ' AND (p.name LIKE ? OR p.description LIKE ?)'; $params[] = "%$search%"; $params[] = "%$search%"; }
$sql .= ' ORDER BY c.sort_order, p.name';
$stmt = db()->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>
<header class="page-banner"><div class="container"><h1>Our Products</h1><p>Freshly baked, priced in rupees, ready to order.</p></div></header>
<section class="section">
  <div class="container">
    <form class="row g-2 mb-3" method="get">
      <?php if ($catId): ?><input type="hidden" name="category" value="<?= $catId ?>"><?php endif; ?>
      <div class="col-sm-9 col-md-10"><input type="search" name="q" value="<?= e($search) ?>" class="form-control" placeholder="Search cakes, bread, cookies..."></div>
      <div class="col-sm-3 col-md-2"><button class="btn btn-brown w-100">Search</button></div>
    </form>
    <div class="filter-pills mb-4">
      <a href="products.php" class="pill <?= $catId === 0 ? 'active' : '' ?>">All</a>
      <?php foreach ($categories as $c): ?>
        <a href="products.php?category=<?= (int) $c['id'] ?>" class="pill <?= $catId === (int) $c['id'] ? 'active' : '' ?>"><?= e($c['icon'] . ' ' . $c['name']) ?></a>
      <?php endforeach; ?>
    </div>
    <?php if (!$products): ?>
      <div class="empty-state"><p>No products found. Try another category or search word.</p><a href="products.php" class="btn btn-brown">Show all products</a></div>
    <?php else: ?>
      <div class="row g-3 g-lg-4"><?php foreach ($products as $p) { product_card($p); } ?></div>
    <?php endif; ?>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
