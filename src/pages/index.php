<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Featured products: latest 6
$products = dbQuery($conn, 'SELECT * FROM products ORDER BY created_at DESC LIMIT 6')->fetch_all(MYSQLI_ASSOC);

// Categories with product counts
$categories = dbQuery($conn, 'SELECT category, COUNT(*) AS total FROM products GROUP BY category ORDER BY category')->fetch_all(MYSQLI_ASSOC);

$categoryIcons = [
    'Cleanser' => '🧼', 'Toner' => '💧', 'Serum' => '✨', 'Moisturizer' => '🫙',
    'Sun Protection' => '☀️', 'Treatment' => '💉', 'Exfoliator' => '🍃', 'Mask' => '🍃',
];
function categoryIcon($name, $map) {
    return $map[$name] ?? '🧴';
}

$why = [
    ['icon' => '🌿', 'title' => 'Natural Ingredients', 'text' => 'Fermented botanicals, centella, ginseng — sourced from Korean farms.'],
    ['icon' => '🩺', 'title' => 'Dermatologist Tested', 'text' => 'Every formula clinically tested for sensitive skin compatibility.'],
    ['icon' => '🐰', 'title' => 'Cruelty Free', 'text' => 'Never tested on animals, certified by Leaping Bunny.'],
];

$routine = [
    ['icon' => '🧼', 'title' => 'Cleanse', 'text' => 'Double cleanse with oil then foam to fully remove impurities.'],
    ['icon' => '💦', 'title' => 'Tone', 'text' => 'Rebalance pH and prep skin to drink in what comes next.'],
    ['icon' => '💉', 'title' => 'Treat', 'text' => 'Target concerns with serums and ampoules rich in actives.'],
    ['icon' => '🧴', 'title' => 'Moisturize', 'text' => 'Seal it all in and reinforce the barrier with cream or balm.'],
];

$faqs = [
    ['q' => 'How long until I see results?', 'a' => 'Most customers notice improved hydration within a week and visible texture/tone changes by 4–8 weeks of consistent use.'],
    ['q' => 'Are your products cruelty-free?', 'a' => 'Yes — every Korean Point product is Leaping Bunny certified and never tested on animals at any stage.'],
    ['q' => 'What is your return policy?', 'a' => 'Unopened products can be returned within 30 days.'],
];

$pageTitle = 'Korean Point · Korean Skincare';
include __DIR__ . '/../includes/header.php';
?>

<section class="hero">
  <div class="container hero__inner">
    <div>
      <h1>Reveal Your Natural Glow</h1>
      <p>Dermatologist-tested Korean skincare built on fermented botanicals, centella and niacinamide.</p>
      <a href="shop.php" class="btn">Shop Now</a>
    </div>
    <div class="hero__art">
      <svg viewBox="0 0 400 420" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Illustration of a woman applying skincare cream, with skincare jars and leaves">
        <ellipse cx="200" cy="220" rx="180" ry="190" fill="#eef3ea"/>
        <path d="M40 60 C20 90 30 130 70 140 C60 100 60 80 40 60 Z" fill="var(--sage)"/>
        <path d="M360 70 C385 95 380 135 340 150 C348 110 350 90 360 70 Z" fill="var(--sage-deep)"/>
        <path d="M120 420 C120 340 150 300 200 300 C250 300 280 340 280 420 Z" fill="#ffffff" stroke="var(--line)" stroke-width="2"/>
        <path d="M140 150 C140 100 165 70 200 70 C235 70 260 100 260 150 C260 165 245 175 230 168 C215 178 185 178 170 168 C155 175 140 165 140 150 Z" fill="var(--sage-deep)"/>
        <circle cx="200" cy="95" r="10" fill="var(--sage-deep)"/>
        <ellipse cx="200" cy="185" rx="58" ry="65" fill="#f3c9a1"/>
        <circle cx="165" cy="200" r="8" fill="#f3a6a6" opacity="0.5"/>
        <circle cx="235" cy="200" r="8" fill="#f3a6a6" opacity="0.5"/>
        <path d="M175 185 q8 6 16 0" stroke="#5a4634" stroke-width="3" fill="none" stroke-linecap="round"/>
        <path d="M209 185 q8 6 16 0" stroke="#5a4634" stroke-width="3" fill="none" stroke-linecap="round"/>
        <path d="M188 215 q12 8 24 0" stroke="#5a4634" stroke-width="3" fill="none" stroke-linecap="round"/>
        <ellipse cx="245" cy="205" rx="16" ry="22" fill="#f3c9a1" transform="rotate(-20 245 205)"/>
        <rect x="80" y="330" width="46" height="56" rx="10" fill="var(--pink)"/>
        <rect x="80" y="322" width="46" height="14" rx="6" fill="var(--sage-deep)"/>
        <rect x="270" y="345" width="40" height="45" rx="8" fill="var(--sage)"/>
        <rect x="270" y="338" width="40" height="12" rx="5" fill="var(--sage-deep)"/>
        <rect x="185" y="355" width="30" height="40" rx="14" fill="#ffffff" stroke="var(--line)" stroke-width="2"/>
      </svg>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <h2>Shop by Category</h2>
    <div class="cat-grid">
      <?php foreach ($categories as $cat): ?>
        <a class="cat-card" href="shop.php?category=<?php echo urlencode($cat['category']); ?>">
          <div class="cat-card__icon"><?php echo categoryIcon($cat['category'], $categoryIcons); ?></div>
          <div class="cat-card__name"><?php echo htmlspecialchars($cat['category']); ?></div>
          <div class="cat-card__count"><?php echo (int) $cat['total']; ?> products</div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section section--tint">
  <div class="container">
    <h2>Featured Products</h2>
    <div class="product-grid">
      <?php if (count($products) > 0): ?>
        <?php foreach ($products as $p): ?>
          <div class="card">
            <div class="card__media">
              <img src="<?php echo htmlspecialchars($p['image_url']); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>" onerror="this.src='https://via.placeholder.com/300x300/a8c3ab/ffffff?text=Skincare'">
            </div>
            <div class="card__body">
              <span class="card__brand"><?php echo htmlspecialchars($p['brand']); ?></span>
              <h3 class="card__name"><?php echo htmlspecialchars($p['name']); ?></h3>
              <div class="card__price"><?php echo formatPrice($p['price']); ?></div>
            </div>
            <div class="card__foot">
              <a href="product_details.php?id=<?php echo $p['id']; ?>" class="btn btn-outline btn-sm">View Details</a>
              <?php if ($p['stock'] > 0): ?>
                <form method="post" action="../actions/cart.php">
                  <input type="hidden" name="action" value="add">
                  <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
                  <input type="hidden" name="redirect" value="../pages/index.php">
                  <button type="submit" class="btn btn-sm">Add to Cart</button>
                </form>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="empty">No products available.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <h2>Why Korean Skincare</h2>
    <div class="why-grid">
      <?php foreach ($why as $w): ?>
        <div class="why-card">
          <div class="icon"><?php echo $w['icon']; ?></div>
          <h3><?php echo htmlspecialchars($w['title']); ?></h3>
          <p><?php echo htmlspecialchars($w['text']); ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section section--tint">
  <div class="container">
    <h2>Your Beauty Routine</h2>
    <div class="timeline">
      <?php foreach ($routine as $i => $r): ?>
        <div>
          <div class="icon"><?php echo $r['icon']; ?></div>
          <p class="step">STEP <?php echo $i + 1; ?></p>
          <h3><?php echo htmlspecialchars($r['title']); ?></h3>
          <p><?php echo htmlspecialchars($r['text']); ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section">
  <div class="container container--narrow">
    <h2>Frequently Asked</h2>
    <div class="faq">
      <?php foreach ($faqs as $f): ?>
        <details>
          <summary><?php echo htmlspecialchars($f['q']); ?></summary>
          <p><?php echo htmlspecialchars($f['a']); ?></p>
        </details>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
