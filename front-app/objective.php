<?php
    include("../condb.php");
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <title>ระบบจัดเก็บผลงานตีพิมพ์</title>
    <link rel="stylesheet" href="objective.css">
    <link rel="icon" href="/front-app/Pic/logo3.png" type="image/png">
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo-container">
                <a href="/front-app/ex-user.php">
                    <img src="/front-app/pic/logo1.png" alt="logo">
                </a>
            </div>
            <h1>ระบบจัดเก็บผลงานตีพิมพ์อาจารย์</h1> 
        </div>
    </header>

    <nav class="nav">
        <ul>
            <div class="user-login-container">
                <div class="login-info">
                    <h3>สำหรับบุคลากร</h3>
                    <form action="/back-app/login-exit/login.php" method="post">
                        <div class="input-box">
                            <input type="text" name="username" placeholder="ชื่อผู้ใช้" required>
                        </div>
                        <div class="input-box">
                            <input type="password" name="pass" placeholder="รหัสผ่าน" required>
                        </div>
                        <button type="submit" class="btn">ลงชื่อเข้าใช้</button>
                    </form>
                </div>
            </div>
            <div class="line"></div>
            <li><a href="/front-app/ex-user.php"><i class="bi bi-house icon-large"></i> หน้าแรก</a></li>
            <li><a href="/front-app/ex-public.php"><i class="bi bi-journal-text icon-large"></i> บทความตีพิมพ์</a></li>
            <li><a href="/front-app/contact.php"><i class="bi bi-envelope icon-large"></i> ติดต่อ</a></li>
            <li><a href="/front-app/objective.php"><i class="bi bi-info-circle icon-large"></i> เกี่ยวกับ</a></li>
        </ul>
    </nav>

<main>
    <div class="objective-container">
        <h1>เกี่ยวกับระบบจัดเก็บผลงานตีพิมพ์อาจารย์</h1>
        <p>ระบบจัดเก็บผลงานตีพิมพ์อาจารย์นี้ถูกพัฒนาเพื่อให้เป็นแหล่งข้อมูลที่รวบรวมผลงานตีพิมพ์ของอาจารย์ในมหาวิทยาลัยสงขลานครินทร์ วิทยาเขตหาดใหญ่ โดยมีวัตถุประสงค์หลักดังนี้:</p>
        <ul>
            <li><strong>การจัดเก็บข้อมูล:</strong> ระบบนี้ช่วยให้อาจารย์สามารถจัดเก็บข้อมูลผลงานตีพิมพ์ของตนเองได้อย่างเป็นระบบและง่ายดาย</li>
            <li><strong>การค้นหาและเข้าถึง:</strong> ผู้ใช้สามารถค้นหาและเข้าถึงผลงานตีพิมพ์ได้อย่างรวดเร็วผ่านระบบค้นหาที่มีประสิทธิภาพ</li>
            <li><strong>การส่งเสริมการวิจัย:</strong> ระบบนี้สนับสนุนการส่งเสริมการวิจัยและการเผยแพร่ผลงานของอาจารย์ในวงกว้าง</li>
            <li><strong>ความปลอดภัยของข้อมูล:</strong> ระบบมีมาตรการรักษาความปลอดภัยของข้อมูลเพื่อปกป้องข้อมูลส่วนบุคคลและผลงานตีพิมพ์ของอาจารย์</li>
        </ul>
        <p>เราหวังว่าระบบนี้จะเป็นเครื่องมือที่มีประโยชน์สำหรับอาจารย์และบุคลากรในมหาวิทยาลัยในการจัดการและเผยแพร่ผลงานวิจัยของตนเองอย่างมีประสิทธิภาพ</p>
        <br>
        <p style="text-align: center;">หากมีข้อเสนอแนะหรือคำถามเพิ่มเติม กรุณาติดต่อทีมงานผ่านหน้าติดต่อของเรา</p>
        <br>
        <h2 style="text-align: center;">จัดทำโดย</h2>
        <ul style="list-style-type: none; text-align: center; padding: 0;">
            <li>นาย ธนกฤต ชีวรุ่งโรจน์ (143)</li>
            <li>นาย พงศกร จรัญรักษ์ (251)</li>
            <li>นาย พงศธร แจ๊ดสันเทียะ (253)</li>
            <li>นาย พงศพล คงจันทร์ (254)</li>
            <li>นาย มูไฮมัน อาลี (325)</li>
            <li>นาย วีรภัทร ชุมประยูร (378)</li>

    </div>

</main>

<footer>
    <p>@มหาวิทยาลัย สงขลานครินทร์ วิทยาเขตหาดใหญ่. สมาชิก 143 251 253 254 325 378 </p>
</footer>
</body>
</html>