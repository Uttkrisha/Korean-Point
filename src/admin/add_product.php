<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$error = '';
$name = '';
$description = '';
$price = '';
$category = '';
$brand = '';
$skinType = '';
$imageUrl = '';
$stock = '10';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $brand = trim($_POST['brand'] ?? '');
    $skinType = trim($_POST['skin_type'] ?? '');
    $imageUrl = trim($_POST['image_url'] ?? '');
    $stock = trim($_POST['stock'] ?? '0');

    if ($name === '') {
        $error = 'Product name is required.';
    } elseif (!is_numeric($price) || $price < 0) {
        $error = 'Price must be a positive number.';
    } elseif (!ctype_digit($stock)) {
        $error = 'Stock must be a whole number.';
    } else {
        $stmt = $pdo->prepare(
            'INSERT INTO products (name, description, price, category, brand, skin_type, image_url, stock)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([$name, $description, $price, $category, $brand, $skinType, $imageUrl, $stock]);
        header('Location: products.php');
        exit;
    }
}

$categories = $pdo->query('SELECT DISTINCT category FROM products ORDER BY category')->fetchAll(PDO::FETCH_COLUMN);
$skinTypes = $pdo->query('SELECT DISTINCT skin_type FROM products ORDER BY skin_type')->fetchAll(PDO::FETCH_COLUMN);

$pageTitle = 'Add Product — Korean Point';
include __DIR__ . '/../includes/header.php';
?>

<div class="container section">
  <?php $activePage = 'products'; include __DIR__ . '/_nav.php'; ?>

  <h2>Add Product</h2>

  <?php if ($error): ?>
    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <form method="post" action="add_product.php" style="max-width:560px;">
    <div class="field">
      <label for="name">Name</label>
      <input id="name" name="name" required value="<?php echo htmlspecialchars($name); ?>">
    </div>
    <div class="field">
      <label for="description">Description</label>
      <textarea id="description" name="description" rows="3"><?php echo htmlspecialchars($description); ?></textarea>
    </div>
    <div class="field">
      <label for="price">Price</label>
      <input id="price" name="price" type="number" step="0.01" min="0" required value="<?php echo htmlspecialchars($price); ?>">
    </div>
    <div class="field">
      <label for="category">Category</label>
      <input id="category" name="category" list="categoryList" required value="<?php echo htmlspecialchars($category); ?>">
      <datalist id="categoryList">
        <?php foreach ($categories as $c): ?><option value="<?php echo htmlspecialchars($c); ?>"><?php endforeach; ?>
      </datalist>
    </div>
    <div class="field">
      <label for="brand">Brand</label>
      <input id="brand" name="brand" value="<?php echo htmlspecialchars($brand); ?>">
    </div>
    <div class="field">
      <label for="skin_type">Skin Type</label>
      <input id="skin_type" name="skin_type" list="skinTypeList" value="<?php echo htmlspecialchars($skinType); ?>">
      <datalist id="skinTypeList">
        <?php foreach ($skinTypes as $t): ?><option value="<?php echo htmlspecialchars($t); ?>"><?php endforeach; ?>
      </datalist>
    </div>
    <div class="field">
      <label for="image_url">Image URL</label>
      <input id="image_url" name="image_url" value="<?php echo htmlspecialchars($imageUrl); ?>">
    </div>
    <div class="field">
      <label for="stock">Stock</label>
      <input id="stock" name="stock" type="number" min="0" required value="<?php echo htmlspecialchars($stock); ?>">
    </div>
    <button type="submit" class="btn">Add Product</button>
    <a href="products.php" class="btn btn-outline">Cancel</a>
  </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
