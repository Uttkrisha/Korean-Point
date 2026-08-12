'use strict';
document.addEventListener('DOMContentLoaded', () => {
  const birthdateInput = document.getElementById('regBirthdate');
  const today = new Date().toISOString().slice(0, 10);
  birthdateInput.max = today; // blocks picking a future date in the date picker

  const form = document.getElementById('registerForm');
  const err = document.getElementById('registerError');

  function showError(msg) { err.textContent = msg; err.hidden = false; }

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    err.hidden = true;

    const name = document.getElementById('regName').value.trim();
    const email = document.getElementById('regEmail').value.trim();
    const birthdate = birthdateInput.value;
    const password = document.getElementById('regPassword').value;
    const confirm = document.getElementById('regConfirm').value;

    if (!/^[A-Za-z ]{2,50}$/.test(name)) return showError('Name must be letters only (2-50 characters).');
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) return showError('Please enter a valid email address.');
    if (!birthdate || birthdate > today) return showError('Birthdate cannot be in the future.');
    if (password.length < 6) return showError('Password must be at least 6 characters.');
    if (password !== confirm) return showError('Passwords do not match.');

    const res = await fetch('../api/register.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ name, email, birthdate, password, confirm }),
    });
    const data = await res.json();
    if (!res.ok) return showError(data.error);

    location.href = 'index.php';
  });
});
