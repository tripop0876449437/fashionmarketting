<?php
require 'db.php';

// ตรวจสอบการบันทึกข้อมูล
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $_POST['image'];
    $categories = $_POST['categories'];
    $genders = $_POST['genders'];

    $stmt = $pdo->prepare("INSERT INTO products (name, price, image, categories, genders) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $price, $image, $categories, $genders]);

    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มสินค้า</title>
</head>
<body>
    <h1>เพิ่มสินค้าใหม่</h1>
    <form action="product_form.php" method="POST">
        <label for="name">ชื่อสินค้า:</label><br>
        <input type="text" id="name" name="name" required><br>

        <label for="price">ราคา:</label><br>
        <input type="number" step="0.01" id="price" name="price" required><br>

        <label for="image">รูปภาพ (Path):</label><br>
        <input type="text" id="image" name="image" required><br>

        <label for="categories">หมวดหมู่:</label><br>
        <input type="text" id="categories" name="categories" required><br>

        <label for="genders">เพศ:</label><br>
        <input type="text" id="genders" name="genders" required><br>

        <button type="submit">เพิ่มสินค้า</button>
    </form>

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
