<?php
require 'db.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่ามีการส่งฟอร์มหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $suggestions = trim($_POST['suggestions']); // กรองช่องว่าง

    if (!empty($suggestions)) {
        // บันทึกลงฐานข้อมูล
        $stmt = $pdo->prepare("INSERT INTO suggestions (message) VALUES (?)");
        $stmt->execute([$suggestions]);

        // แจ้งเตือนเมื่อบันทึกสำเร็จ
        $message = "ขอบคุณสำหรับข้อเสนอแนะของคุณ!";
        $suggestions = ""; // รีเซ็ตค่าหลังจากบันทึก
    } else {
        $message = "กรุณากรอกข้อเสนอแนะก่อนส่งข้อมูล!";
    }
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/about.css">
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
            <li><a href="recommendations.php">คำแนะนำ</a></li>
            <li><a href="feedback.php">Feedback</a></li>
            <li><a href="about.php" class="active">About</a></li>
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
        <h1 class="page-title">About/Contact</h1>

        <!-- แสดงข้อความแจ้งเตือน -->
        <?php if (!empty($message)): ?>
            <p class="success-message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <form action="about.php" method="POST" class="about-form">
            <div class="form-section">
                <div class="form-group">
                    <h3>คำอธิบายระบบ</h3>
                    <p>
                        ระบบเว็บไซต์นี้แนะนำโฆษณาแฟชั่น ช่วยแสดงโฆษณาที่ตรงกับความสนใจของผู้ใช้
                        โดยที่ไม่ต้องสมัครใช้งาน เพียงเลือกความสนใจ ระบบจะวิเคราะห์และแนะนำสินค้าที่เหมาะสมที่สุดให้ทันที
                    </p>
                </div>

                <div class="form-group">
                    <h3>ช่องทางการติดต่อ</h3>
                    <p>
                        Email: simple@gmail.com<br>
                        Instagram: Simple<br>
                        Facebook: Simple<br>
                        LINE: Simple<br>
                        Tel: 098 011 2523
                    </p>
                </div>

                <!-- กรอกข้อความข้อเสนอแนะ -->
                <div class="form-group">
                    <h3>กรอกข้อความข้อเสนอแนะ/คำแนะนำ</h3>
                    <textarea name="suggestions" placeholder="กรอกคำแนะนำของคุณที่นี่..." required>
                        <?= ($_SERVER['REQUEST_METHOD'] === 'POST') ? htmlspecialchars($suggestions) : '' ?>
                    </textarea>
                </div>
            </div>

            <div class="form-buttons">
                <button type="submit" class="btn-submit">ตกลง</button>
                <button type="reset" class="btn-reset">ล้างข้อมูล</button>
            </div>
        </form>
    </div>

    <!-- JavaScript สำหรับรีเซ็ตค่า textarea -->
    <script>
        document.querySelector(".btn-reset").addEventListener("click", function() {
            document.querySelector("textarea[name='suggestions']").value = "";
        });
    </script>

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