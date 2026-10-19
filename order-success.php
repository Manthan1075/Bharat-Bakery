<?php
require_once __DIR__ . '/includes/functions.php';

$orderNo = $_GET['order'] ?? '';
if ($orderNo === '' || ($_SESSION['last_order'] ?? '') !== $orderNo) {
    header('Location: index.php');
    exit;
}
$stmt = db()->prepare('SELECT * FROM orders WHERE order_number = ?');
$stmt->execute([$orderNo]);
$order = $stmt->fetch();
if (!$order) { header('Location: index.php'); exit; }
$itemsStmt = db()->prepare('SELECT * FROM order_items WHERE order_id = ?');
$itemsStmt->execute([$order['id']]);
$items = $itemsStmt->fetchAll();

$lines = ["New order {$order['order_number']} - Bharat Bakery", ''];
foreach ($items as $i) { $lines[] = "{$i['quantity']} x {$i['product_name']} = " . price($i['price'] * $i['quantity']); }
$lines[] = '';
$lines[] = 'Total: ' . price($order['total']);
$lines[] = 'Name: ' . $order['customer_name'];
$lines[] = 'Phone: ' . $order['phone'];
$lines[] = 'Address: ' . $order['address'];
if ($order['notes'] !== '') { $lines[] = 'Notes: ' . $order['notes']; }
$waUrl = whatsapp_link(implode("\n", $lines));

$pageTitle = 'Order Confirmed';
require_once __DIR__ . '/includes/header.php';
?>
<section class="section">
  <div class="container" style="max-width:820px">
    <div class="success-card text-center">
      <div class="success-tick">✓</div>
      <h1>Thank you, <?= e($order['customer_name']) ?>!</h1>
      <p class="lead mb-1">Your order has been received.</p>
      <p>Order number: <strong class="price"><?= e($order['order_number']) ?></strong></p>
      <?php if (isset($_GET['wa'])): ?>
        <div class="alert alert-success">Opening WhatsApp to send your order… <a href="<?= e($waUrl) ?>">Tap here if it does not open.</a></div>
        <script>setTimeout(function () { window.location.href = <?= json_encode($waUrl) ?>; }, 1200);</script>
      <?php endif; ?>
    </div>
    <div class="summary-card mt-4">
      <h2 class="h4">Order details</h2>
      <?php foreach ($items as $i): ?>
        <div class="d-flex justify-content-between gap-3 py-2 border-bottom">
          <span><?= e($i['product_name']) ?> <small class="text-muted">× <?= (int) $i['quantity'] ?></small></span>
          <span><?= price($i['price'] * $i['quantity']) ?></span>
        </div>
      <?php endforeach; ?>
      <div class="d-flex justify-content-between pt-3 fs-5 fw-bold"><span>Total</span><span class="price"><?= price($order['total']) ?></span></div>
      <hr>
      <p class="mb-1"><strong>Deliver to:</strong> <?= e($order['customer_name']) ?>, <?= e($order['phone']) ?></p>
      <p class="mb-1"><?= nl2br(e($order['address'])) ?></p>
      <?php if ($order['notes'] !== ''): ?><p class="mb-0"><strong>Notes:</strong> <?= e($order['notes']) ?></p><?php endif; ?>
    </div>
    <div class="d-flex flex-wrap gap-2 justify-content-center mt-4">
      <a class="btn btn-whatsapp" href="<?= e($waUrl) ?>" target="_blank" rel="noopener">💬 Send this order on WhatsApp</a>
      <a class="btn btn-brown" href="products.php">Continue shopping</a>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
