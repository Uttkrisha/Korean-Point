<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $sql = 'SELECT id, username, email, password, full_name, role FROM users WHERE username = ? OR email = ?';
    $user = dbQuery($conn, $sql, 'ss', [$username, $username])->fetch_assoc();

    if (!$user || !password_verify($password, $user['password'])) {
        $error = 'Invalid username/email or password.';
    } else {
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role'] = $user['role'];
        header('Location: index.php');
        exit;
    }
}

$pageTitle = 'Log In — Korean Point';
include __DIR__ . '/../includes/header.php';
?>

<div class="auth-page">
  <div class="auth-card">
    <h1>Welcome back</h1>
    <p class="sub">Log in to shop your Korean skincare routine.</p>

    <?php if ($error): ?>
      <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="post" action="login.php">
      <div class="field">
        <label for="username" class="sr-only">Username or Email</label>
        <input id="username" name="username" type="text" placeholder="Username or Email" required value="<?php echo htmlspecialchars($username); ?>">
      </div>
      <div class="field">
        <label for="password" class="sr-only">Password</label>
        <input id="password" name="password" type="password" placeholder="Password" required>
      </div>
      <button type="submit" class="btn btn-block">Log In</button>
    </form>

    <p class="auth-switch">Don't have an account? <a href="register.php">Register</a></p>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
