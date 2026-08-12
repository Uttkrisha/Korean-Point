<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$orderId = $_GET['id'] ?? '';
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Order Confirmed — Korean Point</title>
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Noto+Sans+KR:wght@300;400;500;700&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="../css/variables.css" />
<link rel="stylesheet" href="../css/base.css" />
<link rel="stylesheet" href="../css/overlays.css" />
<link rel="stylesheet" href="../css/auth.css" />
<link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🌿</text></svg>" />
</head>
<body>

<main class="auth-page">
  <div class="auth-card" style="text-align:center;">
    <a class="logo" href="index.php">
      <span class="logo__leaf" aria-hidden="true">🌿</span>
      <span class="logo__text">Korean Point</span>
    </a>
    <div class="tick" aria-hidden="true">
      <svg viewBox="0 0 52 52"><circle cx="26" cy="26" r="24"/><path d="M14 27l8 8 16-16"/></svg>
    </div>
    <h1>Order confirmed</h1>
    <p class="section__sub">Order <strong>#<?php echo htmlspecialchars($orderId); ?></strong> is on its way. A receipt is in your inbox.</p>
    <a class="btn btn--primary btn--block" href="shop.php">Keep glowing</a>
  </div>
</main>

</body>
</html>
