<?php
require 'db.php';

// รับค่าการค้นหาจาก GET
$search = $_GET['search'] ?? '';

// 1️⃣ ดึงสินค้าที่ผู้ใช้กด "ถูกใจ"
$likedQuery = $pdo->query("
    SELECT id, category_id, brand
    FROM products 
    WHERE preference = 'disliked'
");
$likedProducts = $likedQuery->fetchAll(PDO::FETCH_ASSOC);

if ($likedProducts) {
    // 2️⃣ ดึงหมวดหมู่ (`category_id`) และแบรนด์ (`brand`) ของสินค้าที่ถูก Like
    $likedCategories = array_unique(array_column($likedProducts, 'category_id'));
    $likedBrands = array_unique(array_column($likedProducts, 'brand'));
    $likedProductIds = array_unique(array_column($likedProducts, 'id')); // ห้ามแสดงสินค้าที่ถูก Like เอง

    // 3️⃣ Query แสดงเฉพาะสินค้าที่มี `category_id` หรือ `brand` เหมือนกัน
    $sql = "
        SELECT p.id AS product_id, p.name, p.image, p.price, p.color, p.brand, p.category_id, p.created_at,
               s.messages, s.review_score
        FROM products p
        LEFT JOIN suggestions s ON p.id = s.product_id  
        WHERE (p.category_id IN (" . implode(',', array_fill(0, count($likedCategories), '?')) . ") 
        OR p.brand IN (" . implode(',', array_fill(0, count($likedBrands), '?')) . "))
        AND p.id NOT IN (" . implode(',', array_fill(0, count($likedProductIds), '?')) . ") 
        ORDER BY p.created_at DESC
    ";

    // 4️⃣ รวมค่าทั้งหมดเป็นอาร์เรย์สำหรับ `execute()`
    $params = array_merge($likedCategories, $likedBrands, $likedProductIds);
} else {
    // 5️⃣ ถ้ายังไม่มีการกด Like → แสดงสินค้าทั้งหมด
    $sql = "
        SELECT p.id AS product_id, p.name, p.image, p.price, p.color, p.brand, p.category_id, p.created_at,
               s.messages, s.review_score
        FROM products p
        LEFT JOIN suggestions s ON p.id = s.product_id
        WHERE p.name LIKE ? OR p.brand LIKE ?
        ORDER BY p.created_at DESC
    ";
    $params = ["%$search%", "%$search%"];
}

// 6️⃣ เตรียมและรันคำสั่ง SQL
$query = $pdo->prepare($sql);
$query->execute($params);
$suggestions = $query->fetchAll(PDO::FETCH_ASSOC);
?>




<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>คำแนะนำ</title>
    <link rel="stylesheet" href="css/recommendations.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">
            <!-- <img src="images/logo.png" alt="Logo"> -->
            Logo
        </div>
        <ul class="menu">
            <li><a href="index.php">หน้าแรก</a></li>
            <li><a href="interests.php">ความสนใจ</a></li>
            <li><a href="recommendations.php" class="active">คำแนะนำ</a></li>
            <li><a href="feedback.php">Feedback</a></li>
            <li><a href="about.php">About</a></li>
        </ul>
        <!-- Profile Icon -->
        <div class="profile-container">
            <div class="profile-icon">👤</div>
            <ul class="profile-menu">
                <!-- <li><a href="#">โปรไฟล์</a></li>
                <li><a href="#">การตั้งค่า</a></li> -->
                <li><a href="add_product.php">เพิ่มสินค้า</a></li>
                <!-- <li><a href="#">ออกจากระบบ</a></li> -->
            </ul>
        </div>
    </nav>

    <!-- Content -->
    <div class="content">
        <h1 class="page-title">คำแนะนำ</h1>

        <!-- ค้นหา -->
        <form method="GET" class="search-form">
            <input
                type="text"
                name="search"
                placeholder="ค้นหา: เช่น รองเท้า"
                value="<?= htmlspecialchars($search) ?>">
            <button type="submit">🔍</button>
        </form>

        <!-- คำแนะนำโฆษณา -->
        <button class="btn-ad">คำแนะนำโฆษณา</button>

        <!-- รายการข้อเสนอแนะ -->
        <h2>รายการข้อเสนอแนะ</h2>
        <div class="product-list">
            <?php if ($suggestions): ?>
                <?php foreach ($suggestions as $suggestion): ?>
                    <div class="product-card">
                        <img src="<?= htmlspecialchars($suggestion['image']) ?>" alt="<?= htmlspecialchars($suggestion['name']) ?>">
                        <h3><?= htmlspecialchars($suggestion['name']) ?></h3>
                        <p>แบรนด์: <?= htmlspecialchars($suggestion['brand']) ?></p>
                        <p>สี: <?= htmlspecialchars($suggestion['color']) ?></p>
                        <p>ราคา <?= number_format($suggestion['price'], 2) ?> บาท</p>
                        <p>คะแนนรีวิว: <?= isset($suggestion['review_score']) ? str_repeat('⭐', round($suggestion['review_score'])) : 'ไม่มีคะแนน' ?></p>
                        <p><strong>รีวิว: </strong> <?= htmlspecialchars($suggestion['messages'] ?? 'ไม่มีรีวิว') ?></p>
                        <p class="date">วันที่แนะนำ: <?= date("d/m/Y H:i", strtotime($suggestion['created_at'])) ?> น.</p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-results">ไม่พบข้อเสนอแนะที่ตรงกับคำค้นหา</p>
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