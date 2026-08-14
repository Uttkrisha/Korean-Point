<?php
// Shared sub-nav for admin pages. $activePage should be set by the
// including page (e.g. $activePage = 'products';) before including this.
$activePage = $activePage ?? '';
?>
<div class="admin-nav">
  <a href="index.php" class="<?php echo $activePage === 'dashboard' ? 'is-active' : ''; ?>">Dashboard</a>
  <a href="products.php" class="<?php echo $activePage === 'products' ? 'is-active' : ''; ?>">Products</a>
  <a href="orders.php" class="<?php echo $activePage === 'orders' ? 'is-active' : ''; ?>">Orders</a>
  <a href="reports.php" class="<?php echo $activePage === 'reports' ? 'is-active' : ''; ?>">Reports</a>
</div>
