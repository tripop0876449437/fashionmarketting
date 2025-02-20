<?php
require 'db.php'; // เชื่อมต่อฐานข้อมูล

$message = "";

// ตรวจสอบการส่งฟอร์ม
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // ตรวจสอบค่าที่ได้รับจากฟอร์ม
        if (empty($_POST['product_name']) || empty($_POST['messages']) || empty($_POST['review_score'])) {
            throw new Exception("กรุณากรอกข้อมูลให้ครบทุกช่อง");
        }

        $productId = (int) $_POST['product_name']; // ใช้ `id` ของสินค้า
        $messages = htmlspecialchars($_POST['messages']);
        $reviewScore = (int) $_POST['review_score']; // แปลงเป็นตัวเลข

        // บันทึกข้อมูลลงฐานข้อมูล (เปลี่ยน `product_name` เป็น `product_id`)
        $stmt = $pdo->prepare("INSERT INTO suggestions (product_id, messages, review_score) VALUES (?, ?, ?)");
        $stmt->execute([$productId, $messages, $reviewScore]);

        // Redirect เพื่อป้องกันการส่งค่าซ้ำ
        header("Location: feedback.php?success=1");
        exit;
    } catch (Exception $e) {
        $message = "เกิดข้อผิดพลาด: " . $e->getMessage();
    }
}

// ตรวจสอบว่าการบันทึกสำเร็จหรือไม่
if (isset($_GET['success'])) {
    $message = "ฟีดแบ็กของคุณถูกส่งเรียบร้อยแล้ว!";
}

// ดึงข้อมูลสินค้าสำหรับแสดงในฟอร์ม
$stmt = $pdo->query("SELECT id, name FROM products ORDER BY name ASC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>



<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback</title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/feedback.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">Logo</div>
        <ul class="menu">
            <li><a href="index.php">หน้าแรก</a></li>
            <li><a href="interests.php">ความสนใจ</a></li>
            <li><a href="recommendations.php">คำแนะนำ</a></li>
            <li><a href="feedback.php" class="active">Feedback</a></li>
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
        <h1 class="page-title">Feedback</h1>
        <?php if (!empty($message)): ?>
            <p class="success-message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <form action="feedback.php" method="POST" class="feedback-form">
            <!-- ชื่อสินค้า -->
            <div class="form-group">
                <label for="product_name">ชื่อสินค้า:</label>
                <select id="product_name" name="product_name" required>
                    <option value="">-- เลือกสินค้า --</option>
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                            <option value="<?= htmlspecialchars($product['id']) ?>">
                                <?= htmlspecialchars($product['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">ไม่มีสินค้าในระบบ</option>
                    <?php endif; ?>
                </select>
            </div>




            <!-- ความคิดเห็น -->
            <div class="form-group">
                <label for="messages">ความคิดเห็น:</label>
                <textarea id="messages" name="messages" placeholder="กรอกความคิดเห็นของคุณ" required></textarea>
            </div>

            <!-- คะแนนรีวิว -->
            <div class="form-group">
                <label for="review_score">คะแนนรีวิว:</label>
                <select id="review_score" name="review_score" required>
                    <option value="1">1 ดาว</option>
                    <option value="2">2 ดาว</option>
                    <option value="3">3 ดาว</option>
                    <option value="4">4 ดาว</option>
                    <option value="5">5 ดาว</option>
                </select>
            </div>

            <!-- ปุ่ม -->
            <div class="form-buttons">
                <button type="submit" class="btn-submit">ตกลง</button>
                <button type="reset" class="btn-reset">ล้างข้อมูล</button>
            </div>
        </form>
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