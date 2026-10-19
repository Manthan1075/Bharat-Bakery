<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/database.php';

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function price($amount): string
{
    return '₹' . number_format((float) $amount, 2);
}

function setting(string $key, string $default = ''): string
{
    static $cache = null;
    if ($cache === null) {
        $cache = [];
        foreach (db()->query('SELECT setting_key, setting_value FROM settings') as $row) {
            $cache[$row['setting_key']] = (string) $row['setting_value'];
        }
    }
    return $cache[$key] ?? $default;
}

function whatsapp_link(string $text = ''): string
{
    $number = preg_replace('/\D/', '', setting('whatsapp_number', '919999999999'));
    return 'https://wa.me/' . $number . ($text !== '' ? '?text=' . rawurlencode($text) : '');
}

/** Logo image if uploaded (settings: logo_file), otherwise the text logo. */
function brand_logo(): string
{
    $file = basename(setting('logo_file'));
    if ($file !== '' && is_file(__DIR__ . '/../assets/images/' . $file)) {
        return '<img src="assets/images/' . e($file) . '" alt="Bharat Bakery" class="brand-logo">';
    }
    return '<span class="brand-mark">B</span> Bharat Bakery';
}

function image_url(string $file): string
{
    return 'assets/images/' . e($file);
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash(): ?array
{
    $f = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $f;
}

function cart_count(): int
{
    return array_sum($_SESSION['cart'] ?? []);
}

/** Returns [items, total]. Each item = product row + qty + line_total. */
function cart_details(): array
{
    $cart = $_SESSION['cart'] ?? [];
    if (!$cart) {
        return [[], 0.0];
    }
    $ids = array_keys($cart);
    $in  = implode(',', array_fill(0, count($ids), '?'));
    $stmt = db()->prepare("SELECT * FROM products WHERE is_available = 1 AND id IN ($in)");
    $stmt->execute($ids);

    $items = [];
    $total = 0.0;
    foreach ($stmt as $p) {
        $p['qty'] = (int) $cart[$p['id']];
        $p['line_total'] = $p['qty'] * (float) $p['price'];
        $total += $p['line_total'];
        $items[] = $p;
    }
    return [$items, $total];
}

/** Safe local redirect target (e.g. products.php?category=2). */
function safe_redirect_target(?string $target): string
{
    if ($target && preg_match('/^[a-z\-]+\.php(\?[\w=&%\-+.]*)?$/i', $target)) {
        return $target;
    }
    return 'products.php';
}

function product_card(array $p): void
{
    $redirect = e(basename($_SERVER['REQUEST_URI'] ?? 'products.php'));
    ?>
    <div class="col-6 col-md-4 col-lg-3">
      <div class="product-card h-100">
        <a href="product-details.php?id=<?= (int) $p['id'] ?>" class="product-img">
          <img src="<?= image_url($p['image']) ?>" alt="<?= e($p['name']) ?>" loading="lazy">
          <?php if (!empty($p['old_price'])): ?><span class="badge-offer">Offer</span><?php endif; ?>
        </a>
        <div class="product-body">
          <?php if (!empty($p['category_name'])): ?><small class="text-muted"><?= e($p['category_name']) ?></small><?php endif; ?>
          <h3 class="product-title"><a href="product-details.php?id=<?= (int) $p['id'] ?>"><?= e($p['name']) ?></a></h3>
          <p class="product-desc"><?= e($p['description']) ?></p>
          <div class="product-price">
            <span class="price"><?= price($p['price']) ?></span>
            <?php if (!empty($p['old_price'])): ?><del><?= price($p['old_price']) ?></del><?php endif; ?>
          </div>
          <form method="post" action="cart.php" class="mt-2">
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="product_id" value="<?= (int) $p['id'] ?>">
            <input type="hidden" name="redirect" value="<?= $redirect ?>">
            <button class="btn btn-brown w-100" type="submit">Add to Cart</button>
          </form>
        </div>
      </div>
    </div>
    <?php
}
