I am developing a Korean skincare e-commerce website called **Korean Point** using **XAMPP, PHP, MySQL, and phpMyAdmin** on Windows 11.

The MySQL database has **already been created and configured in phpMyAdmin**.

The database name is:

`korean_point`

The following tables have already been created:

* `users`
* `products`
* `orders`
* `order_items`

The database schema is already set up correctly, including the primary keys, foreign keys, and relationships.

**Do NOT recreate or modify the database schema unless a specific problem requires it.**

---

## Main Goal

I currently have a **Node.js + JSON-based backend**.

I want to completely migrate the application to:

**PHP + MySQL**

The final architecture should be:

**Frontend → PHP → MySQL**

instead of:

**Frontend → Node.js → JSON files**

I want to remove the old Node.js and JSON data-storage system completely.

---

## 1. Remove Node.js and JSON Storage

Find and replace all parts of the existing application that currently use:

* Node.js
* Express or other Node.js backend code
* JSON files as data storage
* `fetch()` calls to Node.js API endpoints
* Node.js routes
* JSON read/write operations
* Any Node.js-specific database/storage logic

Do not keep the old Node.js backend running alongside PHP.

The final application should use PHP for all backend operations and MySQL for persistent data.

However, **do not unnecessarily change my existing HTML, CSS, JavaScript, UI, or design**.

Only change the parts necessary to connect the application to PHP/MySQL.

---

## 2. Existing MySQL Database

The database is already available:

`korean_point`

The existing tables are:

```text
users
products
orders
order_items
```

Use these tables rather than creating new ones.

The relationships are:

```text
users
  │
  └── orders
        │
        └── order_items
              │
              └── products
```

Use the existing primary keys and foreign keys.

---

## 3. PHP Database Connection

Create one reusable PHP database connection file, preferably:

```text
config/database.php
```

Use **PDO with prepared statements**.

Do not duplicate database connection code across multiple files.

The connection should connect to:

```text
Database: korean_point
Host: localhost
Username: root
Password: [my XAMPP MySQL password]
```

If XAMPP is using the default MySQL configuration, explain where I should place the correct credentials.

---

## 4. Registration

The existing registration page contains:

* Name
* Email
* Date of birth
* Password
* Confirm password

Convert the registration functionality from the current Node.js/JSON implementation to PHP/MySQL.

When a user registers:

1. Validate all required fields.
2. Validate the email format.
3. Confirm that the passwords match.
4. Check whether the email already exists in the `users` table.
5. Hash the password using PHP's `password_hash()`.
6. Insert the user into the `users` table.
7. Do not store plain-text passwords.
8. Display appropriate success/error messages.
9. Redirect appropriately after successful registration.

---

## 5. Login

Convert the existing login system to PHP/MySQL.

When a user logs in:

1. Find the user using their email.
2. Retrieve the hashed password from MySQL.
3. Verify the password using `password_verify()`.
4. Create a PHP session after successful authentication.
5. Store the user's ID in the session.
6. Handle incorrect email/password appropriately.
7. Prevent access to authenticated pages when the user is not logged in.
8. Implement logout using PHP sessions.

Do not store login credentials in JSON files.

---

## 6. Products

The existing application currently gets product information from JSON.

Replace this with MySQL queries against the existing:

```text
products
```

table.

Products should be retrieved dynamically from MySQL.

The existing product functionality should continue to work, including:

* Product ID
* Product name
* Brand
* Category
* Price
* Old price
* Rating
* Reviews
* Badges
* Image
* Description
* Product date

Do not change the existing product UI unless necessary.

If the product JSON data has already been imported into MySQL, use the MySQL data directly.

---

## 7. Shopping Cart

The existing website already has a shopping cart.

Keep the current cart functionality and UI.

Replace any Node.js/JSON logic with PHP.

For this student project, use **PHP sessions for the cart** unless there is already a better implementation in the existing code.

The cart should support:

* Add product
* Remove product
* Increase quantity
* Decrease quantity
* Display product information
* Calculate subtotal
* Calculate total
* Proceed to checkout

When displaying cart products, retrieve the actual product information from the MySQL `products` table using the product IDs stored in the cart.

Do not trust prices sent directly from the browser. Retrieve the current price from MySQL when calculating totals.

---

## 8. Checkout

Convert the existing checkout process to PHP/MySQL.

When a logged-in user places an order:

### Step 1 — Create the order

Insert a record into:

```text
orders
```

using the logged-in user's ID.

Store:

* Order ID
* User ID
* Customer name
* Email
* Address
* City
* ZIP
* Total

### Step 2 — Create order items

For every product in the cart, insert a record into:

```text
order_items
```

Store:

* Order ID
* Product ID
* Quantity
* Product price at the time of purchase

### Step 3 — Complete the order

After the order and all order items are successfully inserted:

* Commit the transaction.
* Clear the user's cart.
* Show the appropriate order-success/confirmation page.

Use a **MySQL transaction** so that if any part of the checkout fails, the entire order is rolled back.

---

## 9. Security

Use basic secure PHP practices throughout the migration.

Requirements:

* PDO prepared statements
* Password hashing with `password_hash()`
* Password verification with `password_verify()`
* PHP sessions for authentication
* Server-side validation
* Do not trust prices or totals submitted by JavaScript
* Do not directly insert unsanitized user input into SQL
* Prevent SQL injection
* Check authentication before accessing protected pages

Keep the implementation understandable for a student project rather than introducing an unnecessarily complicated framework.

---

## 10. Existing Project Structure

Do not force a completely new architecture onto the project.

First inspect my existing files and determine:

* Which files currently belong to Node.js
* Which files read/write JSON
* Which JavaScript files call Node.js APIs
* Which pages need PHP processing
* Which files can remain unchanged
* Which files need to be converted or modified

Then migrate the existing project gradually.

For example, a possible PHP structure could be:

```text
korean-point/
│
├── config/
│   └── database.php
│
├── auth/
│   ├── login.php
│   ├── register.php
│   └── logout.php
│
├── cart/
│   └── ...
│
├── checkout/
│   └── ...
│
├── index.php
├── login.php
├── register.php
├── cart.php
├── checkout.php
│
├── css/
├── js/
└── images/
```

But **adapt this to my existing project** instead of unnecessarily restructuring everything.

---

## 11. Important Migration Rules

Please follow these rules:

1. **Do not recreate my MySQL database.**
2. **Do not recreate my tables.**
3. Assume the `korean_point` database and tables already exist.
4. Do not delete existing MySQL data.
5. Do not rewrite the entire frontend.
6. Preserve the existing UI and CSS.
7. Remove Node.js backend functionality.
8. Remove JSON-based persistent storage.
9. Replace JSON operations with PHP/MySQL operations.
10. Keep the existing functionality working.
11. Use PHP sessions where appropriate.
12. Use PDO and prepared statements.
13. Explain every important change.
14. If an existing file needs modification, show me exactly what needs to change.
15. Do not make assumptions about files you have not seen.

---

## 12. How I Want You to Help Me

I will provide my existing project files/code to you.

After inspecting them:

1. Identify the current Node.js/JSON architecture.
2. Identify which files need to be changed.
3. Identify which Node.js files can be deleted.
4. Identify which JSON files can be removed.
5. Create the PHP database connection.
6. Convert registration to PHP/MySQL.
7. Convert login/logout to PHP/MySQL.
8. Convert product retrieval to MySQL.
9. Convert the cart functionality to work with PHP/MySQL.
10. Convert checkout/order creation to MySQL.
11. Make sure `orders` and `order_items` are correctly related.
12. Keep the existing frontend design.
13. Explain where each new PHP file should be placed.
14. Explain how to run and test everything using XAMPP.

**Do not generate replacement code based on guesses. First inspect the files I provide and then give me the exact changes required for my project.**
