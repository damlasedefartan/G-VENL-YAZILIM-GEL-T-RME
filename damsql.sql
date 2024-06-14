CREATE DATABASE ayakkabi_satis;

USE ayakkabi_satis;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'editor', 'user') DEFAULT 'user'
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
INSERT INTO users (username, password, role) VALUES 
('admin', 'adminpassword', 'admin'),
('editor', 'editorpassword', 'editor'),
('user', 'userpassword', 'user');


INSERT INTO products (name, description, price) VALUES 
('Terlik', 'Rahat ve kullanışlı terlik', 19.99),
('Spor Ayakkabı', 'Spor yaparken kullanılacak ayakkabı', 59.99),
('Topuklu Ayakkabı', 'Elegant ve şık topuklu ayakkabı', 79.99);






select * from products;
