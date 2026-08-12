<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../includes/catalog_data.php';
$checkoutError = $_GET['checkout_error'] ?? '';
$selfRedirect = $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="description" content="Why Korean skincare, and the Korean Point beauty routine." />
<title>About — Korean Point</title>

<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Noto+Sans+KR:wght@300;400;500;700&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="../css/variables.css" />
<link rel="stylesheet" href="../css/base.css" />
<link rel="stylesheet" href="../css/nav.css" />
<link rel="stylesheet" href="../css/content.css" />
<link rel="stylesheet" href="../css/footer.css" />
<link rel="stylesheet" href="../css/overlays.css" />
<link rel="stylesheet" href="../css/effects.css" />
<link rel="stylesheet" href="../css/responsive.css" />
<link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🌿</text></svg>" />
</head>
<body>

<header class="nav" id="nav">
  <div class="container nav__inner">
    <a class="logo" href="index.php" aria-label="Korean Point home">
      <span class="logo__leaf" aria-hidden="true">🌿</span>
      <span class="logo__text">Korean Point</span>
    </a>

    <nav class="nav__links" id="navLinks" aria-label="Primary">
      <a href="index.php" class="nav__link">Home</a>
      <a href="shop.php" class="nav__link">Shop</a>
      <a href="about.php" class="nav__link">About</a>
    </nav>

    <div class="nav__actions">
      <button class="icon-btn" id="searchToggle" aria-label="Search products" aria-expanded="false">
        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
      </button>
      <button class="icon-btn" id="themeToggle" aria-label="Toggle dark mode">
        <svg class="icon-sun" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="4.5"/><path d="M12 2v2M12 20v2M2 12h2M20 12h2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M19.1 4.9l-1.4 1.4M6.3 17.7l-1.4 1.4"/></svg>
        <svg class="icon-moon" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 13.2A8.5 8.5 0 1 1 10.8 3a6.8 6.8 0 0 0 10.2 10.2z"/></svg>
      </button>
      <button class="icon-btn" id="cartToggle" aria-label="Open shopping cart">
        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="9" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.5 3h2.5l2.36 12.19a2 2 0 0 0 2 1.63h8.4a2 2 0 0 0 1.97-1.63L21.5 8H6.1"/></svg>
        <span class="badge" id="cartCount">0</span>
      </button>
      <button class="btn btn--ghost btn--sm" id="logoutBtn" type="button">Logout</button>
      <button class="hamburger" id="hamburger" aria-label="Open menu" aria-expanded="false" aria-controls="navLinks">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>

  <div class="searchbar" id="searchbar" hidden>
    <div class="container searchbar__inner">
      <svg class="searchbar__icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
      <input type="search" id="searchInput" class="searchbar__input" placeholder="Search serums, toners, brands…" aria-label="Search products" />
      <button class="btn btn--ghost btn--sm" id="searchClear" type="button">Clear</button>
    </div>
  </div>
</header>

<div class="nav-overlay" id="navOverlay" hidden></div>

<main>
<section class="section" id="about">
  <div class="container">
    <header class="section__head">
      <p class="eyebrow">The Korean Point standard</p>
      <h2 class="section__title">Why Korean Skincare</h2>
      <p class="section__sub">Layered, gentle, obsessive about the barrier — not about stripping it.</p>
    </header>
    <div class="why-grid" id="whyGrid"><!-- JS --></div>
  </div>
</section>

<section class="section section--tint" id="routine">
  <div class="container">
    <header class="section__head">
      <p class="eyebrow">Four steps, ten minutes</p>
      <h2 class="section__title">Your Beauty Routine</h2>
      <p class="section__sub">The core ritual. Morning and night, in this order.</p>
    </header>
    <ol class="timeline" id="timeline"><!-- JS --></ol>
  </div>
</section>
</main>

<footer class="footer">
  <div class="container footer__grid">
    <div class="footer__col">
      <a class="logo" href="index.php"><span class="logo__leaf" aria-hidden="true">🌿</span><span class="logo__text">Korean Point</span></a>
      <p class="footer__about">Clean Korean skincare formulated in Seoul, made for every skin barrier.
        Cruelty free, dermatologist tested, recyclable glass.</p>
      <div class="socials">
        <a href="#" aria-label="Instagram"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.2" cy="6.8" r="1.1"/></svg></a>
        <a href="#" aria-label="YouTube"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="2.5" y="5.5" width="19" height="13" rx="4"/><path d="M10.5 9.5l5 2.5-5 2.5z"/></svg></a>
        <a href="#" aria-label="TikTok"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 4v9.5a3.5 3.5 0 1 1-3-3.46"/><path d="M14 6.5c.8 1.6 2.2 2.5 4 2.6"/></svg></a>
        <a href="#" aria-label="Pinterest"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M10 19l2-8M12.4 11a2.4 2.4 0 1 1 2.3-2.4c0 2.6-1.6 4.6-3.6 4.6"/></svg></a>
      </div>
    </div>

    <nav class="footer__col" aria-label="Quick links">
      <h3>Shop</h3>
      <ul><li><a href="shop.php">All Products</a></li>
        <li><a href="index.php#categories">Categories</a></li><li><a href="about.php">Routine</a></li></ul>
    </nav>

    <nav class="footer__col" aria-label="Customer support">
      <h3>Support</h3>
      <ul><li><a href="index.php#faq">FAQ</a></li><li><a href="index.php#faq">Shipping &amp; Returns</a></li>
        <li><a href="index.php#faq">Ingredient Glossary</a></li></ul>
    </nav>

    <div class="footer__col">
      <h3>Company</h3>
      <ul><li><a href="about.php">Our Story</a></li><li><a href="about.php">Sustainability</a></li>
        <li><a href="index.php#reviews">Reviews</a></li></ul>
      <p class="footer__contact">Seongsu-dong, Seoul</p>
    </div>
  </div>

  <div class="container footer__bar">
    <p>© <span id="year">2026</span> Korean Point Cosmetics Co. All rights reserved.</p>
    <ul class="pay" aria-label="Accepted payment methods">
      <li>VISA</li><li>Mastercard</li><li>AMEX</li><li>PayPal</li><li>Apple&nbsp;Pay</li><li>KakaoPay</li>
    </ul>
  </div>
</footer>

<aside class="drawer" id="cartDrawer" role="dialog" aria-modal="true" aria-label="Shopping cart" hidden>
  <header class="drawer__head">
    <h2 id="drawerTitle">Your Cart</h2>
    <button class="icon-btn" id="cartClose" aria-label="Close cart">
      <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg>
    </button>
  </header>
  <div class="drawer__body" id="cartItems"><!-- JS --></div>
  <footer class="drawer__foot">
    <div class="drawer__row"><span>Subtotal</span><strong id="cartSubtotal">NPR 0.00</strong></div>
    <div class="drawer__row drawer__row--muted"><span>Shipping</span><span id="cartShip">Free</span></div>
    <div class="drawer__row drawer__row--total"><span>Total</span><strong id="cartTotal">NPR 0.00</strong></div>
    <button class="btn btn--primary btn--block" id="checkoutBtn">Checkout</button>
    <button class="btn btn--ghost btn--block btn--sm" id="cartToggleClose">Continue shopping</button>
  </footer>
</aside>

<div class="scrim" id="scrim" hidden></div>

<div class="modal" id="quickModal" role="dialog" aria-modal="true" aria-labelledby="qvName" hidden>
  <div class="modal__panel modal__panel--wide">
    <button class="icon-btn modal__close" id="qvClose" aria-label="Close quick view">
      <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg>
    </button>
    <div class="qv" id="qvBody"><!-- JS --></div>
  </div>
</div>

<div class="modal" id="checkoutModal" role="dialog" aria-modal="true" aria-labelledby="coTitle" hidden>
  <div class="modal__panel">
    <button class="icon-btn modal__close" id="coClose" aria-label="Close checkout">
      <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg>
    </button>
    <h2 id="coTitle" class="modal__title">Checkout</h2>
    <p class="modal__sub">Demo only — no payment is processed.</p>
    <form class="co-form" id="coForm" method="post" action="../actions/checkout.php">
      <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($selfRedirect); ?>" />
      <div class="field"><label for="coName">Full name</label><input id="coName" name="name" required autocomplete="name" /></div>
      <div class="field"><label for="coEmail">Email</label><input id="coEmail" name="email" type="email" required autocomplete="email" /></div>
      <div class="field field--full"><label for="coAddr">Address</label><input id="coAddr" name="address" required autocomplete="street-address" /></div>
      <div class="field"><label for="coCity">City</label><input id="coCity" name="city" required autocomplete="address-level2" /></div>
      <div class="field"><label for="coPhone">Phone number</label><input id="coPhone" name="phone" type="tel" required autocomplete="tel" /></div>
      <p class="co-form__error" id="coError" role="alert" hidden></p>
      <div class="co-form__foot">
        <span>Total <strong id="coTotal">NPR 0.00</strong></span>
        <button class="btn btn--primary" type="submit">Place order</button>
      </div>
    </form>
  </div>
</div>

<button class="fab" id="backTop" aria-label="Back to top" hidden>
  <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 19V6M5 12l7-7 7 7"/></svg>
</button>

<div class="toasts" id="toasts" role="status" aria-live="polite"></div>

<script>
  const PRODUCTS = <?php echo json_encode($products); ?>;
  const CATEGORIES = <?php echo json_encode($categories); ?>;
  const CART_ITEMS = <?php echo json_encode($cartItems); ?>;
  const CART_SUBTOTAL = <?php echo json_encode($cartSubtotal); ?>;
  const CART_COUNT = <?php echo json_encode($cartCount); ?>;
  <?php if ($checkoutError): ?>
  window.__checkoutError = <?php echo json_encode($checkoutError); ?>;
  <?php endif; ?>
</script>
<script src="../js/data.js"></script>
<script src="../js/state.js"></script>
<script src="../js/utils.js"></script>
<script src="../js/catalog.js"></script>
<script src="../js/reviews.js"></script>
<script src="../js/cart.js"></script>
<script src="../js/modals.js"></script>
<script src="../js/before-after.js"></script>
<script src="../js/nav-ui.js"></script>
<script src="../js/main.js"></script>
</body>
</html>
