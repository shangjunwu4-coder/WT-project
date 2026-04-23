CREATE DATABASE IF NOT EXISTS secondhandmarket CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE secondhandmarket;


CREATE TABLE users
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    gender VARCHAR(10) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE categories
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);


CREATE TABLE products 
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    status VARCHAR(20) DEFAULT 'active',
    views INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);


CREATE TABLE favorites 
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,

    UNIQUE KEY unique_favorite (user_id, product_id)
);


INSERT INTO categories (name) VALUES
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


INSERT INTO users (username, email, password, gender)
VALUES ('testuser', 'test@example.com', '123456' ,'Male');
