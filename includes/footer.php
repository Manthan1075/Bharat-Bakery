<footer class="site-footer">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-4">
        <a class="brand text-white" href="index.php"><?= brand_logo() ?></a>
        <p class="mt-3"><?= e(setting('footer_text', 'Freshly Baked with Love, Every Day!')) ?></p>
        <div class="social">
          <a href="<?= e(setting('facebook')) ?>" target="_blank" rel="noopener">Facebook</a>
          <a href="<?= e(setting('instagram')) ?>" target="_blank" rel="noopener">Instagram</a>
          <a href="<?= e(setting('youtube')) ?>" target="_blank" rel="noopener">YouTube</a>
        </div>
      </div>
      <div class="col-6 col-lg-2">
        <h5>Quick Links</h5>
        <ul class="list-unstyled footer-links">
          <li><a href="index.php">Home</a></li>
          <li><a href="about.php">About Us</a></li>
          <li><a href="products.php">Our Products</a></li>
          <li><a href="cart.php">Cart</a></li>
          <li><a href="contact.php">Contact Us</a></li>
        </ul>
      </div>
      <div class="col-lg-6">
        <h5>Visit or Call Us</h5>
        <p class="mb-1">📍 <?= e(setting('address')) ?></p>
        <p class="mb-1">📞 <a href="tel:<?= e(preg_replace('/[^\d+]/', '', setting('phone'))) ?>"><?= e(setting('phone')) ?></a></p>
        <p class="mb-1">✉️ <a href="mailto:<?= e(setting('email')) ?>"><?= e(setting('email')) ?></a></p>
        <p class="mb-0">🕗 <?= e(setting('opening_hours')) ?></p>
      </div>
    </div>
    <div class="footer-bottom">&copy; <?= date('Y') ?> Bharat Bakery. All rights reserved.</div>
  </div>
</footer>

<a href="<?= e(whatsapp_link('Hello Bharat Bakery, I would like to place an order.')) ?>" class="wa-float" target="_blank" rel="noopener" aria-label="Order on WhatsApp">
  <span aria-hidden="true">💬</span> Order on WhatsApp
</a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/script.js"></script>
</body>
</html>
