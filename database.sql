-- Bharat Bakery database
CREATE DATABASE IF NOT EXISTS bharat_bakery CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bharat_bakery;

DROP TABLE IF EXISTS order_items, orders, contact_messages, products, categories, settings;

CREATE TABLE categories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  icon VARCHAR(10) NOT NULL DEFAULT '🍰',
  description VARCHAR(255) NULL,
  sort_order INT NOT NULL DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE products (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_id INT UNSIGNED NOT NULL,
  name VARCHAR(150) NOT NULL,
  description TEXT NULL,
  price DECIMAL(10,2) NOT NULL,
  old_price DECIMAL(10,2) NULL,
  image VARCHAR(255) NOT NULL DEFAULT 'cake.svg',
  is_featured TINYINT(1) NOT NULL DEFAULT 0,
  is_offer TINYINT(1) NOT NULL DEFAULT 0,
  is_available TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE orders (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_number VARCHAR(30) NOT NULL UNIQUE,
  customer_name VARCHAR(120) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  address TEXT NOT NULL,
  notes TEXT NULL,
  total DECIMAL(10,2) NOT NULL,
  status VARCHAR(20) NOT NULL DEFAULT 'Pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE order_items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id INT UNSIGNED NOT NULL,
  product_id INT UNSIGNED NULL,
  product_name VARCHAR(150) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  quantity INT UNSIGNED NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE contact_messages (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(150) NULL,
  phone VARCHAR(20) NULL,
  message TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE settings (
  setting_key VARCHAR(60) PRIMARY KEY,
  setting_value TEXT NULL
) ENGINE=InnoDB;



INSERT IGNORE INTO settings (setting_key, setting_value) VALUES
('logo_file', 'logo.jpg'),
('hero_title', 'Freshly Baked with Love, Every Day!'),
('hero_text', 'Birthday cakes, soft bread, flaky pastries and hot snacks, baked in small batches every morning.'),
('footer_text', 'Freshly Baked with Love, Every Day! Cakes, pastries, bread and snacks made with quality ingredients.'),
('about_story', 'Bharat Bakery began with a simple idea: bake the way you would for your own family. Every loaf, cake and puff is made in small batches with quality flour, real butter and fresh ingredients.\n\nFrom birthday cakes for your little ones to soft pav for your evening bhaji, we bake for the everyday moments and the big celebrations of our neighbourhood.\n\nOur ovens start before sunrise, so your order is always fresh on the day you receive it.');

INSERT INTO categories (id, name, icon, description, sort_order) VALUES
(1, 'Birthday Cakes', '<?xml version="1.0" encoding="utf-8"?><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 110.08 122.88" style="enable-background:new 0 0 110.08 122.88" xml:space="preserve"><style type="text/css">.st0{fill-rule:evenodd;clip-rule:evenodd;}</style><g><path class="st0" d="M63.85,18.63h3.9v6.55h16.4c3.7,0,6.72,3.02,6.72,6.72V37c-2.55,2.11-4.91,3.25-7.05,3.3 c-2.68,0.07-5.24-1.66-7.65-5.39l0,0c-0.13-0.2-0.3-0.38-0.5-0.52c-0.89-0.65-2.13-0.45-2.78,0.44c-2.44,3.35-4.95,5.21-7.53,5.35 c-2.61,0.14-5.53-1.46-8.79-5.03c-0.04-0.05-0.09-0.09-0.13-0.13c-0.82-0.74-2.08-0.68-2.82,0.13c-3.06,3.36-5.92,5.1-8.58,5.13 c-2.6,0.03-5.22-1.61-7.86-5.01l0,0c-0.12-0.15-0.26-0.29-0.43-0.41c-0.9-0.63-2.15-0.42-2.78,0.48c-1.95,2.76-4.22,4.4-6.66,4.84 c-1.67,0.3-3.47,0.02-5.39-0.77c-3.96-1.63-2.53-2.56-2.79-7.5c-0.2-3.69,3.03-6.73,6.73-6.73h16.35v-6.55h3.9v6.55h6.93V10.92h3.9 v14.26h6.93V18.63L63.85,18.63z M0,114.88h110.08v8H0V114.88L0,114.88z M90.87,41.92v0.22h-0.35C90.63,42.07,90.75,42,90.87,41.92 L90.87,41.92z M89.31,42.81v20.52H20.77V43.27c2.48,0.99,4.91,1.26,7.23,0.84c2.82-0.51,5.4-2.02,7.64-4.48 c2.98,3.15,6.12,4.67,9.42,4.64c3.35-0.04,6.69-1.7,10.03-4.91c3.6,3.46,7.09,4.99,10.47,4.81c3.19-0.17,6.13-1.86,8.85-4.89 c2.85,3.49,6.03,5.1,9.51,5.01C85.68,44.24,87.48,43.74,89.31,42.81L89.31,42.81z M15.2,66.95h79.76c5.06,0,9.19,4.14,9.19,9.19 v6.98c-3.49,2.89-6.71,4.44-9.65,4.52c-3.66,0.09-7.16-2.26-10.47-7.37l0,0c-0.18-0.27-0.4-0.52-0.68-0.72 c-1.22-0.89-2.92-0.62-3.8,0.6c-3.34,4.58-6.77,7.13-10.3,7.32c-3.57,0.19-7.56-1.99-12.02-6.88c-0.06-0.06-0.12-0.12-0.18-0.18 c-1.12-1.02-2.84-0.93-3.86,0.18c-4.18,4.59-8.1,6.97-11.74,7.01c-3.55,0.04-7.14-2.2-10.75-6.85l0,0 c-0.16-0.21-0.36-0.4-0.59-0.56c-1.23-0.87-2.94-0.57-3.8,0.66c-2.66,3.78-5.77,6.02-9.11,6.62c-2.29,0.41-4.75,0.02-7.38-1.06 C4.39,84.19,6.36,82.92,6,76.15C5.73,71.1,10.14,66.95,15.2,66.95L15.2,66.95z M104.15,89.85v0.3h-0.48 C103.83,90.06,103.99,89.96,104.15,89.85L104.15,89.85z M102.02,91.07v21.49H8.26V91.69c3.39,1.35,6.72,1.72,9.89,1.15 c3.86-0.7,7.39-2.77,10.45-6.13c4.07,4.31,8.37,6.39,12.89,6.34c4.58-0.05,9.16-2.32,13.72-6.71c4.93,4.73,9.7,6.83,14.32,6.58 c4.37-0.23,8.39-2.54,12.1-6.68c3.9,4.77,8.25,6.98,13,6.86C97.05,93.03,99.51,92.34,102.02,91.07L102.02,91.07z M44.13,7.71 C52.37,19.65,35.94,21.79,44.13,7.71L44.13,7.71z M54.96,0C63.2,11.94,46.76,14.08,54.96,0L54.96,0z M65.79,7.71 C74.02,19.65,57.59,21.79,65.79,7.71L65.79,7.71z"/></g></svg>', 'Custom cakes for every celebration', 1),
(2, 'Pastries', '🥐', 'Soft, flaky and filled with cream', 2),
(3, 'Bread', '🍞', 'Baked fresh every morning', 3),
(4, 'Cookies and Biscuits', '🍪', 'Crunchy, buttery and homemade', 4),
(5, 'Puffs and Snacks', '🥟', 'Hot, savoury tea-time favourites', 5),
(6, 'Cupcakes', '🧁', 'Single-serve treats with rich frosting', 6),
(7, 'Chocolates and Desserts', '🍫', 'Indulgent chocolate and sweet endings', 7);
INSERT INTO products
(category_id, name, description, price, old_price, image, is_featured, is_offer)
VALUES
-- Birthday Cakes
(1, 'Chocolate Truffle Cake (1 kg)', 'Rich dark chocolate sponge layered with silky truffle ganache.', 750.00, 850.00, 'cake1.jpg', 1, 1),
(1, 'Black Forest Cake (1 kg)', 'Classic cherry and whipped cream cake with chocolate shavings.', 650.00, NULL, 'cake2.jpg', 1, 0),
(1, 'Butterscotch Cake (1 kg)', 'Vanilla sponge with butterscotch cream and crunchy praline.', 600.00, NULL, 'cake3.jpg', 0, 0),
(1, 'Pineapple Cake (1 kg)', 'Light sponge with fresh pineapple pieces and soft cream.', 580.00, NULL, 'cake4.jpg', 0, 0),

-- Pastries
(2, 'Chocolate Pastry', 'Moist chocolate sponge with a smooth ganache topping.', 60.00, NULL, 'pastry.jpg', 1, 0),
(2, 'Black Forest Pastry', 'Cherry, cream and chocolate in a single slice.', 55.00, NULL, 'pastry2.jpg', 0, 0),
(2, 'Fruit Cream Pastry', 'Vanilla sponge with whipped cream and seasonal fruit.', 65.00, 75.00, 'pastry3.jpg', 0, 1),
(2, 'Red Velvet Pastry', 'Velvety red sponge with cream cheese frosting.', 80.00, NULL, 'pastry4.jpg', 0, 0),

-- Bread
(3, 'Fresh Milk Bread', 'Soft, fluffy sandwich loaf baked fresh daily.', 45.00, NULL, 'bread.jpg', 1, 0),
(3, 'Whole Wheat Bread', 'Wholesome atta bread, high in fibre.', 55.00, NULL, 'bread2.jpg', 0, 0),
(3, 'Garlic Bread', 'Toasted loaf with garlic butter and herbs.', 90.00, NULL, 'bread3.jpg', 0, 0),
(3, 'Pav (6 pcs)', 'Soft ladi pav, perfect with bhaji or vada pav.', 30.00, NULL, 'brad4.jpg', 0, 0),

-- Cookies and Biscuits
(4, 'Butter Cookies (250 g)', 'Melt-in-the-mouth butter cookies.', 180.00, NULL, 'cookies.jpg', 1, 0),
(4, 'Choco Chip Cookies (250 g)', 'Loaded with dark chocolate chips.', 220.00, 250.00, 'cookies2.jpg', 0, 1),
(4, 'Nankhatai (250 g)', 'Traditional Indian shortbread with cardamom.', 160.00, NULL, 'cookies3.jpg', 0, 0),
(4, 'Jeera Biscuits (250 g)', 'Crisp salty-spicy biscuits, ideal with chai.', 120.00, NULL, 'cookies4.jpg', 0, 0),

-- Puffs and Snacks
(5, 'Veg Puff', 'Flaky pastry filled with spiced vegetable masala.', 25.00, NULL, 'puff.jpg', 1, 0),
(5, 'Paneer Puff', 'Golden puff with spicy paneer filling.', 35.00, NULL, 'puff2.jpg', 0, 0),
(5, 'Cheese Sandwich', 'Grilled sandwich with melted cheese and veggies.', 70.00, NULL, 'puff3.jpg', 0, 0),
(5, 'Veg Burger', 'Crispy patty, fresh veggies and sauces in a soft bun.', 60.00, NULL, 'puff4.jpg', 0, 0),

-- Cupcakes
(6, 'Vanilla Cupcake', 'Soft vanilla cupcake with buttercream swirl.', 50.00, NULL, 'cupcake.jpg', 0, 0),
(6, 'Chocolate Cupcake', 'Rich chocolate cupcake with ganache frosting.', 55.00, NULL, 'cupcake2.jpg', 1, 0),
(6, 'Red Velvet Cupcake', 'Cocoa-kissed red cupcake with cream cheese frosting.', 70.00, NULL, 'cupcake3.jpg', 0, 0),
(6, 'Cupcake Box (6 pcs)', 'Assorted flavours in a gift box.', 280.00, 330.00, 'cupcake4.jpg', 0, 1),

-- Chocolates and Desserts
(7, 'Chocolate Brownie', 'Fudgy brownie with crackly top and walnuts.', 70.00, NULL, 'Chocolate Brownie.jpg', 1, 0),
(7, 'Assorted Chocolates (200 g)', 'Hand-made milk, dark and hazelnut chocolates.', 350.00, NULL, 'brownies2.jpg', 0, 0),
(7, 'Gulab Jamun Cake Jar', 'Fusion dessert jar with gulab jamun and cream.', 120.00, NULL, 'brownies3.jpg', 0, 0),
(7, 'Choco Lava Cake', 'Warm cake with a molten chocolate centre.', 85.00, 99.00, 'brownies4.jpg', 0, 1);