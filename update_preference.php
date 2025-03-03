<?php
require 'db.php'; // เชื่อมต่อฐานข้อมูล

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['product_id'], $_POST['preference'])) {
    $productId = $_POST['product_id'];
    $preference = $_POST['preference']; // 'like' หรือ 'disliked'

    // อัปเดตฐานข้อมูล
    $stmt = $pdo->prepare("UPDATE products SET preference = :preference WHERE id = :product_id");
    $stmt->execute([
        'preference' => $preference,
        'product_id' => $productId
    ]);

    // ส่งกลับไปยังหน้าเดิม
    header("Location: " . $_SERVER["HTTP_REFERER"]);
    exit;
}
?>
