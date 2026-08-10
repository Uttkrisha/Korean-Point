'use strict';
/* Persistence + shared app state + tiny DOM/format helpers */
const LS = {
  get(key, fallback) { try { const v = JSON.parse(localStorage.getItem(key)); return v ?? fallback; } catch { return fallback; } },
  set(key, val) { try { localStorage.setItem(key, JSON.stringify(val)); } catch {} },
};

const state = {
  cart: LS.get('kp_cart', []),  // [{id, qty}]
  theme: LS.get('kp_theme', null),
  category: 'All',
  search: '',
  sort: 'featured',
  reviewIndex: 0,
};

const byId = (id) => PRODUCTS.find((p) => p.id === id);
const fmt = (n) => '$' + n.toFixed(2);
const $ = (sel, ctx = document) => ctx.querySelector(sel);
const $$ = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));
