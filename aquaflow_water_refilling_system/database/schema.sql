CREATE DATABASE IF NOT EXISTS aquaflow_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE aquaflow_db;

CREATE TABLE IF NOT EXISTS users (
 id INT AUTO_INCREMENT PRIMARY KEY, username VARCHAR(60) NOT NULL UNIQUE,
 password_hash VARCHAR(255) NOT NULL, full_name VARCHAR(120) NOT NULL,
 role ENUM('Admin','Cashier','Staff') NOT NULL DEFAULT 'Staff',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS customers (
 id INT AUTO_INCREMENT PRIMARY KEY, customer_code VARCHAR(30) UNIQUE,
 full_name VARCHAR(120) NOT NULL, contact VARCHAR(40), address VARCHAR(255),
 balance DECIMAL(10,2) NOT NULL DEFAULT 0, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS products (
 id INT AUTO_INCREMENT PRIMARY KEY, product_name VARCHAR(120) NOT NULL,
 category VARCHAR(80), price DECIMAL(10,2) NOT NULL DEFAULT 0,
 stock INT NOT NULL DEFAULT 0, unit VARCHAR(30) DEFAULT 'piece',
 low_stock_threshold INT NOT NULL DEFAULT 10, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS dealers (
 id INT AUTO_INCREMENT PRIMARY KEY, dealer_name VARCHAR(120) NOT NULL,
 contact VARCHAR(40), address VARCHAR(255), created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS orders (
 id INT AUTO_INCREMENT PRIMARY KEY, order_no VARCHAR(30) NOT NULL UNIQUE,
 customer_id INT NULL, order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
 total DECIMAL(10,2) NOT NULL DEFAULT 0,
 status ENUM('Pending','Processing','Completed','Cancelled') DEFAULT 'Pending',
 payment_status ENUM('Unpaid','Partial','Paid') DEFAULT 'Unpaid',
 delivery_type ENUM('Pickup','Delivery') DEFAULT 'Pickup',
 FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL
);
CREATE TABLE IF NOT EXISTS order_items (
 id INT AUTO_INCREMENT PRIMARY KEY, order_id INT NOT NULL, product_id INT NULL,
 quantity INT NOT NULL, unit_price DECIMAL(10,2) NOT NULL, subtotal DECIMAL(10,2) NOT NULL,
 FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
 FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
);
CREATE TABLE IF NOT EXISTS expenses (
 id INT AUTO_INCREMENT PRIMARY KEY, expense_title VARCHAR(150) NOT NULL,
 amount DECIMAL(10,2) NOT NULL, expense_date DATE NOT NULL, notes TEXT
);
CREATE TABLE IF NOT EXISTS activity_logs (
 id INT AUTO_INCREMENT PRIMARY KEY, user_id INT NULL, action VARCHAR(120) NOT NULL,
 details TEXT, ip_address VARCHAR(45), created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

INSERT IGNORE INTO users(username,password_hash,full_name,role)
VALUES('admin', '$2y$10$wH4wR4JfJ4Y9Wj1Zp6V5Ee8t4uB3xg5mR8Yy8Y8Y8Y8Y8Y8Y8Y8Y8', 'System Administrator', 'Admin');
-- The installer replaces the demo hash with a known hash if the admin user is first created.
INSERT INTO customers(customer_code,full_name,contact,address,balance)
SELECT 'CUST-001','John Dela Cruz','09123456789','Iloilo City',0
WHERE NOT EXISTS (SELECT 1 FROM customers WHERE customer_code='CUST-001');
INSERT INTO customers(customer_code,full_name,contact,address,balance)
SELECT 'CUST-002','Maria Santos','09987654321','Pavia',150
WHERE NOT EXISTS (SELECT 1 FROM customers WHERE customer_code='CUST-002');
INSERT INTO products(product_name,category,price,stock,unit,low_stock_threshold)
SELECT 'Purified Water','Water Products',25,150,'gallon',20
WHERE NOT EXISTS (SELECT 1 FROM products WHERE product_name='Purified Water');
INSERT INTO products(product_name,category,price,stock,unit,low_stock_threshold)
SELECT 'Mineral Water','Water Products',30,120,'gallon',20
WHERE NOT EXISTS (SELECT 1 FROM products WHERE product_name='Mineral Water');
INSERT INTO products(product_name,category,price,stock,unit,low_stock_threshold)
SELECT 'Empty Gallon','Containers',150,5,'piece',10
WHERE NOT EXISTS (SELECT 1 FROM products WHERE product_name='Empty Gallon');
INSERT INTO products(product_name,category,price,stock,unit,low_stock_threshold)
SELECT 'Bottle Cap','Accessories',5,500,'piece',50
WHERE NOT EXISTS (SELECT 1 FROM products WHERE product_name='Bottle Cap');
INSERT INTO dealers(dealer_name,contact,address)
SELECT 'Aqua Supplies Trading','09170000000','Iloilo City'
WHERE NOT EXISTS (SELECT 1 FROM dealers WHERE dealer_name='Aqua Supplies Trading');
