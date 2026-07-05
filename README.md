# Ecommerce (Simple PHP + MySQL)

A small, beginner-friendly PHP + MySQL ecommerce demo. This README explains how to set up and run the project step-by-step.
## Features
- Product listing
- User register and login
- Add to cart, update quantity, remove items
- Place an order (saved to `orders`/`order_items`)

## Prerequisites
- XAMPP (Apache + MySQL)
- A web browser (Chrome, Firefox, Edge)

## Step-by-step setup (for freshers)

1. Copy the project folder into XAMPP `htdocs`:

```powershell
# Example
Copy the entire `ecommerce` folder to:
C:\xampp1\htdocs\ecommerce
```

2. Start services:

- Register a new user (`pages/register.php`).
- Login and add a product to the cart.
- Go to `pages/cart.php` and place an order.

## Git (push your changes to GitHub)

If you want to upload your local changes to GitHub (you already have a remote `origin`):

```powershell
cd C:\xampp1\htdocs\ecommerce
git status
git add README.md
git commit -m "Improve README"
git push origin main
```

If this is your first push to a new remote branch, use `git push -u origin main` once to set the upstream.

## Troubleshooting (quick fixes)

- "Connection failed": start MySQL in XAMPP and check `database/db.php` values.
- Blank page or PHP errors: enable error display temporarily by adding to the top of a PHP file:

```php
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

- Images not visible: check `images/` filenames match the `image` values in your `products` table.
- 404 errors: ensure the `ecommerce` folder is inside `htdocs` and you are using the correct URL.

## Notes for sharing (resume / interview)

- Keep `database/db.php` default values but do NOT commit any real passwords.
- Add a short `database.sql` (optional) if you want recruiters to run the app quickly.

## Repository

https://github.com/kalyanitaddi/Ecommerce-website

---


# Ecommerce (Simple PHP + MySQL)

A minimal, beginner-friendly PHP + MySQL ecommerce demo.

## Prerequisites
- XAMPP (Apache + MySQL)
- A web browser

## Quick setup
1. Place this project in your XAMPP `htdocs` folder: `C:\xampp1\htdocs\ecommerce`
2. Start **Apache** and **MySQL** from the XAMPP control panel.
3. Create a MySQL database named `ecommerce` (or change the name in `database/db.php`).
4. Import a SQL dump if you have one (optional): use phpMyAdmin → Import → choose `database.sql`.
5. Open the app in your browser: `http://localhost/ecommerce/`.

## Database connection
Default connection settings are in `database/db.php`:
- host: `localhost`
- database: `ecommerce`
- user: `root`
- password: (empty)

## Notes
- GitHub repo: https://github.com/kalyanitaddi/Ecommerce-website



