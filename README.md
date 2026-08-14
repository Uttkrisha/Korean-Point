# Korean Point — Korean Skincare eCommerce

A simple, beginner-friendly PHP + MySQL storefront. Every page is
server-rendered PHP — products, categories and the cart are looped and
echoed directly with `while`/`foreach`, the same way you'd write it in
an intro web dev class. There's no JSON-into-JavaScript data layer and
no build step.

## Tech Stack

- **PHP 8** with **PDO + prepared statements** — see `config/database.php`
- **MySQL** (`korean_point` database: `users`, `products`, `orders`, `order_items`)
- **HTML** rendered directly by each page in `src/pages/*.php`
- **CSS** — one flat stylesheet, `src/css/style.css`
- **JavaScript** — one small file, `src/js/script.js`, only for the mobile
  nav toggle and auto-dismissing alerts. Everything else (add to cart,
  update quantity, remove, checkout, login, register) is a plain HTML
  `<form>` POST that reloads the page — no `fetch()`/AJAX anywhere.

## Folder structure

```
config/
  database.php               PDO connection + starts the session

src/
  includes/
    functions.php            isLoggedIn(), formatPrice(), getProduct(), cart helpers
    header.php                shared <head> + nav, included by every page
    footer.php                shared footer + closing tags

  actions/
    cart.php                  add / remove / setQty — POST + redirect back
    checkout.php               reads session cart, writes orders + order_items in a transaction
    logout.php                   destroys the session

  data/
    categories.json           category name + icon (static editorial data)

  pages/
    login.php                 login form + gate
    register.php               registration form
    index.php                      home: hero, categories, featured products, FAQ
    shop.php                        product listing with category/search filter
    product_details.php               single product page
    cart.php                            cart table + checkout form
    about.php                            why Korean skincare + the routine
    order-success.php                     shown after a successful checkout

  css/
    style.css                one stylesheet for the whole site

  js/
    script.js                mobile nav toggle + alert auto-dismiss
```

## Validation & Security

- **Register**: name (letters only, 2-50 chars), email (regex), birthdate
  (not in the future), password (6+ chars), confirm password must match.
  Duplicate emails are rejected and passwords are hashed with
  `password_hash()` — nothing is ever stored in plain text.
- **Login**: checks the email against MySQL and verifies the password
  with `password_verify()`, then starts a PHP session.
- **Cart & checkout**: the cart lives in `$_SESSION['cart']` (product id →
  quantity), never in the browser. Every price shown or charged is read
  fresh from the `products` table at request time. Checkout runs inside a
  MySQL transaction — if any insert fails, the whole order is rolled back.
- **Redirect targets**: the hidden `redirect` field on cart/checkout forms
  is validated against a strict pattern before being used in a `Location`
  header, so it can't be turned into an open redirect.
- All SQL goes through PDO prepared statements — no string-built queries.

## Features

Login/register gate on every page · product catalog with category filter
and search · a real product detail page · a cart page with quantity
controls and totals · checkout that saves a real order to MySQL · a
dedicated order-confirmation page with the real order ID · FAQ accordion
(native `<details>`, no JS).

## How to start

Start Apache and MySQL in XAMPP, then visit:

http://localhost/korean-point/src/pages/login.php
