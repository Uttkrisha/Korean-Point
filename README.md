# Korean Point — Korean Skincare eCommerce

Multi-page Korean-beauty storefront demo with login/register gating, a
PHP backend and a MySQL database (`korean_point`).

## Tech Stack

- **HTML5** rendered by PHP — one file per page (`src/pages/*.php`), plain markup, no templating
- **CSS3** — no framework, split into small files under `src/css/`, flat
  design (solid colors, simple borders, small radius, no glassmorphism/
  gradients/animations)
- **Vanilla JavaScript (ES6+)** — split into small files under `src/js/`, loaded as classic `<script>` tags sharing one global scope
- **PHP 8** with **PDO + prepared statements** as the backend — see `config/database.php` and `src/api/*.php`
- **MySQL** (`korean_point` database: `users`, `products`, `orders`, `order_items`) for persistent data
- `src/data/categories.json` / `brands.json` remain static JSON — they're editorial catalog metadata, not part of the MySQL schema
- **Fonts:** Google Fonts — Poppins + Noto Sans KR

## Run (XAMPP)

1. Start **Apache** and **MySQL** from the XAMPP Control Panel.
2. Make sure the `korean_point` database exists with the `users`, `products`,
   `orders`, `order_items` tables (already set up in phpMyAdmin).
3. Serve this folder through Apache — either place the project under
   `C:\xampp\htdocs\korean-point`, or create a junction so it's reachable
   there without moving anything:
   ```
   mklink /J C:\xampp\htdocs\korean-point "<path to this project>"
   ```
4. Open `http://localhost/korean-point/src/pages/login.php`.
5. If your MySQL root user has a password, set it in `config/database.php`.

## Why PHP + MySQL

The site needs to *persist* users, products, and orders. `config/database.php`
holds one shared PDO connection, and `src/api/*.php` are small JSON endpoints
the frontend JS talks to via `fetch()`:
- `POST src/api/register.php` — validates + creates a user, hashes the password with `password_hash()`
- `POST src/api/login.php` — verifies with `password_verify()`, starts a PHP session
- `GET  src/api/logout.php` — destroys the session
- `GET  src/api/products.php` — reads the product catalog from MySQL
- `GET/POST src/api/cart.php` — the shopping cart, stored server-side in `$_SESSION['cart']`
- `POST src/api/orders.php` — checkout: looks up cart contents + prices from MySQL (never trusts the browser) and writes `orders` + `order_items` inside one transaction

`src/pages/index.php`, `shop.php`, and `about.php` each start with a PHP
session check that redirects to `login.php` when nobody is signed in — no
client-side auth flash. `login.php` / `register.php` redirect to `index.php`
when a session already exists.

## Folder structure

```
config/
  database.php               shared PDO connection

src/
  api/
    register.php / login.php / logout.php    auth endpoints
    products.php                                reads MySQL products table
    cart.php                                      session-based cart
    orders.php                                     checkout transaction

  data/
    categories.json          6 shop-by-need categories (static)
    brands.json                brand list (static)

  pages/
    login.php                gate — shown first if not logged in
    register.php               name / email / birthdate / password
    index.php                    Home: hero, categories, before/after, reviews, IG, FAQ
    shop.php                       Shop: filter chips, search, sort, full product grid
    about.php                        Why Korean Skincare + the 4-step routine

  css/                       one small file per section: variables, base,
                               nav, hero, products, content, footer, overlays,
                               effects, responsive, auth

  js/
    data.js                  editorial content (why/routine/reviews/IG/FAQ copy)
    load-data.js               fetches products from api/products.php + categories.json at startup
    state.js                    localStorage theme helper, shared app state, $/$$/fmt/byId
    utils.js                     toast, lazy-load, star markup
    catalog.js                    categories, filter chips, product cards, why/timeline/FAQ/IG
    reviews.js                     reviews slider (prev/next + dots)
    cart.js                         cart rendering, backed by api/cart.php (PHP session)
    modals.js                        quick view, modal/drawer helpers, checkout (POSTs to api/orders.php)
    before-after.js                   before/after image slider
    nav-ui.js                          navbar, search, theme toggle, back-to-top, logout()
    login.js / register.js               form handling + validation for those two pages
    main.js                               event delegation + page bootstrap (loaded last)
```

Every gated page loads the exact same list of `<script>` tags; `main.js`
only runs the render/setup calls whose target element actually exists on
that page, so one shared bundle works across all pages without extra
per-page wiring.

## Validation & Security

- **Register**: name (letters only, 2-50 chars), email (regex), birthdate
  (HTML `max` attribute is set to today at page load, and `api/register.php`
  double-checks it isn't in the future), password (6+ chars), confirm
  password must match. The server also rejects duplicate emails and hashes
  passwords with `password_hash()` — nothing is ever stored in plain text.
- **Login**: `api/login.php` checks the email against MySQL and verifies the
  password with `password_verify()`, then starts a PHP session.
- **Cart & checkout**: the cart lives in `$_SESSION['cart']` (product id →
  quantity), never in the browser. Every price shown or charged is read
  fresh from the `products` table at request time, so a tampered client
  request can't change what gets charged. Checkout runs inside a MySQL
  transaction — if any insert fails, the whole order is rolled back.
- All SQL goes through PDO prepared statements — no string-built queries.

## Features

Login/register gate on every page · product catalog with category filter,
search, sort · shopping cart drawer with quantity controls and totals ·
quick-view modal · checkout that saves a real order to MySQL · order-success
modal with a real order ID · reviews slider · before/after slider · FAQ
accordion · Instagram-style gallery · dark mode toggle (light by default) ·
back-to-top · toast notifications · lazy-loaded images.

## What was simplified

No wishlist, no Best Sellers/New Arrivals pages, and no decorative
animation layer (glassmorphism, blurred background blobs, cursor-follow
glow, ripple click effect, animated gradients, parallax, scroll-reveal,
auto-rotating carousels). The goal: every CSS rule and JS function should
be something a first-year CS student could point to and explain in one
sentence.
