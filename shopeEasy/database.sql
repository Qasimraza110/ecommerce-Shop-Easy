-- Create database
CREATE DATABASE IF NOT EXISTS ecommerce_db;
USE ecommerce_db;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Create categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    image_url VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Create products table
CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category_id INT,
    image_url VARCHAR(255),
    featured BOOLEAN DEFAULT FALSE,
    stock INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Create orders table
CREATE TABLE IF NOT EXISTS orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending',
    shipping_address TEXT NOT NULL,
    shipping_city VARCHAR(100) NOT NULL,
    shipping_state VARCHAR(100) NOT NULL,
    shipping_zip VARCHAR(20) NOT NULL,
    shipping_country VARCHAR(100) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Create order_items table
CREATE TABLE IF NOT EXISTS order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Create contact_messages table
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('new', 'read', 'replied') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create cart table
CREATE TABLE IF NOT EXISTS cart (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample categories
INSERT INTO categories (name, description) VALUES
('Electronics', 'Latest electronic gadgets and devices'),
('Clothing', 'Fashion and apparel for all ages'),
('Books', 'Fiction, non-fiction, and educational books'),
('Home & Kitchen', 'Home decor and kitchen appliances'),
('Sports', 'Sports equipment and accessories'),
('Beauty', 'Beauty and personal care products');

-- Insert sample products
INSERT INTO products (name, description, price, category_id, featured, stock) VALUES
('Smartphone X', 'Latest smartphone with advanced features', 699.99, 1, TRUE, 50),
('Laptop Pro', 'High-performance laptop for professionals', 1299.99, 1, TRUE, 30),
('Men\'s T-Shirt', 'Comfortable cotton t-shirt', 29.99, 2, FALSE, 100),
('Women\'s Dress', 'Elegant summer dress', 49.99, 2, TRUE, 75),
('Novel Collection', 'Bestselling novels pack', 39.99, 3, FALSE, 200),
('Coffee Maker', 'Automatic coffee maker with timer', 79.99, 4, TRUE, 40),
('Yoga Mat', 'Premium quality yoga mat', 24.99, 5, FALSE, 150),
('Facial Cleanser', 'Gentle facial cleanser for all skin types', 19.99, 6, TRUE, 100),
('Wireless Earbuds', 'Noise-cancelling wireless earbuds', 149.99, 1, TRUE, 60),
('Smart Watch', 'Fitness tracker and smart watch', 199.99, 1, FALSE, 45),
('Running Shoes', 'Comfortable running shoes', 89.99, 5, TRUE, 80),
('Cookware Set', 'Complete cookware set for your kitchen', 299.99, 4, FALSE, 25);

-- Insert sample user (password: Test@123)
INSERT INTO users (username, email, password) VALUES
('testuser', 'test@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Update orders table
ALTER TABLE orders
ADD COLUMN shipping_city VARCHAR(100) NOT NULL AFTER shipping_address,
ADD COLUMN shipping_state VARCHAR(100) NOT NULL AFTER shipping_city,
ADD COLUMN shipping_zip VARCHAR(20) NOT NULL AFTER shipping_state,
ADD COLUMN shipping_country VARCHAR(100) NOT NULL AFTER shipping_zip; 