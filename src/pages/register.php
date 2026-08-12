<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';
$name = '';
$email = '';
$birthdate = '';
$today = date('Y-m-d');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../../config/database.php';

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
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Register — Korean Point</title>
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
    <h1>Create your account</h1>
    <p class="section__sub">Join Korean Point for a personalized skincare routine.</p>

    <form class="auth-form" method="post" action="register.php">
      <div class="field">
        <label for="regName">Full name</label>
        <input id="regName" name="name" type="text" required autocomplete="name" value="<?php echo htmlspecialchars($name); ?>" />
      </div>
      <div class="field">
        <label for="regEmail">Email</label>
        <input id="regEmail" name="email" type="email" required autocomplete="email" value="<?php echo htmlspecialchars($email); ?>" />
      </div>
      <div class="field">
        <label for="regBirthdate">Birthdate</label>
        <input id="regBirthdate" name="birthdate" type="date" required autocomplete="bday" max="<?php echo $today; ?>" value="<?php echo htmlspecialchars($birthdate); ?>" />
      </div>
      <div class="field">
        <label for="regPassword">Password</label>
        <input id="regPassword" name="password" type="password" required autocomplete="new-password" />
      </div>
      <div class="field">
        <label for="regConfirm">Confirm password</label>
        <input id="regConfirm" name="confirm" type="password" required autocomplete="new-password" />
      </div>
      <?php if ($error): ?>
        <p class="auth-form__error" role="alert"><?php echo htmlspecialchars($error); ?></p>
      <?php endif; ?>
      <button class="btn btn--primary btn--block" type="submit">Register</button>
    </form>

    <p class="auth-switch">Already have an account? <a href="login.php">Log in</a></p>
  </div>
</main>

</body>
</html>
