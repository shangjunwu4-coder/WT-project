USE secondhandmarket;

ALTER TABLE products
    ADD COLUMN IF NOT EXISTS views INT NOT NULL DEFAULT 0 AFTER status;

INSERT IGNORE INTO categories (name) VALUES
('Books'),
('Textbooks'),
('Electronics'),
('Phones & Tablets'),
('Laptops'),
('Clothes'),
('Shoes'),
('Furniture'),
('Daily Use'),
('Dorm Supplies'),
('Kitchenware'),
('Bicycles'),
('Sports Equipment'),
('Beauty & Personal Care'),
('Study Supplies'),
('Tickets & Coupons'),
('Other');

UPDATE users
SET password = '$2y$10$2xVkcXCn.qAN5QJLrBSGQuypVebiPYWcnuXLmC6pqBrB6znwBBnWq'
WHERE email = 'test@example.com'
  AND password = '123456';
