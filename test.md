
2. config/database.php
php
<?php
// Database configuration
$host = 'localhost';
$dbname = 'skincare_store';
$username = 'root';
$password = '';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8");

// Start session if not started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
3. includes/header.php
php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkinCare Store</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <a href="index.php">✨ SkinCare</a>
            </div>
            <div class="nav-links">
                <a href="index.php">Home</a>
                <a href="products.php">Products</a>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="cart.php">🛒 Cart</a>
                    <a href="logout.php">Logout</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="register.php">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <main>
4. includes/footer.php
php
    </main>
    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 SkinCare Store. All rights reserved.</p>
        </div>
    </footer>
    <script src="js/script.js"></script>
</body>
</html>
5. includes/functions.php
php
<?php
function sanitize($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] == 'admin';
}

function getCartCount($conn, $user_id) {
    $result = $conn->query("SELECT SUM(quantity) as total FROM cart WHERE user_id = $user_id");
    $row = $result->fetch_assoc();
    return $row['total'] ?? 0;
}

function getProduct($conn, $id) {
    $id = (int)$id;
    $result = $conn->query("SELECT * FROM products WHERE id = $id");
    return $result->fetch_assoc();
}

function formatPrice($price) {
    return '$' . number_format($price, 2);
}
?>
6. css/style.css
css
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.navbar {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 1rem 0;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    position: sticky;
    top: 0;
    z-index: 1000;
}

.navbar .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.nav-brand a {
    color: white;
    font-size: 1.5rem;
    font-weight: 700;
    text-decoration: none;
    letter-spacing: 1px;
}

.nav-links a {
    color: white;
    text-decoration: none;
    margin-left: 2rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.nav-links a:hover {
    transform: translateY(-2px);
    text-shadow: 0 2px 10px rgba(255,255,255,0.3);
}

.hero {
    text-align: center;
    padding: 4rem 0;
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    border-radius: 20px;
    margin: 2rem 0;
    color: white;
}

.hero h1 {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 2rem;
    padding: 2rem 0;
}

.product-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.product-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.12);
}

.product-card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 10px;
    background: #f0f0f0;
}

.product-card h3 {
    margin: 1rem 0 0.5rem;
    color: #333;
}

.product-card .price {
    font-size: 1.25rem;
    color: #764ba2;
    font-weight: 600;
    margin: 0.5rem 0;
}

.btn {
    display: inline-block;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0.5rem 1.5rem;
    border-radius: 25px;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    font-family: 'Poppins', sans-serif;
    font-weight: 500;
}

.btn:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.btn-secondary {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.auth-container {
    max-width: 400px;
    margin: 3rem auto;
    padding: 2rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.auth-container h2 {
    text-align: center;
    margin-bottom: 2rem;
    color: #333;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: #555;
    font-weight: 500;
}

.form-group input,
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
    font-family: 'Poppins', sans-serif;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
    outline: none;
    border-color: #764ba2;
}

.btn-primary {
    width: 100%;
    padding: 0.75rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    font-family: 'Poppins', sans-serif;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.cart-table {
    width: 100%;
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.cart-table th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1rem;
    text-align: left;
}

.cart-table td {
    padding: 1rem;
    border-bottom: 1px solid #f0f0f0;
}

.cart-total {
    text-align: right;
    font-size: 1.5rem;
    font-weight: 600;
    margin: 2rem 0;
}

.alert {
    padding: 1rem;
    border-radius: 8px;
    margin: 1rem 0;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.alert-info {
    background: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
}

.footer {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-align: center;
    padding: 2rem 0;
    margin-top: 3rem;
}

.filter-section {
    background: white;
    padding: 1.5rem;
    border-radius: 15px;
    margin-bottom: 2rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.filter-form {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    align-items: center;
}

.filter-form select,
.filter-form input {
    padding: 0.5rem;
    border-radius: 8px;
    border: 2px solid #e0e0e0;
    font-family: 'Poppins', sans-serif;
}

.filter-form input[type="text"] {
    flex: 1;
    min-width: 200px;
}

.filter-form button {
    padding: 0.5rem 1.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-family: 'Poppins', sans-serif;
    font-weight: 500;
}

.product-detail-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    padding: 3rem 0;
}

.product-detail-image {
    width: 100%;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.product-detail-info h1 {
    margin-bottom: 0.5rem;
}

.product-detail-price {
    font-size: 2rem;
    color: #764ba2;
    font-weight: 600;
    margin: 1rem 0;
}

.product-detail-meta {
    background: #f5f5f5;
    padding: 1rem;
    border-radius: 10px;
    margin: 1rem 0;
}

.stock-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
}

.stock-in {
    background: #d4edda;
    color: #155724;
}

.stock-out {
    background: #f8d7da;
    color: #721c24;
}

@media (max-width: 768px) {
    .product-detail-container {
        grid-template-columns: 1fr;
    }
    
    .nav-links a {
        margin-left: 1rem;
        font-size: 0.9rem;
    }
    
    .hero h1 {
        font-size: 2rem;
    }
}
7. js/script.js
javascript
document.addEventListener('DOMContentLoaded', function() {
    // Cart quantity update
    const quantityInputs = document.querySelectorAll('.quantity-input');
    quantityInputs.forEach(input => {
        input.addEventListener('change', function() {
            const cartId = this.dataset.cartId;
            const quantity = this.value;
            if (quantity > 0) {
                window.location.href = `update_cart.php?id=${cartId}&quantity=${quantity}`;
            }
        });
    });

    // Add to cart with animation
    const addToCartBtns = document.querySelectorAll('.add-to-cart');
    addToCartBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            const productId = this.dataset.productId;
            // Show loading state
            this.textContent = 'Adding...';
            this.style.opacity = '0.7';
            
            // Redirect to add to cart
            window.location.href = `add_to_cart.php?product_id=${productId}`;
        });
    });

    // Form validation
    const forms = document.querySelectorAll('form[data-validate]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = '#f5576c';
                } else {
                    field.style.borderColor = '#e0e0e0';
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields.');
            }
        });
    });

    // Auto-dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
});
8. index.php
php
<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
include 'includes/header.php';

// Get featured products (latest 6)
$result = $conn->query("SELECT * FROM products ORDER BY created_at DESC LIMIT 6");
?>

<div class="container">
    <div class="hero">
        <h1>✨ Glow Up with SkinCare</h1>
        <p>Discover premium skincare products for radiant, healthy skin</p>
        <a href="products.php" class="btn" style="display:inline-block;margin-top:1rem;background:white;color:#764ba2;padding:0.75rem 2rem;border-radius:30px;text-decoration:none;font-weight:600;">Shop Now</a>
    </div>

    <h2 style="text-align:center;margin:2rem 0;">Featured Products</h2>
    <div class="products-grid">
        <?php if($result->num_rows > 0): ?>
            <?php while($product = $result->fetch_assoc()): ?>
                <div class="product-card">
                    <img src="images/<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" onerror="this.src='https://via.placeholder.com/300x200/667eea/ffffff?text=Skincare'">
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p style="color:#666;font-size:0.9rem;"><?php echo htmlspecialchars($product['brand']); ?> • <?php echo htmlspecialchars($product['category']); ?></p>
                    <div class="price">$<?php echo number_format($product['price'], 2); ?></div>
                    <a href="product_details.php?id=<?php echo $product['id']; ?>" class="btn">View Details</a>
                    <?php if(isLoggedIn()): ?>
                        <a href="add_to_cart.php?product_id=<?php echo $product['id']; ?>" class="btn add-to-cart" data-product-id="<?php echo $product['id']; ?>" style="margin-top:0.5rem;">Add to Cart</a>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="grid-column:1/-1;text-align:center;padding:2rem;">No products available.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
9. products.php
php
<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
include 'includes/header.php';

// Get filter parameters
$category = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';
$skin_type = isset($_GET['skin_type']) ? $conn->real_escape_string($_GET['skin_type']) : '';
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Build query
$sql = "SELECT * FROM products WHERE 1=1";

if($category) {
    $sql .= " AND category = '$category'";
}

if($skin_type) {
    $sql .= " AND (skin_type = '$skin_type' OR skin_type = 'All')";
}

if($search) {
    $sql .= " AND (name LIKE '%$search%' OR description LIKE '%$search%')";
}

$sql .= " ORDER BY created_at DESC";
$result = $conn->query($sql);

// Get categories for filter
$categories = $conn->query("SELECT DISTINCT category FROM products");
$skin_types = $conn->query("SELECT DISTINCT skin_type FROM products WHERE skin_type != 'All'");
?>

<div class="container">
    <h1 style="margin:2rem 0;">Our Products</h1>
    
    <!-- Filters -->
    <div class="filter-section">
        <form method="GET" class="filter-form">
            <select name="category">
                <option value="">All Categories</option>
                <?php while($cat = $categories->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($cat['category']); ?>" <?php echo $category == $cat['category'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat['category']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
            
            <select name="skin_type">
                <option value="">All Skin Types</option>
                <?php while($type = $skin_types->fetch_assoc()): ?>
                    <option value="<?php echo htmlspecialchars($type['skin_type']); ?>" <?php echo $skin_type == $type['skin_type'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($type['skin_type']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
            
            <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Filter</button>
            <?php if($category || $skin_type || $search): ?>
                <a href="products.php" style="color:#764ba2;text-decoration:none;">Clear Filters</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="products-grid">
        <?php if($result->num_rows > 0): ?>
            <?php while($product = $result->fetch_assoc()): ?>
                <div class="product-card">
                    <img src="images/<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" onerror="this.src='https://via.placeholder.com/300x200/667eea/ffffff?text=Skincare'">
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p style="color:#666;font-size:0.9rem;"><?php echo htmlspecialchars($product['brand']); ?> • <?php echo htmlspecialchars($product['category']); ?></p>
                    <div class="price">$<?php echo number_format($product['price'], 2); ?></div>
                    <a href="product_details.php?id=<?php echo $product['id']; ?>" class="btn">View Details</a>
                    <?php if(isLoggedIn()): ?>
                        <a href="add_to_cart.php?product_id=<?php echo $product['id']; ?>" class="btn add-to-cart" data-product-id="<?php echo $product['id']; ?>" style="margin-top:0.5rem;">Add to Cart</a>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="grid-column:1/-1;text-align:center;padding:2rem;">No products found. Try adjusting your filters.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
10. product_details.php
php
<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
include 'includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = getProduct($conn, $id);

if(!$product) {
    header('Location: products.php');
    exit;
}
?>

<div class="container">
    <div class="product-detail-container">
        <div>
            <img src="images/<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-detail-image" onerror="this.src='https://via.placeholder.com/500x500/667eea/ffffff?text=Skincare'">
        </div>
        <div class="product-detail-info">
            <h1><?php echo htmlspecialchars($product['name']); ?></h1>
            <p style="color:#666;margin:0.5rem 0;"><?php echo htmlspecialchars($product['brand']); ?> • <?php echo htmlspecialchars($product['category']); ?></p>
            <div class="product-detail-price">$<?php echo number_format($product['price'], 2); ?></div>
            
            <div class="product-detail-meta">
                <p><strong>Skin Type:</strong> <?php echo htmlspecialchars($product['skin_type']); ?></p>
                <p><strong>Stock:</strong> 
                    <span class="stock-badge <?php echo $product['stock'] > 0 ? 'stock-in' : 'stock-out'; ?>">
                        <?php echo $product['stock'] > 0 ? $product['stock'] . ' units available' : 'Out of Stock'; ?>
                    </span>
                </p>
            </div>
            
            <p style="margin:1rem 0;line-height:1.8;"><?php echo htmlspecialchars($product['description']); ?></p>
            
            <?php if($product['stock'] > 0): ?>
                <?php if(isLoggedIn()): ?>
                    <a href="add_to_cart.php?product_id=<?php echo $product['id']; ?>" class="btn" style="display:inline-block;padding:1rem 3rem;font-size:1.1rem;">Add to Cart</a>
                <?php else: ?>
                    <a href="login.php" class="btn" style="display:inline-block;padding:1rem 3rem;font-size:1.1rem;">Login to Buy</a>
                <?php endif; ?>
            <?php else: ?>
                <p style="color:#f5576c;font-weight:600;font-size:1.2rem;">Out of Stock</p>
            <?php endif; ?>
            
            <a href="products.php" style="display:block;margin-top:1.5rem;color:#667eea;text-decoration:none;">← Back to Products</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
11. login.php
php
<?php
require_once 'config/database.php';
include 'includes/header.php';

$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    
    $result = $conn->query("SELECT * FROM users WHERE username = '$username' OR email = '$username'");
    
    if($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if(password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['full_name'] = $user['full_name'];
            
            header('Location: index.php');
            exit;
        } else {
            $error = 'Invalid password.';
        }
    } else {
        $error = 'User not found.';
    }
}
?>

<div class="container">
    <div class="auth-container">
        <h2>Login</h2>
        
        <?php if($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" data-validate>
            <div class="form-group">
                <label for="username">Username or Email</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn-primary">Login</button>
        </form>
        
        <p style="text-align:center;margin-top:1.5rem;">
            Don't have an account? <a href="register.php" style="color:#764ba2;text-decoration:none;font-weight:500;">Register</a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
12. register.php
php
<?php
require_once 'config/database.php';
include 'includes/header.php';

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $full_name = $conn->real_escape_string($_POST['full_name']);
    
    // Check if username or email exists
    $check = $conn->query("SELECT id FROM users WHERE username = '$username' OR email = '$email'");
    
    if($check->num_rows > 0) {
        $error = 'Username or email already exists.';
    } else {
        $sql = "INSERT INTO users (username, email, password, full_name) VALUES ('$username', '$email', '$password', '$full_name')";
        
        if($conn->query($sql)) {
            $success = 'Registration successful! Please login.';
        } else {
            $error = 'Registration failed. Please try again.';
        }
    }
}
?>

<div class="container">
    <div class="auth-container">
        <h2>Create Account</h2>
        
        <?php if($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="POST" data-validate>
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" required>
            </div>
            
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required minlength="6">
            </div>
            
            <button type="submit" class="btn-primary">Register</button>
        </form>
        
        <p style="text-align:center;margin-top:1.5rem;">
            Already have an account? <a href="login.php" style="color:#764ba2;text-decoration:none;font-weight:500;">Login</a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
13. logout.php
php
<?php
session_start();
session_destroy();
header('Location: index.php');
exit;
?>
14. cart.php
php
<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
include 'includes/header.php';

if(!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Get cart items
$result = $conn->query("
    SELECT c.*, p.name, p.price, p.image_url, p.stock 
    FROM cart c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_id = $user_id
");

// Calculate total
$total = 0;
$cart_items = [];
while($item = $result->fetch_assoc()) {
    $cart_items[] = $item;
    $total += $item['price'] * $item['quantity'];
}
?>

<div class="container">
    <h1 style="margin:2rem 0;">Shopping Cart</h1>
    
    <?php if(count($cart_items) > 0): ?>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($cart_items as $item): ?>
                    <tr>
                        <td>
                            <img src="images/<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" style="width:50px;height:50px;object-fit:cover;border-radius:8px;vertical-align:middle;margin-right:10px;" onerror="this.src='https