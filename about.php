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
            <img src="images/logo.png" alt="Logo">
        </div>
        <ul class="menu">
            <li><a href="index.php">หน้าแรก</a></li>
            <li><a href="interests.php">ความสนใจ</a></li>
            <li><a href="recommendations.php">คำแนะนำ</a></li>
            <li><a href="feedback.php">Feedback</a></li>
            <li><a href="about.php" class="active">About</a></li>
        </ul>
        <div class="profile-icon">👤</div>
    </nav>

    <!-- Content -->
    <div class="content">
        <h1 class="page-title">About/Contact</h1>
        
        <form action="about.php" method="POST" class="about-form">
            <div class="form-section">
                <!-- คำอธิบายระบบ -->
                <div class="form-group">
                    <h3>คำอธิบายระบบ</h3>
                    <p>
                        ระบบเว็บไซต์นี้แนะนำโฆษณาแฟชั่น ช่วยแสดงโฆษณาที่ตรงกับความสนใจของผู้ใช้ 
                        โดยที่ไม่ต้องสมัครใช้งาน เพียงเลือกความสนใจ ระบบจะวิเคราะห์และแนะนำสินค้าที่เหมาะสมที่สุดให้ทันที
                    </p>
                </div>

                <!-- ช่องทางการติดต่อ -->
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

                <!-- กรอกข้อความคำแนะนำ -->
                <div class="form-group">
                    <h3>กรอกข้อความข้อเสนอแนะ/คำแนะนำ</h3>
                    <textarea name="suggestions" placeholder="กรอกคำแนะนำของคุณที่นี่..." required></textarea>
                </div>
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
