<?php
require 'db.php';

// 1️⃣ ดึงสินค้าที่ผู้ใช้กด "ถูกใจ"
$likedQuery = $pdo->query("
    SELECT DISTINCT brand, color 
    FROM products 
    WHERE preference = 'like'
");
$likedProducts = $likedQuery->fetchAll(PDO::FETCH_ASSOC);

if ($likedProducts) {
    // 2️⃣ ดึงแบรนด์และสีของสินค้าที่ถูกกด Like
    $likedBrands = array_unique(array_column($likedProducts, 'brand'));
    $likedColors = array_unique(array_column($likedProducts, 'color'));

    // 3️⃣ Query แสดงสินค้าแนะนำที่มี Brand และ Color เดียวกัน
    $sql = "
        SELECT * FROM products 
        WHERE brand IN (" . implode(',', array_fill(0, count($likedBrands), '?')) . ") 
        OR color IN (" . implode(',', array_fill(0, count($likedColors), '?')) . ") 
        ORDER BY created_at DESC
    ";
    $params = array_merge($likedBrands, $likedColors);
} else {
    // 4️⃣ ถ้ายังไม่มีการกด Like → แสดงสินค้าทั้งหมด
    $sql = "SELECT * FROM products ORDER BY created_at DESC";
    $params = [];
}

// 5️⃣ เตรียมและรันคำสั่ง SQL
$query = $pdo->prepare($sql);
$query->execute($params);
$products = $query->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าแรก</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">Logo</div>
        <ul class="menu">
            <li><a href="index.php" class="active">หน้าแรก</a></li>
            <li><a href="interests.php">ความสนใจ</a></li>
            <li><a href="recommendations.php">คำแนะนำ</a></li>
            <li><a href="feedback.php">Feedback</a></li>
            <li><a href="about.php">About</a></li>
        </ul>
        <!-- Profile Icon -->
        <div class="profile-container">
            <div class="profile-icon">👤</div>
            <ul class="profile-menu">
                <!-- <li><a href="#">โปรไฟล์</a></li> -->
                <!-- <li><a href="#">การตั้งค่า</a></li> -->
                <li><a href="add_product.php">เพิ่มสินค้า</a></li>
                <!-- <li><a href="#">ออกจากระบบ</a></li> -->
            </ul>
        </div>
    </nav>

    <!-- เนื้อหา -->
    <div class="content">
        <!-- คำอธิบายเกี่ยวกับเว็บไซต์ -->
        <div class="description">
            <h2>คำอธิบายเกี่ยวกับเว็บไซต์</h2>
            <p>
                ระบบแนะนำโฆษณาของเราถูกออกแบบมาเพื่อปรับแต่งคำแนะนำให้เหมาะสมกับความสนใจของคุณโดยเฉพาะ
                โดยใช้ข้อมูลที่คุณระบุ เช่น หมวดหมู่แฟชั่นที่ชื่นชอบ ช่วงราคา หรือความสนใจเฉพาะด้าน
                ระบบจะคัดเลือกโฆษณาและสินค้าแฟชั่นที่ตรงกับความต้องการของคุณมากที่สุด
                เพื่อให้คุณได้รับประสบการณ์ที่ตอบโจทย์และสะดวกสบายที่สุด
            </p>
        </div>

        <!-- สินค้าแนะนำ -->
<div class="recommended-products">
    <h2>สินค้าแนะนำ</h2>
    <?php if (!empty($products)): ?>
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                <h3><?= htmlspecialchars($product['name']) ?></h3>
                <p><?= number_format($product['price'], 2) ?> THB</p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="no-results">ยังไม่มีสินค้าแนะนำ</p>
    <?php endif; ?>
</div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const profileIcon = document.querySelector(".profile-icon");
            const profileMenu = document.querySelector(".profile-menu");

            // แสดง/ซ่อนเมนูเมื่อคลิกที่ไอคอน
            profileIcon.addEventListener("click", function() {
                profileMenu.style.display = profileMenu.style.display === "block" ? "none" : "block";
            });

            // ปิดเมนูเมื่อคลิกที่อื่น
            document.addEventListener("click", function(e) {
                if (!profileIcon.contains(e.target) && !profileMenu.contains(e.target)) {
                    profileMenu.style.display = "none";
                }
            });
        });
    </script>
</body>
</html>
