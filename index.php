<?php
require 'db.php';

// ดึงข้อมูลสินค้า
$query = $pdo->query("SELECT * FROM products");
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
        <div class="profile-icon">👤</div>
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
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p><?= htmlspecialchars($product['price']) ?> THB</p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
