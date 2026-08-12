'use strict';
document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('loginForm');
  const err = document.getElementById('loginError');

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    err.hidden = true;

    const email = document.getElementById('loginEmail').value.trim();
    const password = document.getElementById('loginPassword').value;

    const res = await fetch('../api/login.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email, password }),
    });
    const data = await res.json();
    if (!res.ok) { err.textContent = data.error; err.hidden = false; return; }

    location.href = 'index.php';
  });
});
