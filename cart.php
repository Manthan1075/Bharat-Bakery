<?php
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id     = (int) ($_POST['product_id'] ?? 0);
    $back   = safe_redirect_target($_POST['redirect'] ?? 'cart.php');

    if ($action === 'add' && $id > 0) {
        $check = db()->prepare('SELECT name FROM products WHERE id = ? AND is_available = 1');
        $check->execute([$id]);
        if ($name = $check->fetchColumn()) {
            $qty = max(1, min(50, (int) ($_POST['qty'] ?? 1)));
            $_SESSION['cart'][$id] = min(50, ($_SESSION['cart'][$id] ?? 0) + $qty);
            flash('success', $name . ' added to your cart.');
        }
        header('Location: ' . $back);
        exit;
    }
    if ($action === 'update') {
        foreach ((array) ($_POST['qty'] ?? []) as $pid => $q) {
            $q = (int) $q;
            if ($q <= 0) { unset($_SESSION['cart'][(int) $pid]); }
            else { $_SESSION['cart'][(int) $pid] = min(50, $q); }
        }
        flash('success', 'Cart updated.');
    }
    if ($action === 'remove' && $id > 0) {
        unset($_SESSION['cart'][$id]);
        flash('info', 'Item removed from your cart.');
    }
    if ($action === 'clear') {
        $_SESSION['cart'] = [];
        flash('info', 'Your cart is empty.');
    }
    header('Location: cart.php');
    exit;
}

$pageTitle = 'Shopping Cart';
require_once __DIR__ . '/includes/header.php';
[$items, $total] = cart_details();
?>
<header class="page-banner"><div class="container"><h1>Shopping Cart</h1><p>Review your items before checkout.</p></div></header>
<section class="section">
  <div class="container">
  <?php if (!$items): ?>
    <div class="empty-state"><div class="display-3">🛒</div><h2>Your cart is empty</h2><p>Add a cake, some bread or a box of cookies to get started.</p><a href="products.php" class="btn btn-brown btn-lg">Browse products</a></div>
  <?php else: ?>
    <div class="row g-4">
      <div class="col-lg-8">
        <form method="post" id="updateForm">
          <input type="hidden" name="action" value="update">
          <div class="cart-table table-responsive">
            <table class="table align-middle mb-0">
              <thead><tr><th>Product</th><th>Price</th><th style="width:150px">Quantity</th><th class="text-end">Total</th><th></th></tr></thead>
              <tbody>
              <?php foreach ($items as $it): ?>
                <tr>
                  <td><div class="d-flex align-items-center gap-3"><img src="<?= image_url($it['image']) ?>" class="cart-thumb" alt=""><a class="fw-bold text-decoration-none" href="product-details.php?id=<?= (int) $it['id'] ?>"><?= e($it['name']) ?></a></div></td>
                  <td><?= price($it['price']) ?></td>
                  <td>
                    <div class="qty-box">
                      <button type="button" class="qty-btn" data-step="-1" aria-label="Decrease">−</button>
                      <input type="number" name="qty[<?= (int) $it['id'] ?>]" value="<?= (int) $it['qty'] ?>" min="1" max="50" class="qty-input" aria-label="Quantity">
                      <button type="button" class="qty-btn" data-step="1" aria-label="Increase">+</button>
                    </div>
                  </td>
                  <td class="text-end fw-bold"><?= price($it['line_total']) ?></td>
                  <td class="text-end">
                    <button type="submit" form="remove-<?= (int) $it['id'] ?>" class="btn btn-sm btn-outline-danger" aria-label="Remove <?= e($it['name']) ?>">Remove</button>
                  </td>
                </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <div class="d-flex flex-wrap gap-2 mt-3">
            <button class="btn btn-brown" type="submit">Update cart</button>
            <a class="btn btn-outline-brown" href="products.php">Continue shopping</a>
            <button class="btn btn-link text-danger ms-auto" type="submit" form="clearForm">Clear cart</button>
          </div>
        </form>
        <?php foreach ($items as $it): ?>
          <form id="remove-<?= (int) $it['id'] ?>" method="post"><input type="hidden" name="action" value="remove"><input type="hidden" name="product_id" value="<?= (int) $it['id'] ?>"></form>
        <?php endforeach; ?>
        <form id="clearForm" method="post"><input type="hidden" name="action" value="clear"></form>
      </div>
      <div class="col-lg-4">
        <div class="summary-card">
          <h2 class="h4">Order summary</h2>
          <div class="d-flex justify-content-between py-2"><span>Items</span><span><?= cart_count() ?></span></div>
          <div class="d-flex justify-content-between py-2 border-top fs-5 fw-bold"><span>Total</span><span class="price"><?= price($total) ?></span></div>
          <a href="checkout.php" class="btn btn-gold btn-lg w-100 mt-3">Proceed to Checkout</a>
        </div>
      </div>
    </div>
  <?php endif; ?>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
