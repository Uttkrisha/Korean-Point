# Korean Point — Korean Skincare eCommerce

Multi-page Korean-beauty storefront demo with login/register gating and a
tiny JSON-file backend. No frameworks, no database, no fancy CSS tricks —
plain HTML/CSS/JS simple enough to explain line by line.

## Tech Stack

- **HTML5** — one file per page, plain markup, no templating
- **CSS3** — no framework, split into small files under `src/css/`, flat
  design (solid colors, simple borders, small radius, no glassmorphism/
  gradients/animations)
- **Vanilla JavaScript (ES6+)** — split into small files under `src/js/`, loaded as classic `<script>` tags sharing one global scope
- **Node.js core only** (`http`, `fs`, `path`) as the backend — no Express, no npm dependencies
- **JSON files instead of a database** — `src/data/*.json`
- **Fonts:** Google Fonts — Poppins + Noto Sans KR

## Run

```
node server.js
```
then open `http://localhost:3000`. It redirects to the login page until you
register/log in.

## Why a server at all?

The site needs to *save* users and orders to disk, and a browser can't write
files on its own — so `server.js` is a small Node http server that:
- serves every page/css/js/json file as static files, and
- handles three endpoints that write to `src/data/*.json`:
  - `POST /api/register` — validates + creates a user
  - `POST /api/login` — checks email/password
  - `POST /api/orders` — saves a placed order

No auth tokens or sessions — after login the browser just remembers
`{ id, name, email }` in `localStorage`, and every page checks for that
before showing anything (see `src/js/auth.js`). Passwords are stored as
plain text in `users.json`. This is a demo-level auth flow for a class
project, not production security.

## Folder structure

```
server.js                 tiny static file server + register/login/order API
package.json               "npm start" -> node server.js

src/
  data/
    products.json          product catalog
    categories.json         6 shop-by-need categories
    brands.json              brand list
    users.json                registered users
    orders.json                every placed order

  pages/
    login.html              gate — shown first if not logged in
    register.html            name / email / birthdate / password
    index.html                 Home: hero, categories, before/after, reviews, IG, FAQ
    shop.html                   Shop: filter chips, search, sort, full product grid
    about.html                    Why Korean Skincare + the 4-step routine

  css/                       one small file per section: variables, base,
                               nav, hero, products, content, footer, overlays,
                               effects, responsive, auth

  js/
    data.js                  editorial content (why/routine/reviews/IG/FAQ copy)
    load-data.js               fetches products.json + categories.json at startup
    state.js                    localStorage helpers, shared app state, $/$$/fmt/byId
    utils.js                     toast, lazy-load, star markup
    catalog.js                    categories, filter chips, product cards, why/timeline/FAQ/IG
    reviews.js                     reviews slider (prev/next + dots)
    cart.js                         cart state and rendering
    modals.js                        quick view, modal/drawer helpers, checkout (POSTs to /api/orders)
    before-after.js                   before/after image slider
    nav-ui.js                          navbar, search, theme toggle, back-to-top
    auth.js                             redirects to login.html if not signed in; logout()
    login.js / register.js               form handling + validation for those two pages
    main.js                               event delegation + page bootstrap (loaded last)
```

Every gated page loads the exact same list of `<script>` tags; `main.js`
only runs the render/setup calls whose target element actually exists on
that page, so one shared bundle works across all pages without extra
per-page wiring.

## Validation

- **Register**: name (letters only, 2-50 chars), email (regex), birthdate
  (HTML `max` attribute is set to today at page load, and the server
  double-checks it isn't in the future), password (6+ chars), confirm
  password must match. The server also rejects duplicate emails.
- **Login**: checked against `users.json`.

## Features

Login/register gate on every page · product catalog with category filter,
search, sort · shopping cart drawer with quantity controls and totals ·
quick-view modal · checkout that saves a real order to `orders.json` ·
order-success modal with a real order ID · reviews slider · before/after
slider · FAQ accordion · Instagram-style gallery · dark mode toggle (light
by default) · back-to-top · toast notifications · lazy-loaded images.

## What was simplified

No wishlist, no Best Sellers/New Arrivals pages, and no decorative
animation layer (glassmorphism, blurred background blobs, cursor-follow
glow, ripple click effect, animated gradients, parallax, scroll-reveal,
auto-rotating carousels). The goal: every CSS rule and JS function should
be something a first-year CS student could point to and explain in one
sentence.
