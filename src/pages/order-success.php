<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$orderId = $_GET['id'] ?? '';
$pageTitle = 'Order Confirmed — Korean Point';
include __DIR__ . '/../includes/header.php';
?>

<div class="auth-card" style="text-align:center;">
  <h1>✅ Order confirmed</h1>
  <p class="sub">Order <strong>#<?php echo htmlspecialchars($orderId); ?></strong> is on its way. A receipt is in your inbox.</p>
  <a class="btn btn-block" href="shop.php">Keep glowing</a>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
