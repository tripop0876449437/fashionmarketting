CREATE DATABASE product_database;

USE product_database;

-- ตารางสินค้า
-- CREATE TABLE products (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     name VARCHAR(255) NOT NULL,
--     price DECIMAL(10,2) NOT NULL,
--     image VARCHAR(255) NOT NULL,
--     review_rating FLOAT DEFAULT 0
-- );



-- สร้างตารางหมวดหมู่
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

-- เพิ่มข้อมูลหมวดหมู่ตัวอย่าง
INSERT INTO categories (name) VALUES 
('รองเท้า'), 
('กระเป๋า'), 
('เสื้อผ้า'), 
('เครื่องประดับ');

-- สร้างตารางสินค้า
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category_id INT NOT NULL,
    gender VARCHAR(255) DEFAULT 'ชาย',
    age_range VARCHAR(255) DEFAULT '14 - 18 ปี',
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    review_rating FLOAT DEFAULT 0,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

INSERT INTO products (name, category_id, gender, age_range, price, image, review_rating) VALUES
('รองเท้าผ้าใบ Nike', 1, 'ชาย', '14 - 18 ปี', 2999.00, 'images/nike.jpg', 4.5),
('รองเท้า Adidas', 1, 'ชาย', '19 - 25 ปี', 2590.00, 'images/adidas.jpg', 4.2),
('รองเท้า Converse', 1, 'ชาย', '26 - 35 ปี', 1890.00, 'images/converse.jpg', 4.8);


-- ตารางตัวกรอง (ช่วงอายุและราคา)
CREATE TABLE filters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    age_range VARCHAR(50),
    price VARCHAR(50)
);

-- เพิ่มข้อมูลตัวอย่าง
INSERT INTO products (name, category_id, gender, age_range, price, image) VALUES
('รองเท้า Nike', 1, 'ชาย', '14 - 18 ปี', 2999.00, 'images/nike.jpg'),
('กระเป๋า Gucci', 2, 'ชาย', '19 - 25 ปี', 15500.00, 'images/gucci.jpg');


INSERT INTO categories (name) VALUES
('เสื้อผ้า'),
('รองเท้า'),
('กระเป๋า');

INSERT INTO filters (age_range, price) VALUES
('14 - 18 ปี', '350 บาท'),
('19 - 25 ปี', '500 บาท'),
('26 - 35 ปี', '1,000 บาท');


CREATE TABLE recommendations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) NOT NULL,
    review_rating FLOAT DEFAULT 0
);

INSERT INTO recommendations (name, category, price, image, review_rating) VALUES
('รองเท้า Nike', 'รองเท้า', 2999.00, 'images/nike.jpg', 4.5),
('กระเป๋า Gucci', 'กระเป๋า', 15500.00, 'images/gucci.jpg', 4.8);

CREATE TABLE feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(255) NOT NULL,
    comments TEXT NOT NULL,
    review_score INT CHECK (review_score BETWEEN 1 AND 5),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO feedback (product_name, comments, review_score) VALUES
('รองเท้า Nike', 'รองเท้าดูดสีดำสวยมาก สบายใจใช้งาน', 5),
('กระเป๋า Gucci', 'กระเป๋ามีคุณภาพดี ราคาสมเหตุผล', 4);

CREATE TABLE suggestions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    messages TEXT NOT NULL,
    review_score INT CHECK (review_score BETWEEN 1 AND 5),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);


INSERT INTO suggestions (product_id, messages, review_score) VALUES
(1, 'ขอบคุณสำหรับข้อเสนอแนะของคุณ!', 5);

