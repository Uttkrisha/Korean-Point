# Korean Point — Korean Skincare eCommerce

A simple, beginner-friendly PHP + MySQL storefront. Every page is
server-rendered PHP — products, categories and the cart are looped and
echoed directly with `while`/`foreach`, the same way you'd write it in
an intro web dev class. There's no JSON-into-JavaScript data layer and
no build step.

## Tech Stack

- **PHP 8** with **PDO + prepared statements** — see `config/database.php`
- **MySQL** (`skincare_store` database: `users`, `products`, `cart`, `orders`, `order_items` — see `database.sql`)
- **HTML** rendered directly by each page in `src/pages/*.php`
- **CSS** — one flat stylesheet, `src/css/style.css`
- **JavaScript** — one small file, `src/js/script.js`, only for the mobile
  nav toggle and auto-dismissing alerts. Everything else (add to cart,
  update quantity, remove, checkout, login, register) is a plain HTML
  `<form>` POST that reloads the page — no `fetch()`/AJAX anywhere.

## Database setup

Import `database.sql` (phpMyAdmin → Import, or `mysql -u root < database.sql`).
It creates the `skincare_store` database, all five tables, six sample
products, and one admin account (`admin` / `admin123` — login with
username or email).

## Folder structure

```
database.sql                  schema + sample data

config/
  database.php               PDO connection + starts the session

src/
  includes/
    functions.php            isLoggedIn(), isAdmin(), formatPrice(), getProduct(), cart helpers
    header.php                shared <head> + nav, included by every page
    footer.php                shared footer + closing tags

  actions/
    cart.php                  add / remove / setQty on the `cart` table — POST + redirect back
    checkout.php               reads the cart table, writes orders + order_items in a transaction
    logout.php                   destroys the session

  admin/                      gated by requireAdmin() — role must be 'admin'
    _nav.php                  shared Dashboard/Products/Orders/Reports sub-nav
    index.php                 dashboard: totals, recent orders, low-stock list
    products.php               product list, search, delete
    add_product.php             add a product
    edit_product.php            edit a product
    delete_product.php           POST-only delete endpoint
    orders.php                  all orders, inline status update, view items
    reports.php                  revenue, orders by status, top sellers, low stock

  pages/
    login.php                 login form (username or email) + gate
    register.php               registration form
    index.php                      home: hero, categories, featured products, FAQ
    shop.php                        product listing with category/skin type/search filter
    product_details.php               single product page with stock + skin type
    cart.php                            cart table + checkout form
    about.php                            why Korean skincare + the routine
    order-success.php                     shown after a successful checkout

  css/
    style.css                one stylesheet for the whole site

  js/
    script.js                mobile nav toggle + alert auto-dismiss
```

## Validation & Security

- **Register**: full name (letters only, 2-50 chars), username (3-20
  chars, letters/numbers/underscore), email (regex), password (6+
  chars), confirm password must match. Duplicate usernames/emails are
  rejected and passwords are hashed with `password_hash()` — nothing is
  ever stored in plain text.
- **Login**: accepts username or email, checks it against MySQL and
  verifies the password with `password_verify()`, then starts a PHP
  session.
- **Cart & checkout**: the cart lives in the `cart` table (user_id,
  product_id, quantity), never in the browser. Every price shown or
  charged is read fresh from the `products` table at request time.
  Checkout runs inside a MySQL transaction — order + order_items are
  written and the cart is cleared together, or nothing is (rolled back
  on failure).
- **Redirect targets**: the hidden `redirect` field on cart/checkout forms
  is validated against a strict pattern before being used in a `Location`
  header, so it can't be turned into an open redirect.
- All SQL goes through PDO prepared statements — no string-built queries.

## Features

Login/register gate on every page · product catalog with category,
skin type and search filters · a real product detail page showing
stock and skin type · a cart page with quantity controls and totals ·
checkout that saves a real order to MySQL · a dedicated
order-confirmation page with the real order ID · FAQ accordion
(native `<details>`, no JS).

**Admin** (log in as `admin`/`admin123`, or any user with `role = 'admin'`
in the `users` table — the "Admin" link then appears in the nav):
- Dashboard with product/order/user/revenue totals, recent orders, low stock
- Add, edit, delete products (category and skin type are plain text fields
  with a `<datalist>` of existing values — there's no separate categories
  table in the schema, so a category is just whatever string a product uses)
- View all orders and update their status (pending → processing → shipped → delivered)
- Reports: total revenue, order counts by status, top-selling products, low stock

## How to start

Start Apache and MySQL in XAMPP, import `database.sql` if you haven't
already, then visit:

http://localhost/korean-point/src/pages/login.php
