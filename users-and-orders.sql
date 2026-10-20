-- Run this in phpMyAdmin after selecting the Bharat Bakery database.
-- This assumes your project uses MySQL/MariaDB and a PDO connection.

CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(190) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- After checking the existing orders table, add this column to associate
-- future orders with a logged-in customer. NULL keeps old guest orders valid.
ALTER TABLE orders
    ADD COLUMN user_id INT UNSIGNED NULL,
    ADD INDEX idx_orders_user_id (user_id);

-- IMPORTANT:
-- Existing orders will remain guest orders (user_id NULL) unless you manually
-- match them to a customer. Do not automatically assign old orders by email/phone.
