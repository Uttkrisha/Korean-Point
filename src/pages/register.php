<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';
$fullName = '';
$username = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if (!preg_match('/^[A-Za-z ]{2,50}$/', $fullName)) {
        $error = 'Full name must be letters only (2-50 characters).';
    } elseif (!preg_match('/^[A-Za-z0-9_]{3,20}$/', $username)) {
        $error = 'Username must be 3-20 characters (letters, numbers, underscore).';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $emailLower = strtolower($email);
        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
        $stmt->execute([$username, $emailLower]);
        if ($stmt->fetch()) {
            $error = 'An account with this username or email already exists.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (username, email, password, full_name) VALUES (?, ?, ?, ?)');
            $stmt->execute([$username, $emailLower, $hash, $fullName]);

            $_SESSION['user_id'] = (int) $pdo->lastInsertId();
            $_SESSION['username'] = $username;
            $_SESSION['full_name'] = $fullName;
            $_SESSION['role'] = 'user';
            header('Location: index.php');
            exit;
        }
    }
}

$pageTitle = 'Register — Korean Point';
include __DIR__ . '/../includes/header.php';
?>

<div class="auth-card">
  <h1>Create your account</h1>
  <p class="sub">Join Korean Point for a personalized skincare routine.</p>

  <?php if ($error): ?>
    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <form method="post" action="register.php">
    <div class="field">
      <label for="full_name">Full name</label>
      <input id="full_name" name="full_name" type="text" required value="<?php echo htmlspecialchars($fullName); ?>">
    </div>
    <div class="field">
      <label for="username">Username</label>
      <input id="username" name="username" type="text" required value="<?php echo htmlspecialchars($username); ?>">
    </div>
    <div class="field">
      <label for="email">Email</label>
      <input id="email" name="email" type="email" required value="<?php echo htmlspecialchars($email); ?>">
    </div>
    <div class="field">
      <label for="password">Password</label>
      <input id="password" name="password" type="password" required>
    </div>
    <div class="field">
      <label for="confirm">Confirm password</label>
      <input id="confirm" name="confirm" type="password" required>
    </div>
    <button type="submit" class="btn btn-block">Register</button>
  </form>

  <p class="auth-switch">Already have an account? <a href="login.php">Log in</a></p>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
