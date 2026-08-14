<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'updateStatus') {
    $orderId = (int) ($_POST['order_id'] ?? 0);
    $status = $_POST['status'] ?? '';
    $allowed = ['pending', 'processing', 'shipped', 'delivered'];
    if ($orderId > 0 && in_array($status, $allowed, true)) {
        $pdo->prepare('UPDATE orders SET status = ? WHERE id = ?')->execute([$status, $orderId]);
    }
    header('Location: orders.php');
    exit;
}

$orders = $pdo->query(
    'SELECT o.*, u.username, u.email FROM orders o
     JOIN users u ON o.user_id = u.id
     ORDER BY o.order_date DESC'
)->fetchAll();

$itemsStmt = $pdo->prepare(
    'SELECT oi.quantity, oi.price, p.name FROM order_items oi
     JOIN products p ON oi.product_id = p.id
     WHERE oi.order_id = ?'
);

$pageTitle = 'Manage Orders — Korean Point';
include __DIR__ . '/../includes/header.php';
?>

<div class="container section">
  <?php $activePage = 'orders'; include __DIR__ . '/_nav.php'; ?>

  <h2>Orders</h2>

  <table class="table">
    <thead>
      <tr><th>Order #</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th><th>Items</th></tr>
    </thead>
    <tbody>
      <?php foreach ($orders as $o): $itemsStmt->execute([$o['id']]); $items = $itemsStmt->fetchAll(); ?>
        <tr>
          <td>#<?php echo $o['id']; ?></td>
          <td><?php echo htmlspecialchars($o['username']); ?><br><small><?php echo htmlspecialchars($o['email']); ?></small></td>
          <td><?php echo formatPrice($o['total_amount']); ?></td>
          <td>
            <form method="post" action="orders.php" style="display:flex;gap:6px;align-items:center;">
              <input type="hidden" name="action" value="updateStatus">
              <input type="hidden" name="order_id" value="<?php echo $o['id']; ?>">
              <select name="status">
                <?php foreach (['pending', 'processing', 'shipped', 'delivered'] as $s): ?>
                  <option value="<?php echo $s; ?>" <?php echo $o['status'] === $s ? 'selected' : ''; ?>><?php echo ucfirst($s); ?></option>
                <?php endforeach; ?>
              </select>
              <button type="submit" class="btn btn-sm">Update</button>
            </form>
          </td>
          <td><?php echo htmlspecialchars($o['order_date']); ?></td>
          <td>
            <details>
              <summary>View</summary>
              <ul>
                <?php foreach ($items as $it): ?>
                  <li><?php echo (int) $it['quantity']; ?> &times; <?php echo htmlspecialchars($it['name']); ?> (<?php echo formatPrice($it['price']); ?> each)</li>
                <?php endforeach; ?>
              </ul>
            </details>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (count($orders) === 0): ?>
        <tr><td colspan="6" class="empty">No orders yet.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
