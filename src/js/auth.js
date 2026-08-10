'use strict';
/* Runs first on every page that needs a signed-in user.
   No session/cookies — just a logged-in flag saved in localStorage. */
const currentUser = JSON.parse(localStorage.getItem('kp_user') || 'null');

if (!currentUser) {
  location.href = 'login.html';
}

function logout() {
  localStorage.removeItem('kp_user');
  location.href = 'login.html';
}
