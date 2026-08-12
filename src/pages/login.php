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
    <a class="logo" href="index.html">
      <span class="logo__leaf" aria-hidden="true">🌿</span>
      <span class="logo__text">Korean Point</span>
    </a>
    <h1>Welcome back</h1>
    <p class="section__sub">Log in to shop your Korean skincare routine.</p>

    <form class="auth-form" id="loginForm" novalidate>
      <div class="field">
        <label for="loginEmail">Email</label>
        <input id="loginEmail" type="email" required autocomplete="email" />
      </div>
      <div class="field">
        <label for="loginPassword">Password</label>
        <input id="loginPassword" type="password" required autocomplete="current-password" />
      </div>
      <p class="auth-form__error" id="loginError" role="alert" hidden></p>
      <button class="btn btn--primary btn--block" type="submit">Log In</button>
    </form>

    <p class="auth-switch">Don't have an account? <a href="register.html">Register</a></p>
  </div>
</main>

<script src="../js/login.js"></script>
</body>
</html>
