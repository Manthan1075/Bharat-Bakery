<?php
require_once __DIR__ . '/includes/functions.php';

[$items, $total] = cart_details();
if (!$items) {
    flash('warning', 'Your cart is empty. Add some products first.');
    header('Location: products.php');
    exit;
}

$errors = [];
$old = ['name' => '', 'phone' => '', 'address' => '', 'notes' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($old as $k => $_) { $old[$k] = trim($_POST[$k] ?? ''); }
    if (mb_strlen($old['name']) < 2)                      { $errors['name'] = 'Please enter your name.'; }
    if (!preg_match('/^\+?[0-9\s-]{10,15}$/', $old['phone'])) { $errors['phone'] = 'Enter a valid phone number (10 digits).'; }
    if (mb_strlen($old['address']) < 8)                   { $errors['address'] = 'Please enter your full delivery address.'; }

    if (!$errors) {
        $pdo = db();
        try {
            $pdo->beginTransaction();
            $orderNo = 'BB' . date('ymd') . random_int(1000, 9999);
            $pdo->prepare('INSERT INTO orders (order_number, customer_name, phone, address, notes, total) VALUES (?,?,?,?,?,?)')
                ->execute([$orderNo, $old['name'], $old['phone'], $old['address'], $old['notes'], $total]);
            $orderId = (int) $pdo->lastInsertId();
            $ins = $pdo->prepare('INSERT INTO order_items (order_id, product_id, product_name, price, quantity) VALUES (?,?,?,?,?)');
            foreach ($items as $it) {
                $ins->execute([$orderId, $it['id'], $it['name'], $it['price'], $it['qty']]);
            }
            $pdo->commit();
            $_SESSION['cart'] = [];
            $_SESSION['last_order'] = $orderNo;
            $go = 'order-success.php?order=' . urlencode($orderNo);
            if (($_POST['channel'] ?? '') === 'whatsapp') { $go .= '&wa=1'; }
            header('Location: ' . $go);
            exit;
        } catch (Throwable $ex) {
            if ($pdo->inTransaction()) { $pdo->rollBack(); }
            $errors['general'] = 'Sorry, we could not save your order. Please try again.';
        }
    }
}

$pageTitle = 'Checkout';
require_once __DIR__ . '/includes/header.php';
function field_class(array $errors, string $f): string { return isset($errors[$f]) ? 'is-invalid' : ''; }
?>
<header class="page-banner"><div class="container"><h1>Checkout</h1><p>Tell us where to deliver your order.</p></div></header>
<section class="section">
  <div class="container">
    <?php if (isset($errors['general'])): ?><div class="alert alert-danger"><?= e($errors['general']) ?></div><?php endif; ?>
    <div class="row g-4">
      <div class="col-lg-7">
        <form method="post" class="form-card" novalidate>
          <h2 class="h4 mb-3">Delivery details</h2>
          <div class="mb-3">
            <label class="form-label" for="name">Full name</label>
            <input class="form-control <?= field_class($errors, 'name') ?>" id="name" name="name" value="<?= e($old['name']) ?>" required>
            <div class="invalid-feedback"><?= e($errors['name'] ?? '') ?></div>
          </div>
          <div class="mb-3">
            <label class="form-label" for="phone">Phone number</label>
            <input class="form-control <?= field_class($errors, 'phone') ?>" id="phone" name="phone" type="tel" value="<?= e($old['phone']) ?>" required>
            <div class="invalid-feedback"><?= e($errors['phone'] ?? '') ?></div>
          </div>
          <div class="mb-3">
            <label class="form-label" for="address">Delivery address</label>
            <textarea class="form-control <?= field_class($errors, 'address') ?>" id="address" name="address" rows="3" required><?= e($old['address']) ?></textarea>
            <div class="invalid-feedback"><?= e($errors['address'] ?? '') ?></div>
          </div>
          <div class="mb-4">
            <label class="form-label" for="notes">Order notes (optional)</label>
            <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Message on cake, delivery time, eggless..."><?= e($old['notes']) ?></textarea>
          </div>
          <div class="d-grid gap-2 d-sm-flex">
            <button class="btn btn-gold btn-lg flex-fill" type="submit" name="channel" value="site">Place Order</button>
            <button class="btn btn-whatsapp btn-lg flex-fill" type="submit" name="channel" value="whatsapp">💬 Place Order &amp; Send on WhatsApp</button>
          </div>
          <p class="small text-muted mt-3 mb-0">We will call or message you to confirm the order and payment (cash on delivery or UPI).</p>
        </form>
      </div>
      <div class="col-lg-5">
        <div class="summary-card">
          <h2 class="h4">Your order</h2>
          <?php foreach ($items as $it): ?>
            <div class="d-flex justify-content-between gap-3 py-2 border-bottom">
              <span><?= e($it['name']) ?> <small class="text-muted">× <?= (int) $it['qty'] ?></small></span>
              <span><?= price($it['line_total']) ?></span>
            </div>
          <?php endforeach; ?>
          <div class="d-flex justify-content-between pt-3 fs-5 fw-bold"><span>Total</span><span class="price"><?= price($total) ?></span></div>
          <a href="cart.php" class="small">Edit cart</a>
        </div>
      </div>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
