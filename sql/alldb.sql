CREATE DATABASE IF NOT EXISTS `fashion_marketplace` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `fashion_marketplace`;

-- ตารางสินค้า
CREATE TABLE IF NOT EXISTS `recommendations` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `category` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `price` DECIMAL(10, 2) NOT NULL,
    `image` VARCHAR(255) NOT NULL,
    `review_rating` DECIMAL(3, 1) NOT NULL
);

INSERT INTO `recommendations` (`category`, `name`, `price`, `image`, `review_rating`) VALUES
('รองเท้า', 'Fairy Queenz', 280.00, 'images/shoes1.png', 5.0),
('รองเท้า', 'Fairy Queenz', 290.00, 'images/shoes2.png', 4.8),
('รองเท้า', 'Inspired', 590.00, 'images/shoes3.png', 4.0),
('รองเท้า', 'Inspired', 490.00, 'images/shoes4.png', 4.5);

-- ตารางฟีดแบ็ก
CREATE TABLE IF NOT EXISTS `feedback` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `product_name` VARCHAR(255) NOT NULL,
    `comments` TEXT NOT NULL,
    `review_score` INT NOT NULL
);

INSERT INTO `feedback` (`product_name`, `comments`, `review_score`) VALUES
('รองเท้าผ้าใบหญิง', 'ใส่สบายไม่เจ็บเท้า สีสันสวยงาม ชอบมากๆ เลยค่ะ', 5),
('กระเป๋าสะพายข้าง', 'วัสดุดี แต่สายคล้องสั้นไปนิด', 4),
('หมวกแฟชั่น', 'ดีไซน์เก๋มาก ใส่ได้ทุกวัน', 5);

-- ตารางข้อมูลช่องทางการติดต่อ
CREATE TABLE IF NOT EXISTS `contact_info` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(255) NOT NULL,
    `instagram` VARCHAR(255) NOT NULL,
    `facebook` VARCHAR(255) NOT NULL,
    `line` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(20) NOT NULL
);

INSERT INTO `contact_info` (`email`, `instagram`, `facebook`, `line`, `phone`) VALUES
('simple@gmail.com', 'Simple', 'Simple', 'Simple', '0980112523');

-- ตารางคำอธิบายระบบ
CREATE TABLE IF NOT EXISTS `system_description` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `description` TEXT NOT NULL
);

INSERT INTO `system_description` (`description`) VALUES
('ระบบเว็บไซต์นี้แนะนำโฆษณาแฟชั่น ช่วยแสดงโฆษณาที่ตรงกับความสนใจของผู้ใช้ โดยที่ไม่ต้องสมัครใช้งาน เพียงเลือกความสนใจ ระบบจะวิเคราะห์และแนะนำสินค้าที่เหมาะสมที่สุดให้ทันที');
