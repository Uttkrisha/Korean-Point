# Korean Point — Korean Skincare eCommerce

Multi-page Korean-beauty storefront demo with login/register gating, a
PHP backend and a MySQL database (`korean_point`). No AJAX/JSON API layer —
every page is server-rendered PHP, and every action (login, add to cart,
checkout) is a plain HTML `<form>` POST that redirects back to a page.

## Tech Stack

- **HTML5** rendered by PHP — one file per page (`src/pages/*.php`), plain markup, no templating
- **CSS3** — no framework, split into small files under `src/css/`, flat
  design (solid colors, simple borders, small radius, no glassmorphism/
  gradients/animations)
- **Vanilla JavaScript (ES6+)** — split into small files under `src/js/`, loaded as classic `<script>` tags sharing one global scope. Used only for in-page rendering (product grid, cart drawer, modals) from data PHP already embedded in the page — there is no `fetch()`/AJAX anywhere.
- **PHP 8** with **PDO + prepared statements** as the backend — see `config/database.php`, `src/includes/catalog_data.php`, and `src/actions/*.php`
- **MySQL** (`korean_point` database: `users`, `products`, `orders`, `order_items`) for persistent data
- `src/data/categories.json` remains static JSON — it's editorial catalog metadata, not part of the MySQL schema
- **Fonts:** Google Fonts — Poppins + Noto Sans KR



## Folder structure

```
config/
  database.php                shared PDO connection

src/
  includes/
    catalog_data.php          queries MySQL for products + builds the cart from $_SESSION, included by every gated page

  actions/
    cart.php                  add / remove / setQty — POST + redirect back
    checkout.php               reads session cart, writes orders + order_items in a transaction
    logout.php                   destroys the session

  data/
    categories.json           6 shop-by-need categories (static)
    brands.json                 brand list (static, currently unused by any page)

  pages/
    login.php                 self-submitting login form + gate
    register.php                self-submitting registration form
    index.php                     Home: hero, categories, before/after, reviews, IG, FAQ
    shop.php                        Shop: filter chips, search, sort, full product grid
    about.php                         Why Korean Skincare + the 4-step routine
    order-success.php                   shown after a successful checkout

  css/                        one small file per section: variables, base,
                                nav, hero, products, content, footer, overlays,
                                effects, responsive, auth

  js/
    data.js                   editorial content (why/routine/reviews/IG/FAQ copy)
    state.js                   localStorage theme helper, shared app state, $/$$/fmt/byId
    utils.js                      toast, lazy-load, star markup
    catalog.js                      categories, filter chips, product cards (renders from PRODUCTS/CATEGORIES)
    reviews.js                        reviews slider (prev/next + dots)
    cart.js                             renders the cart drawer from CART_ITEMS
    modals.js                            quick view, modal/drawer helpers, checkout modal open/close
    before-after.js                        before/after image slider
    nav-ui.js                                navbar, search, theme toggle, back-to-top, logout()
    main.js                                    event delegation + page bootstrap (loaded last)
```

Every gated page loads the exact same list of `<script>` tags; `main.js`
only runs the render/setup calls whose target element actually exists on
that page, so one shared bundle works across all pages without extra
per-page wiring.

## Validation & Security

- **Register**: name (letters only, 2-50 chars), email (regex), birthdate
  (HTML `max` attribute plus a server-side check that it isn't in the
  future), password (6+ chars), confirm password must match. The server
  also rejects duplicate emails and hashes passwords with `password_hash()`
  — nothing is ever stored in plain text.
- **Login**: `login.php` checks the email against MySQL and verifies the
  password with `password_verify()`, then starts a PHP session.
- **Cart & checkout**: the cart lives in `$_SESSION['cart']` (product id →
  quantity), never in the browser. Every price shown or charged is read
  fresh from the `products` table at request time, so a tampered client
  request can't change what gets charged. Checkout runs inside a MySQL
  transaction — if any insert fails, the whole order is rolled back.
- **Redirect targets**: the hidden `redirect` field on cart/checkout forms
  is validated against a strict pattern (must be a relative `.php` path
  within the app) before being used in a `Location` header, so it can't be
  turned into an open redirect to an external site.
- All SQL goes through PDO prepared statements — no string-built queries.

## Features

Login/register gate on every page · product catalog with category filter,
search, sort · shopping cart drawer with quantity controls and totals ·
quick-view modal · checkout that saves a real order to MySQL · a dedicated
order-confirmation page with the real order ID · reviews slider ·
before/after slider · FAQ accordion · Instagram-style gallery · dark mode
toggle (light by default) · back-to-top · toast notifications · lazy-loaded
images.

## How to start

Start apache and mysql in Xampp

http://localhost/korean-point/src/pages/login.php