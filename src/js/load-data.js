'use strict';
/* Loads products + categories from the JSON files in src/data.
   Other scripts await `dataLoaded` before rendering anything. */
const dataLoaded = (async function loadData() {
  PRODUCTS = await (await fetch('../data/products.json')).json();
  CATEGORIES = await (await fetch('../data/categories.json')).json();
})();
