CREATE DATABASE product_database;

USE product_database;

-- ตารางสินค้า
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255) NOT NULL
);

-- ตารางหมวดหมู่
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

-- ตารางตัวกรอง (ช่วงอายุและราคา)
CREATE TABLE filters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    age_range VARCHAR(50),
    price VARCHAR(50)
);

-- เพิ่มข้อมูลตัวอย่าง
INSERT INTO products (name, price, image) VALUES
('เสื้อเชิ้ตสไตล์เกาหลี: Cherry factory', 107.00, 'images/shirt.png'),
('รองเท้าแตะแฟชั่น: OXXO', 250.00, 'images/shoes.png');

INSERT INTO categories (name) VALUES
('เสื้อผ้า'),
('รองเท้า'),
('กระเป๋า');

INSERT INTO filters (age_range, price) VALUES
('14 - 18 ปี', '350 บาท'),
('19 - 25 ปี', '500 บาท'),
('26 - 35 ปี', '1,000 บาท');
