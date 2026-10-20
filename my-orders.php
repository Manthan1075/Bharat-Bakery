<?php
require_once __DIR__ . '/includes/functions.php';

if (empty($_SESSION['user_id'])) {
    $_SESSION['after_login'] = 'my-orders.php';
    header('Location: login.php');
    exit;
}

$userId = (int) $_SESSION['user_id'];
$stmt = db()->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC');
$stmt->execute([$userId]);
$orders = $stmt->fetchAll();

$itemsByOrder = [];
if ($orders) {
    $itemStmt = db()->prepare(
        'SELECT product_name, price, quantity FROM order_items WHERE order_id = ? ORDER BY id'
    );
    foreach ($orders as $order) {
        $itemStmt->execute([(int) $order['id']]);
        $itemsByOrder[(int) $order['id']] = $itemStmt->fetchAll();
    }
}

$pageTitle = 'My Orders';
require_once __DIR__ . '/includes/header.php';
?>

<header class="page-banner">
  <div class="container">
    <h1>My Orders</h1>
    <p>Hi <?= e($_SESSION['user_name'] ?? 'there') ?>, view your Bharat Bakery orders here.</p>
  </div>
</header>

<section class="section">
  <div class="container" style="max-width: 1000px">
    <?php if (!$orders): ?>
      <div class="empty-state">
        <div class="display-3 mb-3" aria-hidden="true">🧁</div>
        <h2>No orders yet</h2>
        <p>Your orders placed while logged in will appear here.</p>
        <a href="products.php" class="btn btn-brown btn-lg">Browse products</a>
      </div>
    <?php else: ?>
      <?php foreach ($orders as $order): ?>
        <article class="summary-card mb-4">
          <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
            <div>
              <p class="text-muted mb-1">Order number</p>
              <h2 class="h4 mb-1"><?= e($order['order_number']) ?></h2>
              <?php if (!empty($order['created_at'])): ?>
                <p class="text-muted mb-0">Placed on <?= e(date('d M Y, h:i A', strtotime($order['created_at']))) ?></p>
              <?php endif; ?>
            </div>
            <div class="text-sm-end">
              <span class="badge text-bg-warning">Order received</span>
              <div class="price fw-bold fs-5 mt-2"><?= price($order['total']) ?></div>
            </div>
          </div>

          <hr>
          <div class="table-responsive">
            <table class="table align-middle mb-0">
              <thead>
                <tr><th>Product</th><th class="text-end">Price</th><th class="text-center">Qty</th><th class="text-end">Subtotal</th></tr>
              </thead>
              <tbody>
                <?php foreach ($itemsByOrder[(int) $order['id']] ?? [] as $item): ?>
                  <tr>
                    <td><?= e($item['product_name']) ?></td>
                    <td class="text-end"><?= price($item['price']) ?></td>
                    <td class="text-center"><?= (int) $item['quantity'] ?></td>
                    <td class="text-end"><?= price((float) $item['price'] * (int) $item['quantity']) ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <div class="row g-3 mt-2">
            <div class="col-md-6">
              <p class="text-muted mb-1">Delivery address</p>
              <div><?= nl2br(e($order['address'])) ?></div>
            </div>
            <div class="col-md-6">
              <p class="text-muted mb-1">Contact</p>
              <div><?= e($order['customer_name']) ?> · <?= e($order['phone']) ?></div>
            </div>
            <?php if (!empty($order['notes'])): ?>
              <div class="col-12"><p class="text-muted mb-1">Order notes</p><div><?= nl2br(e($order['notes'])) ?></div></div>
            <?php endif; ?>
          </div>
        </article>
      <?php endforeach; ?>
    <?php endif; ?>

    <div class="d-flex flex-wrap gap-2">
      <a href="products.php" class="btn btn-brown">Continue shopping</a>
      <a href="index.php" class="btn btn-outline-secondary">Back to home</a>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
