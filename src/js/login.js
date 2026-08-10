'use strict';
document.addEventListener('DOMContentLoaded', () => {
  const already = JSON.parse(localStorage.getItem('kp_user') || 'null');
  if (already) { location.href = 'index.html'; return; }

  const form = document.getElementById('loginForm');
  const err = document.getElementById('loginError');

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    err.hidden = true;

    const email = document.getElementById('loginEmail').value.trim();
    const password = document.getElementById('loginPassword').value;

    const res = await fetch('/api/login', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email, password }),
    });
    const data = await res.json();
    if (!res.ok) { err.textContent = data.error; err.hidden = false; return; }

    localStorage.setItem('kp_user', JSON.stringify(data));
    location.href = 'index.html';
  });
});
