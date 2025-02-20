<?php
require 'db.php';

// รับค่าการค้นหาจาก GET
$search = $_GET['search'] ?? '';

// ค้นหาข้อมูลจาก `suggestions` ที่เชื่อมกับ `products`
$query = $pdo->prepare("
    SELECT s.messages, s.review_score, s.created_at, 
           p.id AS product_id, p.name, p.image, p.price
    FROM suggestions s
    JOIN products p ON s.product_id = p.id
    WHERE p.name LIKE :search OR s.messages LIKE :search
    ORDER BY s.created_at DESC
");
$query->execute(['search' => "%$search%"]);
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
                        <p>ราคา <?= number_format($suggestion['price'], 2) ?> บาท</p>
                        <p>คะแนนรีวิว: <?= str_repeat('⭐', round($suggestion['review_score'])) ?></p>
                        <p><strong>รีวิว: </strong>:</strong> <?= htmlspecialchars($suggestion['messages']) ?></p>
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