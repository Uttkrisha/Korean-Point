'use strict';
/* Before/after image slider: a range input controls how much of the "after"
   image is revealed, by setting its width as a percentage. */

function setupBeforeAfter() {
  const range = $('#baRange');
  const after = $('#baAfter');
  const handle = $('#baHandle');
  function paint(v) { after.style.width = v + '%'; handle.style.left = v + '%'; }
  range.addEventListener('input', () => paint(range.value));
  paint(range.value);
}
