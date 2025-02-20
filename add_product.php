<?php
require 'db.php'; // เชื่อมต่อฐานข้อมูล

$message = "";

// ดึงหมวดหมู่จากฐานข้อมูล
$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $category_id = intval($_POST['category_id']);
  $price = trim($_POST['price']);
  $review_rating = floatval($_POST['review_rating']); // รับค่าคะแนนรีวิว

  if (!empty($name) && !empty($category_id) && !empty($price) && isset($_FILES['image'])) {
    $target_dir = "images/"; // โฟลเดอร์เก็บรูป
    if (!file_exists($target_dir)) {
      mkdir($target_dir, 0777, true); // สร้างโฟลเดอร์ถ้ายังไม่มี
    }

    $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
    $image_name = uniqid() . '.' . $imageFileType; // ตั้งชื่อไฟล์ใหม่กันซ้ำ
    $target_file = $target_dir . $image_name;

    // ตรวจสอบว่าเป็นไฟล์รูปภาพหรือไม่
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
      $message = "ไฟล์ที่อัปโหลดไม่ใช่รูปภาพ!";
    } elseif ($_FILES["image"]["size"] > 5000000) { // จำกัดขนาด 5MB
      $message = "ขนาดไฟล์ใหญ่เกินไป (ต้องไม่เกิน 5MB)!";
    } elseif (!in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
      $message = "อนุญาตเฉพาะไฟล์ JPG, JPEG, PNG และ GIF เท่านั้น!";
    } else {
      // อัปโหลดไฟล์
      if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // บันทึกลงฐานข้อมูล พร้อมคะแนนรีวิว
        $stmt = $pdo->prepare("INSERT INTO products (name, category_id, price, image, review_rating) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $category_id, $price, $target_file, $review_rating]);

        $message = "เพิ่มสินค้าสำเร็จ!";
      } else {
        $message = "เกิดข้อผิดพลาดในการอัปโหลดไฟล์!";
      }
    }
  } else {
    $message = "กรุณากรอกข้อมูลให้ครบทุกช่อง!";
  }
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>เพิ่มสินค้า</title>
  <link rel="stylesheet" href="css/add_product.css">
</head>

<body>
  <div class="add-product-container">
    <h2>เพิ่มสินค้าใหม่</h2>

    <?php if (!empty($message)): ?>
      <p class="<?= strpos($message, 'สำเร็จ') !== false ? 'success-message' : 'error-message' ?>">
        <?= htmlspecialchars($message) ?>
      </p>
    <?php endif; ?>

    <form action="add_product.php" method="POST" class="add-product-form" enctype="multipart/form-data">
      <!-- ชื่อสินค้า -->
      <label for="name">ชื่อสินค้า:</label>
      <div class="input-group">
        <input type="text" id="name" name="name" required>
        <button type="button" class="clear-btn" onclick="clearInput('name')">✖</button>
      </div>

      <!-- หมวดหมู่ -->
      <label for="category_id">หมวดหมู่:</label>
      <select id="category_id" name="category_id" required>
        <option value="">-- เลือกหมวดหมู่ --</option>
        <?php foreach ($categories as $category): ?>
          <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
        <?php endforeach; ?>
      </select>

      <!-- ราคา -->
      <label for="price">ราคา:</label>
      <div class="input-group">
        <input type="number" id="price" name="price" step="0.01" required>
        <!-- <button type="button" class="clear-btn" onclick="clearInput('price')">✖</button> -->
      </div>

      <!-- คะแนนรีวิว -->
      <label for="review_rating">คะแนนรีวิวเริ่มต้น (0 - 5 ดาว):</label>
      <div class="input-group">
        <input type="number" id="review_rating" name="review_rating" step="0.1" min="0" max="5" required>
        <button type="button" class="clear-btn" onclick="clearInput('review_rating')">✖</button>
      </div>

      <!-- อัปโหลดรูปภาพ -->
      <label for="image">อัปโหลดรูปภาพ:</label>
      <input type="file" id="image" name="image" accept="image/*" required>

      <div class="form-buttons">
        <button type="submit" class="btn-submit">ตกลง</button>
        <button type="reset" class="btn-reset">ล้างข้อมูล</button>
        <a href="index.php" class="btn-home">🏠 หน้าแรก</a>
      </div>
    </form>
  </div>

  <script>
    function clearInput(id) {
      document.getElementById(id).value = "";
    }
  </script>

</body>

</html>