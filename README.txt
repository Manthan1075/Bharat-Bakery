Bharat Bakery authentication starter
====================================

Files:
- signup.php
- login.php
- logout.php
- my-orders.php (authenticated customer order history)
- checkout.php (updated to associate logged-in customer with new orders, if generated)
- users-and-orders.sql

Install:
1. Back up your database first.
2. In phpMyAdmin, select the Bharat Bakery database and run the CREATE TABLE users query.
3. Run the ALTER TABLE orders query only once, after checking orders.user_id does not already exist.
4. Copy the PHP files into /opt/lampp/htdocs/bakery/.
5. Confirm includes/functions.php starts the PHP session and db() returns a PDO connection.
6. Open http://localhost/bakery/signup.php.

Important:
- The updated checkout.php inserts the logged-in user's ID in orders.user_id; guest orders remain possible with user_id NULL.
- my-orders.php requires login and shows only rows whose user_id matches the logged-in account.
- Existing orders remain unlinked (user_id NULL) unless you safely identify and associate them manually. Do not match accounts using phone/email without verifying ownership.
- Add links to login.php and signup.php in includes/header.php. For logout, use a POST form with the session CSRF token:
  <form method="post" action="logout.php">
    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
    <button type="submit">Logout</button>
  </form>
- If you already have an orders.user_id column, do not run the ALTER TABLE line again.
- The page currently displays a generic "Order received" label. Add a status column to orders and update checkout/admin code before showing real status values.

Security:
- Passwords use password_hash() and password_verify().
- Session IDs regenerate after login/signup.
- Forms use a CSRF token.
- Test locally before exposing the site publicly.
