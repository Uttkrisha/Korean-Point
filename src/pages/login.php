<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../../config/database.php';

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT id, name, email, password FROM users WHERE email = ?');
    $stmt->execute([strtolower($email)]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        $error = 'Invalid email or password.';
    } else {
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Log In — Korean Point</title>
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Noto+Sans+KR:wght@300;400;500;700&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="../css/variables.css" />
<link rel="stylesheet" href="../css/base.css" />
<link rel="stylesheet" href="../css/overlays.css" />
<link rel="stylesheet" href="../css/auth.css" />
<link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🌿</text></svg>" />
</head>
<body>

<main class="auth-page">
  <div class="auth-card">
    <a class="logo" href="index.php">
      <span class="logo__leaf" aria-hidden="true">🌿</span>
      <span class="logo__text">Korean Point</span>
    </a>
    <h1>Welcome back</h1>
    <p class="section__sub">Log in to shop your Korean skincare routine.</p>

    <form class="auth-form" method="post" action="login.php">
      <div class="field">
        <label for="loginEmail">Email</label>
        <input id="loginEmail" name="email" type="email" required autocomplete="email" value="<?php echo htmlspecialchars($email); ?>" />
      </div>
      <div class="field">
        <label for="loginPassword">Password</label>
        <input id="loginPassword" name="password" type="password" required autocomplete="current-password" />
      </div>
      <?php if ($error): ?>
        <p class="auth-form__error" role="alert"><?php echo htmlspecialchars($error); ?></p>
      <?php endif; ?>
      <button class="btn btn--primary btn--block" type="submit">Log In</button>
    </form>

    <p class="auth-switch">Don't have an account? <a href="register.php">Register</a></p>
  </div>
</main>

</body>
</html>
