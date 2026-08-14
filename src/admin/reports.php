<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$totalRevenue = (float) $pdo->query('SELECT COALESCE(SUM(total_amount), 0) FROM orders')->fetchColumn();

$statusCounts = $pdo->query(
    'SELECT status, COUNT(*) AS total FROM orders GROUP BY status'
)->fetchAll(PDO::FETCH_KEY_PAIR);

$topProducts = $pdo->query(
    'SELECT p.name, SUM(oi.quantity) AS units_sold, SUM(oi.quantity * oi.price) AS revenue
     FROM order_items oi
     JOIN products p ON oi.product_id = p.id
     GROUP BY oi.product_id
     ORDER BY units_sold DESC
     LIMIT 5'
)->fetchAll();

$lowStock = $pdo->query('SELECT name, category, stock FROM products WHERE stock < 5 ORDER BY stock ASC')->fetchAll();

$statuses = ['pending', 'processing', 'shipped', 'delivered'];

$pageTitle = 'Reports — Korean Point';
include __DIR__ . '/../includes/header.php';
?>

<div class="container section">
  <?php $activePage = 'reports'; include __DIR__ . '/_nav.php'; ?>

  <h2>Reports</h2>

  <div class="stat-grid">
    <div class="stat-card"><strong><?php echo formatPrice($totalRevenue); ?></strong><span>Total Revenue</span></div>
    <?php foreach ($statuses as $s): ?>
      <div class="stat-card"><strong><?php echo (int) ($statusCounts[$s] ?? 0); ?></strong><span><?php echo ucfirst($s); ?> Orders</span></div>
    <?php endforeach; ?>
  </div>

  <h3>Top Selling Products</h3>
  <?php if (count($topProducts) > 0): ?>
    <table class="table">
      <thead><tr><th>Product</th><th>Units Sold</th><th>Revenue</th></tr></thead>
      <tbody>
        <?php foreach ($topProducts as $p): ?>
          <tr>
            <td><?php echo htmlspecialchars($p['name']); ?></td>
            <td><?php echo (int) $p['units_sold']; ?></td>
            <td><?php echo formatPrice($p['revenue']); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p class="empty">No sales yet.</p>
  <?php endif; ?>

  <h3>Low Stock (under 5 units)</h3>
  <?php if (count($lowStock) > 0): ?>
    <table class="table">
      <thead><tr><th>Product</th><th>Category</th><th>Stock</th></tr></thead>
      <tbody>
        <?php foreach ($lowStock as $p): ?>
          <tr>
            <td><?php echo htmlspecialchars($p['name']); ?></td>
            <td><?php echo htmlspecialchars($p['category']); ?></td>
            <td class="stock-out"><?php echo (int) $p['stock']; ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p class="empty">Nothing low on stock.</p>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
