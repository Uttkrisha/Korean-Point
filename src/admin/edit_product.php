<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$id = isset($_GET['id']) ? (int) $_GET['id'] : (int) ($_POST['id'] ?? 0);
$product = getProduct($conn, $id);

if (!$product) {
    header('Location: products.php');
    exit;
}

$error = '';

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
        dbExec(
            $conn,
            'UPDATE products SET name=?, description=?, price=?, category=?, brand=?, skin_type=?, image_url=?, stock=? WHERE id=?',
            'ssdssssii',
            [$name, $description, (float) $price, $category, $brand, $skinType, $imageUrl, (int) $stock, $id]
        );
        header('Location: products.php');
        exit;
    }
    // keep the submitted values on screen if validation failed
    $product = array_merge($product, [
        'name' => $name, 'description' => $description, 'price' => $price,
        'category' => $category, 'brand' => $brand, 'skin_type' => $skinType,
        'image_url' => $imageUrl, 'stock' => $stock,
    ]);
}

$categories = array_column(dbQuery($conn, 'SELECT DISTINCT category FROM products ORDER BY category')->fetch_all(MYSQLI_ASSOC), 'category');
$skinTypes = array_column(dbQuery($conn, 'SELECT DISTINCT skin_type FROM products ORDER BY skin_type')->fetch_all(MYSQLI_ASSOC), 'skin_type');

$pageTitle = 'Edit Product — Korean Point';
include __DIR__ . '/../includes/header.php';
?>

<div class="container section">
  <?php $activePage = 'products'; include __DIR__ . '/_nav.php'; ?>

  <h2>Edit Product</h2>

  <?php if ($error): ?>
    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <form method="post" action="edit_product.php?id=<?php echo $product['id']; ?>" style="max-width:560px;">
    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
    <div class="field">
      <label for="name">Name</label>
      <input id="name" name="name" required value="<?php echo htmlspecialchars($product['name']); ?>">
    </div>
    <div class="field">
      <label for="description">Description</label>
      <textarea id="description" name="description" rows="3"><?php echo htmlspecialchars($product['description']); ?></textarea>
    </div>
    <div class="field">
      <label for="price">Price</label>
      <input id="price" name="price" type="number" step="0.01" min="0" required value="<?php echo htmlspecialchars($product['price']); ?>">
    </div>
    <div class="field">
      <label for="category">Category</label>
      <input id="category" name="category" list="categoryList" required value="<?php echo htmlspecialchars($product['category']); ?>">
      <datalist id="categoryList">
        <?php foreach ($categories as $c): ?><option value="<?php echo htmlspecialchars($c); ?>"><?php endforeach; ?>
      </datalist>
    </div>
    <div class="field">
      <label for="brand">Brand</label>
      <input id="brand" name="brand" value="<?php echo htmlspecialchars($product['brand']); ?>">
    </div>
    <div class="field">
      <label for="skin_type">Skin Type</label>
      <input id="skin_type" name="skin_type" list="skinTypeList" value="<?php echo htmlspecialchars($product['skin_type']); ?>">
      <datalist id="skinTypeList">
        <?php foreach ($skinTypes as $t): ?><option value="<?php echo htmlspecialchars($t); ?>"><?php endforeach; ?>
      </datalist>
    </div>
    <div class="field">
      <label for="image_url">Image URL</label>
      <input id="image_url" name="image_url" value="<?php echo htmlspecialchars($product['image_url']); ?>">
    </div>
    <div class="field">
      <label for="stock">Stock</label>
      <input id="stock" name="stock" type="number" min="0" required value="<?php echo htmlspecialchars($product['stock']); ?>">
    </div>
    <button type="submit" class="btn">Save Changes</button>
    <a href="products.php" class="btn btn-outline">Cancel</a>
  </form>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
