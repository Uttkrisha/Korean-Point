<?php
// Shared page head + nav. Expects $conn, functions.php and session to
// already be loaded, and an optional $pageTitle set before including this.
$pageTitle = $pageTitle ?? 'Korean Point';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title><?php echo htmlspecialchars($pageTitle); ?></title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Noto+Sans+KR:wght@400;500;700&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="../css/style.css" />
</head>
<body>

<header class="nav">
  <div class="container nav__inner">
    <a class="logo" href="../pages/index.php">🌿 Korean Point</a>

    <button class="hamburger" id="hamburger" aria-label="Open menu" aria-expanded="false" type="button">☰</button>

    <nav class="nav__links" id="navLinks">
      <a href="../pages/index.php">Home</a>
      <a href="../pages/shop.php">Shop</a>
      <a href="../pages/about.php">About</a>
      <?php if (isLoggedIn()): ?>
        <?php $navCartCount = getCartCount($conn, $_SESSION['user_id']); ?>
        <a href="../pages/cart.php">Cart<?php if ($navCartCount > 0): ?> (<?php echo $navCartCount; ?>)<?php endif; ?></a>
        <?php if (isAdmin()): ?>
          <a href="../admin/index.php">Admin</a>
        <?php endif; ?>
        <a href="../actions/logout.php">Logout</a>
      <?php else: ?>
        <a href="../pages/login.php">Login</a>
        <a href="../pages/register.php">Register</a>
      <?php endif; ?>
    </nav>
  </div>
</header>

<main>
