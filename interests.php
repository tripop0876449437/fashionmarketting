<?php
require 'db.php'; // เชื่อมต่อฐานข้อมูล

// ดึงข้อมูลหมวดหมู่สินค้า
$categoriesQuery = $pdo->query("SELECT * FROM categories");
$categories = $categoriesQuery->fetchAll(PDO::FETCH_ASSOC);

// ดึงข้อมูลตัวกรอง (ช่วงอายุและราคา)
$filtersQuery = $pdo->query("SELECT * FROM filters");
$filters = $filtersQuery->fetchAll(PDO::FETCH_ASSOC);

// แยกค่า age_range และ price ออกจาก filters
$ageRanges = $pdo->query("SELECT DISTINCT age_range FROM products WHERE age_range IS NOT NULL AND age_range <> ''");
$ageRanges = $ageRanges->fetchAll(PDO::FETCH_COLUMN);

$prices = $pdo->query("SELECT DISTINCT price FROM products WHERE price IS NOT NULL AND price <> ''");
$prices = $prices->fetchAll(PDO::FETCH_COLUMN);

$colors = $pdo->query("SELECT DISTINCT color FROM products WHERE color IS NOT NULL AND color <> ''");
$colors = $colors->fetchAll(PDO::FETCH_COLUMN);

// คำสั่ง SQL พื้นฐาน (แสดงสินค้าทั้งหมด)
$sql = "
    SELECT products.*, categories.name AS category_name
    FROM products
    JOIN categories ON products.category_id = categories.id
    WHERE 1
";

// เก็บเงื่อนไขตัวกรอง
$conditions = [];
$params = [];

// ตรวจสอบว่ามีการส่งฟอร์มหรือไม่
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!empty($_POST['category'])) {
        $conditions[] = "categories.name = :category";
        $params['category'] = $_POST['category'];
    }
    if (!empty($_POST['gender'])) {
        $conditions[] = "products.gender = :gender";
        $params['gender'] = $_POST['gender'];
    }
    if (!empty($_POST['price_range'])) {
        $priceRange = $_POST['price_range'];

        if ($priceRange === "5000+") {
            $conditions[] = "products.price >= :min_price";
            $params['min_price'] = 5000;
        } else {
            list($min, $max) = explode('-', $priceRange);
            $conditions[] = "products.price BETWEEN :min_price AND :max_price";
            $params['min_price'] = $min;
            $params['max_price'] = $max;
        }
    }
    if (!empty($_POST['price'])) {
        $conditions[] = "products.price <= :price";
        $params['price'] = preg_replace('/[^0-9.]/', '', $_POST['price']);
    }
    if (!empty($_POST['color'])) {
        $conditions[] = "products.color = :color";
        $params['color'] = $_POST['color'];
    }
}

// ถ้ามีตัวกรอง ให้เพิ่ม `WHERE`
if (!empty($conditions)) {
    $sql .= " AND " . implode(" AND ", $conditions);
}

// ดึงข้อมูลสินค้า
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        <h1 class="page-title">ตัวเลือกหมวดหมู่</h1>

        <form action="interests.php" method="POST" class="filter-form">
            <!-- หมวดหมู่ -->
            <div class="form-group">
                <label for="category">หมวดหมู่สินค้า:</label>
                <select id="category" name="category">
                    <option value="">-- แสดงทั้งหมด --</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= htmlspecialchars($category['name']) ?>">
                            <?= htmlspecialchars($category['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- สี -->
            <div class="form-group">
                <label for="price_range">ช่วงราคา:</label>
                <select id="price_range" name="price_range">
                    <option value="">-- แสดงทั้งหมด --</option>
                    <option value="0-500">ต่ำกว่า 500 บาท</option>
                    <option value="500-1000">500 - 1,000 บาท</option>
                    <option value="1000-5000">1,000 - 5,000 บาท</option>
                    <option value="5000+">5,000 บาทขึ้นไป</option>
                </select>
            </div>


            <!-- เพศ -->
            <div class="form-group">
                <label for="gender">เพศ:</label>
                <select id="gender" name="gender">
                    <option value="">-- แสดงทั้งหมด --</option>
                    <option value="ชาย">ชาย</option>
                    <option value="หญิง">หญิง</option>
                    <option value="อื่น ๆ">อื่น ๆ</option>
                </select>
            </div>

            <!-- ช่วงอายุ -->
            <div class="form-group">
                <label for="age_range">ช่วงอายุ:</label>
                <select id="age_range" name="age_range">
                    <option value="">-- แสดงทั้งหมด --</option>
                    <?php foreach ($ageRanges as $age): ?>
                        <option value="<?= htmlspecialchars($age) ?>">
                            <?= htmlspecialchars($age) ?>
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

        <!-- แสดงสินค้าที่ค้นหา -->
        <br>
        <h2>ผลการค้นหา</h2>
        <div class="product-list">
            <?php if (count($products) > 0): ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" width="100%">
                        <h3><?= htmlspecialchars($product['name']) ?></h3>
                        <p>สี: <?= htmlspecialchars($product['color']) ?></p>
                        <p>หมวดหมู่: <?= htmlspecialchars($product['category_name']) ?></p>
                        <p>ราคา: <?= number_format($product['price'], 2) ?> บาท</p>
                        <p>คะแนนรีวิวเริ่มต้น: <?= str_repeat('⭐', round($product['review_rating'])) ?></p>

                        <!-- ปุ่มถูกใจ / ไม่ถูกใจ -->
                        <form action="update_preference.php" method="POST" class="preference-form">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

                            <button type="submit" name="preference" value="like" class="like-btn">
                                ❤️ ถูกใจ
                            </button>

                            <button type="submit" name="preference" value="disliked" class="dislike-btn">
                                👎 ไม่ถูกใจ
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-results">ไม่พบสินค้าที่ตรงกับตัวกรองของคุณ</p>
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