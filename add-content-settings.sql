USE bharat_bakery;
-- Run once if you already imported database.sql earlier
INSERT IGNORE INTO settings (setting_key, setting_value) VALUES
('logo_file', 'logo.jpg'),
('hero_title', 'Freshly Baked with Love, Every Day!'),
('hero_text', 'Birthday cakes, soft bread, flaky pastries and hot snacks, baked in small batches every morning.'),
('footer_text', 'Freshly Baked with Love, Every Day! Cakes, pastries, bread and snacks made with quality ingredients.'),
('about_story', 'Bharat Bakery began with a simple idea: bake the way you would for your own family. Every loaf, cake and puff is made in small batches with quality flour, real butter and fresh ingredients.\n\nFrom birthday cakes for your little ones to soft pav for your evening bhaji, we bake for the everyday moments and the big celebrations of our neighbourhood.\n\nOur ovens start before sunrise, so your order is always fresh on the day you receive it.');
UPDATE settings SET setting_value = 'logo.jpg' WHERE setting_key = 'logo_file';

-- Contact details
UPDATE settings SET setting_value = 'Kadi, Gujarat' WHERE setting_key = 'address';
UPDATE settings SET setting_value = '+91 99223 34455' WHERE setting_key = 'phone';
UPDATE settings SET setting_value = '919922334455' WHERE setting_key = 'whatsapp_number';
UPDATE settings SET setting_value = 'Every day: 8:00 AM – 6:00 PM' WHERE setting_key = 'opening_hours';
UPDATE settings SET setting_value = 'https://maps.google.com/?q=Bharat+Bakery+Kadi+Gujarat' WHERE setting_key = 'maps_link';
