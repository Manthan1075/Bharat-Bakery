<?php
$pageTitle = 'Home';
require_once __DIR__ . '/includes/header.php';

$categories = db()->query('SELECT * FROM categories ORDER BY sort_order')->fetchAll();
$sql = 'SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON c.id = p.category_id WHERE p.is_available = 1 AND ';
$featured = db()->query($sql . 'p.is_featured = 1 ORDER BY p.id LIMIT 8')->fetchAll();
$offers   = db()->query($sql . 'p.is_offer = 1 ORDER BY p.id LIMIT 4')->fetchAll();
$testimonials = [
    ['Priya Patel', 'The chocolate truffle cake was the star of my daughter\'s birthday. Fresh, soft and delivered on time.'],
    ['Rahul Mehta', 'Their veg puffs and pav are the best in town. We pick up fresh bread every morning.'],
    ['Anita Shah', 'Beautiful custom cake for our anniversary. Everyone asked where it came from!'],
];
?>

<style>

  .cat-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 12px;
}

.category-icon {
    width: 48px;
    height: 48px;
    object-fit: contain;
    display: block;
}

.category-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

</style>

<section class="hero">
  <div class="container">
    <div class="row align-items-center g-4">
      <div class="col-lg-6">
        <p class="hero-kicker">Welcome to Bharat Bakery</p>
        <h1><?= e(setting('hero_title', 'Freshly Baked with Love, Every Day!')) ?></h1>
        <p class="lead"><?= e(setting('hero_text', 'Birthday cakes, soft bread, flaky pastries and hot snacks, baked in small batches every morning.')) ?></p>
        <div class="d-flex flex-wrap gap-2 mt-4">
          <a href="products.php" class="btn btn-gold btn-lg">Explore Products</a>
          <a href="<?= e(whatsapp_link('Hello Bharat Bakery, I would like to place an order.')) ?>" class="btn btn-outline-light btn-lg" target="_blank" rel="noopener">Order Now</a>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="hero-art">
          <img src="assets/images/cake.jpeg" alt="Chocolate cake" class="hero-main">
          <img src="assets/images/cupcake.jpg" alt="Cupcake" class="hero-side hero-side-1">
          <img src="assets/images/bread.jpeg" alt="Fresh bread" class="hero-side hero-side-2">
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <h2 class="section-title">Shop by category</h2>
    <p class="section-sub">
      Everything from celebration cakes to everyday bread.
    </p>

    <div class="row g-3">
      <?php foreach ($categories as $c): ?>
        <div class="col-6 col-md-4 col-lg-3">
          <a class="category-card"
             href="products.php?category=<?= (int) $c['id'] ?>">

           

<span class="cat-icon">
    <img
        src="assets/images/icons/<?= e($c['icon']) ?>"
        alt="<?= e($c['name']) ?>"
        class="category-icon"
        loading="lazy"
    >
</span>



            <strong><?= e($c['name']) ?></strong>
            <small><?= e($c['description']) ?></small>

          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section section-cream">
  <div class="container">
    <h2 class="section-title">Featured products</h2>
    <p class="section-sub">Our most loved bakes this week.</p>
    <div class="row g-3 g-lg-4">
      <?php foreach ($featured as $p) { product_card($p); } ?>
    </div>
    <div class="text-center mt-4"><a href="products.php" class="btn btn-brown">View all products</a></div>
  </div>
</section>

<?php if ($offers): ?>
<section class="section">
  <div class="container">
    <div class="offer-band">
      <div class="row align-items-center g-3">
        <div class="col-lg-5">
          <h2>Special offers</h2>
          <p class="mb-0">Limited-time prices on cakes, cookies and desserts. Order today while stock lasts.</p>
        </div>
        <div class="col-lg-7">
          <div class="row g-3">
            <?php foreach ($offers as $o): ?>
              <div class="col-6">
                <a href="product-details.php?id=<?= (int) $o['id'] ?>" class="offer-item">
                  <span><?= e($o['name']) ?></span>
                  <b><?= price($o['price']) ?></b> <del><?= price($o['old_price']) ?></del>
                </a>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="section section-cream">
  <div class="container">
    <h2 class="section-title">What our customers say</h2>
    <div class="row g-3 mt-2">
      <?php foreach ($testimonials as [$name, $text]): ?>
        <div class="col-md-4">
          <figure class="testimonial">
            <div class="stars" aria-label="5 out of 5 stars">★★★★★</div>
            <blockquote><?= e($text) ?></blockquote>
            <figcaption><?= e($name) ?></figcaption>
          </figure>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section text-center">
  <div class="container">
    <h2 class="section-title">Planning a party or a gift?</h2>
    <p class="section-sub mx-auto">Tell us the flavour, weight and date on WhatsApp. We will confirm the price and delivery.</p>
    <a href="<?= e(whatsapp_link('Hello Bharat Bakery, I want to order a custom cake.')) ?>" class="btn btn-whatsapp btn-lg" target="_blank" rel="noopener">💬 Order on WhatsApp</a>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
