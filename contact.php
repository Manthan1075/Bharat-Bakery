<?php
require_once __DIR__ . '/includes/functions.php';

$errors = [];
$old = ['name' => '', 'email' => '', 'phone' => '', 'message' => ''];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($old as $k => $_) { $old[$k] = trim($_POST[$k] ?? ''); }
    if (mb_strlen($old['name']) < 2) { $errors['name'] = 'Please enter your name.'; }
    if ($old['email'] !== '' && !filter_var($old['email'], FILTER_VALIDATE_EMAIL)) { $errors['email'] = 'Enter a valid email address.'; }
    if (mb_strlen($old['message']) < 5) { $errors['message'] = 'Please write your message.'; }
    if (!$errors) {
        db()->prepare('INSERT INTO contact_messages (name, email, phone, message) VALUES (?,?,?,?)')
            ->execute([$old['name'], $old['email'], $old['phone'], $old['message']]);
        flash('success', 'Thank you! We have received your message and will reply soon.');
        header('Location: contact.php');
        exit;
    }
}
$pageTitle = 'Contact Us';
require_once __DIR__ . '/includes/header.php';
$cls = fn(string $f) => isset($errors[$f]) ? 'is-invalid' : '';
?>
<header class="page-banner"><div class="container"><h1>Contact Us</h1><p>Visit the bakery, call us or send a message.</p></div></header>
<section class="section">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-5">
        <div class="summary-card">
          <h2 class="h4">Bharat Bakery</h2>
          <!-- Edit these values in the `settings` table (phpMyAdmin) -->
          <p>📍 <strong>Address</strong><br><?= e(setting('address')) ?></p>
          <p>📞 <strong>Phone</strong><br><a href="tel:<?= e(preg_replace('/[^\d+]/', '', setting('phone'))) ?>"><?= e(setting('phone')) ?></a></p>
          <p>💬 <strong>WhatsApp</strong><br><a href="<?= e(whatsapp_link()) ?>" target="_blank" rel="noopener">+<?= e(preg_replace('/\D/', '', setting('whatsapp_number'))) ?></a></p>
          <p>🕗 <strong>Opening hours</strong><br><?= e(setting('opening_hours')) ?></p>
          <div class="d-flex flex-wrap gap-2">
            <a class="btn btn-brown" href="<?= e(setting('maps_link')) ?>" target="_blank" rel="noopener">Open in Google Maps</a>
            <a class="btn btn-outline-brown" href="<?= e(setting('instagram')) ?>" target="_blank" rel="noopener">Instagram</a>
            <a class="btn btn-outline-brown" href="<?= e(setting('facebook')) ?>" target="_blank" rel="noopener">Facebook</a>
          </div>
        </div>
      </div>
      <div class="col-lg-7">
        <form method="post" class="form-card" novalidate>
          <h2 class="h4 mb-3">Send us a message</h2>
          <div class="row g-3">
            <div class="col-md-6"><label class="form-label" for="name">Name</label>
              <input class="form-control <?= $cls('name') ?>" id="name" name="name" value="<?= e($old['name']) ?>"><div class="invalid-feedback"><?= e($errors['name'] ?? '') ?></div></div>
            <div class="col-md-6"><label class="form-label" for="phone">Phone (optional)</label>
              <input class="form-control" id="phone" name="phone" type="tel" value="<?= e($old['phone']) ?>"></div>
            <div class="col-12"><label class="form-label" for="email">Email (optional)</label>
              <input class="form-control <?= $cls('email') ?>" id="email" name="email" type="email" value="<?= e($old['email']) ?>"><div class="invalid-feedback"><?= e($errors['email'] ?? '') ?></div></div>
            <div class="col-12"><label class="form-label" for="message">Message</label>
              <textarea class="form-control <?= $cls('message') ?>" id="message" name="message" rows="5"><?= e($old['message']) ?></textarea><div class="invalid-feedback"><?= e($errors['message'] ?? '') ?></div></div>
            <div class="col-12"><button class="btn btn-gold btn-lg" type="submit">Send message</button></div>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
