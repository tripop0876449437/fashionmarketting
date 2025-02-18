<?php
require 'db.php';

// ดึงข้อมูลสินค้า (รองเท้า) จากฐานข้อมูล
$search = $_GET['search'] ?? ''; // รับค่าการค้นหาจาก GET
$query = $pdo->prepare("
    SELECT * FROM recommendations
    WHERE name LIKE :search OR category LIKE :search
");
$query->execute(['search' => "%$search%"]);
$products = $query->fetchAll(PDO::FETCH_ASSOC);
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
        <div class="logo">Logo</div>
        <ul class="menu">
            <li><a href="index.php">หน้าแรก</a></li>
            <li><a href="interests.php">หน้าสินค้า</a></li>
            <li><a href="recommendations.php" class="active">คำแนะนำ</a></li>
            <li><a href="feedback.php">Feedback</a></li>
            <li><a href="about.php">About</a></li>
        </ul>
        <!-- Profile Icon -->
        <div class="profile-container">
            <div class="profile-icon">👤</div>
            <ul class="profile-menu">
                <li><a href="#">โปรไฟล์</a></li>
                <li><a href="#">การตั้งค่า</a></li>
                <li><a href="add_product.php">เพิ่มสินค้า</a></li>
                <li><a href="#">ออกจากระบบ</a></li>
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

        <!-- รายการสินค้า -->
        <div class="product-list">
            <?php if ($products): ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                        <h3><?= htmlspecialchars($product['name']) ?></h3>
                        <p>ราคา <?= htmlspecialchars($product['price']) ?> บาท</p>
                        <p>รีวิว: <?= str_repeat('⭐', floor($product['review_rating'])) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-results">ไม่พบสินค้าที่คุณค้นหา</p>
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