<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$why = [
    ['icon' => '🌿', 'title' => 'Natural Ingredients', 'text' => 'Fermented botanicals, centella, ginseng — sourced from Korean farms.'],
    ['icon' => '🩺', 'title' => 'Dermatologist Tested', 'text' => 'Every formula clinically tested for sensitive skin compatibility.'],
    ['icon' => '🐰', 'title' => 'Cruelty Free', 'text' => 'Never tested on animals, certified by Leaping Bunny.'],
    ['icon' => '♻️', 'title' => 'Sustainable Packaging', 'text' => 'Recyclable glass and refill pouches on every core product.'],
    ['icon' => '⚡', 'title' => 'Fast Absorption', 'text' => 'Lightweight textures layer in seconds, no pilling, no residue.'],
    ['icon' => '🌸', 'title' => 'Sensitive Skin Friendly', 'text' => 'Fragrance-optional, pH 5.5 formulas for reactive skin types.'],
];

$routine = [
    ['icon' => '🧼', 'title' => 'Cleanse', 'text' => 'Double cleanse with oil then foam to fully remove impurities.'],
    ['icon' => '💦', 'title' => 'Tone', 'text' => 'Rebalance pH and prep skin to drink in what comes next.'],
    ['icon' => '💉', 'title' => 'Treat', 'text' => 'Target concerns with serums and ampoules rich in actives.'],
    ['icon' => '🧴', 'title' => 'Moisturize', 'text' => 'Seal it all in and reinforce the barrier with cream or balm.'],
];

$pageTitle = 'About — Korean Point';
include __DIR__ . '/../includes/header.php';
?>

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

<?php include __DIR__ . '/../includes/footer.php'; ?>
