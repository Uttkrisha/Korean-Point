<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';
$name = '';
$email = '';
$birthdate = '';
$today = date('Y-m-d');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $birthdate = $_POST['birthdate'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if (!preg_match('/^[A-Za-z ]{2,50}$/', $name)) {
        $error = 'Name must be letters only (2-50 characters).';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (!$birthdate || $birthdate > $today) {
        $error = 'Birthdate cannot be in the future.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $emailLower = strtolower($email);
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$emailLower]);
        if ($stmt->fetch()) {
            $error = 'An account with this email already exists.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (name, email, birthdate, password) VALUES (?, ?, ?, ?)');
            $stmt->execute([$name, $emailLower, $birthdate, $hash]);

            $_SESSION['user_id'] = (int) $pdo->lastInsertId();
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $emailLower;
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
      <label for="name">Full name</label>
      <input id="name" name="name" type="text" required value="<?php echo htmlspecialchars($name); ?>">
    </div>
    <div class="field">
      <label for="email">Email</label>
      <input id="email" name="email" type="email" required value="<?php echo htmlspecialchars($email); ?>">
    </div>
    <div class="field">
      <label for="birthdate">Birthdate</label>
      <input id="birthdate" name="birthdate" type="date" required max="<?php echo $today; ?>" value="<?php echo htmlspecialchars($birthdate); ?>">
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
