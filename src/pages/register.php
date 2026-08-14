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
        $existing = dbQuery($conn, 'SELECT id FROM users WHERE username = ? OR email = ?', 'ss', [$username, $emailLower])->fetch_assoc();
        if ($existing) {
            $error = 'An account with this username or email already exists.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            dbExec(
                $conn,
                'INSERT INTO users (username, email, password, full_name) VALUES (?, ?, ?, ?)',
                'ssss',
                [$username, $emailLower, $hash, $fullName]
            );

            $_SESSION['user_id'] = (int) $conn->insert_id;
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

<div class="auth-page">
  <div class="auth-card">
    <h1>Create Your Account</h1>
    <p class="sub">Join Korean Point for a personalized skincare routine.</p>

    <?php if ($error): ?>
      <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="post" action="register.php">
      <div class="field">
        <label for="full_name" class="sr-only">Full name</label>
        <input id="full_name" name="full_name" type="text" placeholder="Full Name" required value="<?php echo htmlspecialchars($fullName); ?>">
      </div>
      <div class="field">
        <label for="username" class="sr-only">Username</label>
        <input id="username" name="username" type="text" placeholder="Username" required value="<?php echo htmlspecialchars($username); ?>">
      </div>
      <div class="field">
        <label for="email" class="sr-only">Email</label>
        <input id="email" name="email" type="email" placeholder="Email" required value="<?php echo htmlspecialchars($email); ?>">
      </div>
      <div class="field">
        <label for="password" class="sr-only">Password</label>
        <input id="password" name="password" type="password" placeholder="Password" required>
      </div>
      <div class="field">
        <label for="confirm" class="sr-only">Confirm password</label>
        <input id="confirm" name="confirm" type="password" placeholder="Confirm Password" required>
      </div>
      <button type="submit" class="btn btn-block">Register</button>
    </form>

    <p class="auth-switch">Already have an account? <a href="login.php">Login</a></p>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
