'use strict';
function setupBeforeAfter() {
  const range = $('#baRange');
  const after = $('#baAfter');
  const handle = $('#baHandle');
  function paint(v) { after.style.width = v + '%'; handle.style.left = v + '%'; }
  range.addEventListener('input', () => paint(range.value));
  paint(range.value);
}
