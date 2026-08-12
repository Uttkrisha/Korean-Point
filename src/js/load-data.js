'use strict';
/* Loads products from MySQL (via api/products.php) and categories from
   the static categories.json. Other scripts await `dataLoaded` before
   rendering anything. */
const dataLoaded = (async function loadData() {
  PRODUCTS = await (await fetch('../api/products.php')).json();
  CATEGORIES = await (await fetch('../data/categories.json')).json();
})();
