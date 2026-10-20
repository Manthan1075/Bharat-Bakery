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
(1, 'Birthday Cakes', 'birthday-cake-icon.svg',
 'Custom cakes for every celebration', 1),

(2, 'Pastries', 'cake-slice-icon.svg',
 'Soft, flaky and filled with cream', 2),

(3, 'Bread', 'toast-icon.svg',
 'Baked fresh every morning', 3),

(4, 'Cookies and Biscuits', 'cookie-bite-icon.svg',
 'Crunchy, buttery and homemade', 4),

(5, 'Puffs and Snacks', 'snacks-icon.svg',
 'Hot, savoury tea-time favourites', 5),

(6, 'Cupcakes', 'cup-cake-icon.svg',
 'Single-serve treats with rich frosting', 6),

(7, 'Chocolates and Desserts', 'chocolate-icon.svg',
 'Indulgent chocolate and sweet endings', 7);
 
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