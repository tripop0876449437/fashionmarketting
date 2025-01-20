<?php
require 'db.php';

// ดึงข้อมูลหมวดหมู่สินค้า
$categoriesQuery = $pdo->query("SELECT name FROM categories");
$categories = $categoriesQuery->fetchAll(PDO::FETCH_ASSOC);

if (!$categories) {
    $categories = [['name' => 'ไม่มีข้อมูลหมวดหมู่']];
}

// ดึงข้อมูลตัวกรอง (ช่วงอายุและราคา)
$filtersQuery = $pdo->query("SELECT age_range, price FROM filters");
$filters = $filtersQuery->fetchAll(PDO::FETCH_ASSOC);

if (!$filters) {
    $ageRanges = ['ไม่มีข้อมูลช่วงอายุ'];
    $prices = ['ไม่มีข้อมูลราคา'];
} else {
    $ageRanges = array_column($filters, 'age_range');
    $prices = array_column($filters, 'price');
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ความสนใจ</title>
    <link rel="stylesheet" href="css/interests.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">Logo</div>
        <ul class="menu">
            <li><a href="index.php">หน้าแรก</a></li>
            <li><a href="interests.php" class="active">ความสนใจ</a></li>
            <li><a href="recommendations.php">คำแนะนำ</a></li>
            <li><a href="feedback.php">Feedback</a></li>
            <li><a href="about.php">About</a></li>
        </ul>
        <div class="profile-icon">👤</div>
    </nav>

    <!-- Content -->
    <div class="content">
        <h1 class="page-title">ตัวเลือกหมวดหมู่</h1>
        <form action="interests.php" method="POST" class="filter-form">
            <!-- หมวดหมู่ -->
            <div class="form-group">
                <label for="category">หมวดหมู่สินค้า:</label>
                <select id="category" name="category">
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= htmlspecialchars($category['name']) ?>">
                            <?= htmlspecialchars($category['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- เพศ -->
            <div class="form-group">
                <label for="gender">เพศ:</label>
                <select id="gender" name="gender">
                    <option value="ชาย">ชาย</option>
                    <option value="หญิง">หญิง</option>
                    <option value="อื่น ๆ">อื่น ๆ</option>
                </select>
            </div>

            <!-- ช่วงอายุ -->
            <div class="form-group">
                <label for="age_range">ช่วงอายุ:</label>
                <select id="age_range" name="age_range">
                    <?php foreach ($ageRanges as $age): ?>
                        <option value="<?= htmlspecialchars($age) ?>">
                            <?= htmlspecialchars($age) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- ราคา -->
            <div class="form-group">
                <label for="price">ราคา:</label>
                <select id="price" name="price">
                    <?php foreach ($prices as $price): ?>
                        <option value="<?= htmlspecialchars($price) ?>">
                            <?= htmlspecialchars($price) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- ปุ่ม -->
            <div class="form-buttons">
                <button type="submit" class="btn-submit">ตกลง</button>
                <button type="reset" class="btn-reset">ล้างข้อมูล</button>
            </div>
        </form>
    </div>
</body>
</html>
