-- Create database
CREATE DATABASE IF NOT EXISTS skincare_store;
USE skincare_store;

-- Users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    address TEXT,
    phone VARCHAR(20),
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category VARCHAR(50),
    brand VARCHAR(50),
    skin_type VARCHAR(50),
    image_url VARCHAR(255),
    stock INT DEFAULT 10,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Cart table
CREATE TABLE cart (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    product_id INT,
    quantity INT DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Orders table
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    total_amount DECIMAL(10,2),
    status ENUM('pending', 'processing', 'shipped', 'delivered') DEFAULT 'pending',
    shipping_address TEXT,
    payment_method VARCHAR(50),
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Order items table
CREATE TABLE order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT,
    product_id INT,
    quantity INT,
    price DECIMAL(10,2),
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Insert sample skincare products. image_url is a full link (not a filename) —
-- the site prints it straight into <img src="...">, no local images/ folder needed.
INSERT INTO products (name, description, price, category, brand, skin_type, image_url, stock) VALUES
('Vitamin C Serum', 'Brightening serum with 20% vitamin C', 45.99, 'Serum', 'GlowLab', 'All', 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?auto=format&fit=crop&w=500&q=70', 15),
('Hydrating Moisturizer', 'Deep hydration with hyaluronic acid', 32.50, 'Moisturizer', 'DermaCare', 'Dry', 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?auto=format&fit=crop&w=500&q=70', 20),
('Gentle Cleanser', 'Sulfate-free foaming cleanser', 18.99, 'Cleanser', 'PureSkin', 'Sensitive', 'https://images.unsplash.com/photo-1608248543803-ba4f8c70ae0b?auto=format&fit=crop&w=500&q=70', 25),
('Retinol Cream', 'Anti-aging cream with retinol', 52.00, 'Treatment', 'AgeDefy', 'Aging', 'https://images.unsplash.com/photo-1612817288484-6f916006741a?auto=format&fit=crop&w=500&q=70', 10),
('Sunscreen SPF 50', 'Broad spectrum protection', 28.75, 'Sun Protection', 'SunShield', 'All', 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?auto=format&fit=crop&w=500&q=70', 30),
('Exfoliating Scrub', 'Gentle exfoliation with jojoba beads', 22.99, 'Exfoliator', 'GlowLab', 'Oily', 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=500&q=70', 18);

-- Insert sample admin user — username "admin", password "admin123"
-- (log in with username or email; change this password after your first login)
INSERT INTO users (username, email, password, full_name, role)
VALUES ('admin', 'admin@skincare.com', '$2y$12$sN9zZXllOpRoX88kC.dvA..n9OUabzoMbzG.KgnhrWZCBpDf0EbZW', 'Admin User', 'admin');
