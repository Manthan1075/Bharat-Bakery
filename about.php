<?php
$pageTitle = 'About Us';
require_once __DIR__ . '/includes/header.php';
?>
<header class="page-banner"><div class="container"><h1>About Bharat Bakery</h1><p>A neighbourhood bakery, baked fresh every morning.</p></div></header>
<section class="section">
  <div class="container">
    <div class="row g-5 align-items-center">
      <div class="col-lg-6">
        <h2 class="section-title text-start">Our story</h2>
        <?php foreach (preg_split('/\R{2,}/', setting('about_story'), -1, PREG_SPLIT_NO_EMPTY) as $para): ?>
          <p><?= nl2br(e(trim($para))) ?></p>
        <?php endforeach; ?>
      </div>
      <div class="col-lg-6"><div class="about-art"><img src="assets/images/cake.svg" alt="" aria-hidden="true"><img src="assets/images/bread.svg" alt="" aria-hidden="true"><img src="assets/images/cookie.svg" alt="" aria-hidden="true"><img src="assets/images/cupcake.svg" alt="" aria-hidden="true"></div></div>
    </div>
  </div>
</section>
<section class="section section-cream">
  <div class="container">
    <h2 class="section-title">Why people choose us</h2>
    <div class="row g-3 mt-2">
      <?php foreach ([
        ['🌾', 'Quality ingredients', 'Fresh dairy, good flour and real chocolate in every bake.'],
        ['🔥', 'Baked fresh daily', 'Nothing sits on the shelf for days. We bake each morning.'],
        ['🎨', 'Custom cakes', 'Share your theme, flavour and message and we will make it.'],
        ['🛵', 'Easy ordering', 'Order on the website or on WhatsApp, pick up or get it delivered.'],
      ] as [$icon, $title, $text]): ?>
        <div class="col-sm-6 col-lg-3"><div class="why-card"><span><?= $icon ?></span><h3 class="h5"><?= e($title) ?></h3><p class="mb-0"><?= e($text) ?></p></div></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
