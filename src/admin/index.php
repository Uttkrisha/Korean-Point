<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$totalProducts = (int) $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
$totalOrders = (int) $pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn();
$totalUsers = (int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$totalRevenue = (float) $pdo->query('SELECT COALESCE(SUM(total_amount), 0) FROM orders')->fetchColumn();

$recentOrders = $pdo->query(
    'SELECT o.*, u.username FROM orders o
     JOIN users u ON o.user_id = u.id
     ORDER BY o.order_date DESC LIMIT 5'
)->fetchAll();

$lowStock = $pdo->query('SELECT * FROM products WHERE stock < 5 ORDER BY stock ASC')->fetchAll();

$pageTitle = 'Admin Dashboard — Korean Point';
include __DIR__ . '/../includes/header.php';
?>

<div class="container section">
  <?php $activePage = 'dashboard'; include __DIR__ . '/_nav.php'; ?>

  <h2>Dashboard</h2>

  <div class="stat-grid">
    <div class="stat-card"><strong><?php echo $totalProducts; ?></strong><span>Products</span></div>
    <div class="stat-card"><strong><?php echo $totalOrders; ?></strong><span>Orders</span></div>
    <div class="stat-card"><strong><?php echo $totalUsers; ?></strong><span>Users</span></div>
    <div class="stat-card"><strong><?php echo formatPrice($totalRevenue); ?></strong><span>Revenue</span></div>
  </div>

  <h3>Recent Orders</h3>
  <?php if (count($recentOrders) > 0): ?>
    <table class="table">
      <thead><tr><th>Order #</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th></tr></thead>
      <tbody>
        <?php foreach ($recentOrders as $o): ?>
          <tr>
            <td>#<?php echo $o['id']; ?></td>
            <td><?php echo htmlspecialchars($o['username']); ?></td>
            <td><?php echo formatPrice($o['total_amount']); ?></td>
            <td><span class="badge-status status-<?php echo htmlspecialchars($o['status']); ?>"><?php echo htmlspecialchars($o['status']); ?></span></td>
            <td><?php echo htmlspecialchars($o['order_date']); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <p><a href="orders.php">View all orders &rarr;</a></p>
  <?php else: ?>
    <p class="empty">No orders yet.</p>
  <?php endif; ?>

  <h3>Low Stock (under 5 units)</h3>
  <?php if (count($lowStock) > 0): ?>
    <table class="table">
      <thead><tr><th>Product</th><th>Category</th><th>Stock</th></tr></thead>
      <tbody>
        <?php foreach ($lowStock as $p): ?>
          <tr>
            <td><a href="edit_product.php?id=<?php echo $p['id']; ?>"><?php echo htmlspecialchars($p['name']); ?></a></td>
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
