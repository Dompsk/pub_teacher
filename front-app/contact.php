<?php
    include("../condb.php");
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <title>ระบบจัดเก็บผลงานตีพิมพ์</title>
    <link rel="stylesheet" href="contact.css">
    <link rel="icon" href="/pub_teacher/front-app/Pic/logo3.png" type="image/png">

</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo-container">
                <a href="/pub_teacher/front-app/ex-user.php">
                    <img src="/pub_teacher/front-app/pic/logo1.png" alt="logo">
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
                        <form action="/pub_teacher/back-app/login-exit/login.php" method="post">
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
            <li><a href="/pub_teacher/front-app/ex-user.php"><i class="bi bi-house icon-large"></i> หน้าแรก</a></li>
            <li><a href="/pub_teacher/front-app/ex-public.php"><i class="bi bi-journal-text icon-large"></i> บทความตีพิมพ์</a></li>
            <li><a href="/pub_teacher/front-app/contact.php"><i class="bi bi-envelope icon-large"></i> ติดต่อ</a></li>
            <li><a href="/pub_teacher/front-app/objective.php"><i class="bi bi-info-circle icon-large"></i> เกี่ยวกับ</a></li>
           
        </ul>
   </nav>

    <main>
        <div class="container">

                <hr>
            <div class="contact-info">
                    <h2> ☎️ สามารถติดต่อได้ที่ </h2>
                <p>ที่อยู่สำนักงาน : ศุภสาร คอนโดมีเนียม ชั้น4 ห้องเลขที่413  </p>
                <p>ถนน ศุภสารรังสรรค์ ตำบล หาดใหญ่ อำเภอหาดใหญ่ สงขลา 90110</p>
                <p>ติดต่อ เบอร์โทร : <a href="tel:0123456789">063-476-2787</a></p>
                <p>LINE : <a href="https://line.me/R/ti/p/%40POOLONGTAI">@POOSAKON </a></p>
                <p>Facebook : <a href="https://www.facebook.com/pongsakorn.panthaworn">PONGSAKON PANTHAWORN</a></p>
                <p>ig : <a href="https://www.instagram.com/_oakkrub_16912/">_oakkrub_16912</a></p>
                
                    <div class="map"><br>
                        <p>Google Map 🗺️</p>
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d350.0188207667991!2d100.48370079006085!3d7.007997912049081!2m3!1f0!2f0!3f0!3m2!1i1024!2i
                            768!4f13.1!3m3!1m2!1s0x304d284d8eb05b15%3A0x40bb329eb1ed8899!2z4Lio4Li44Lig4Liq4Liy4LijIOC4hOC4reC4meC5guC4lOC4oeC4teC5gOC4meC4teC4ouC4oQ!5e0!3m2!1sth!
                            2sth!4v1738685975092!5m2!1sth!2sth" width="550" height="280" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
            </div>
        </div>
    </main>




    <footer>
        <p>@มหาวิทยาลัย สงขลานครินทร์ วิทยาเขตหาดใหญ่. สมาชิก 143 251 253 254 325 378 </p>
    </footer>

</body>
</html>
