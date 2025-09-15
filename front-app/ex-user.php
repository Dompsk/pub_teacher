<?php
include("../back-app/condb.php");
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <title>ระบบจัดเก็บผลงานตีพิมพ์</title>
    <link rel="stylesheet" href="ex-user.css">
</head>
<body>
    <header>

        <div class="header-container">
            
                <div class="logo-container">
                    <a href="ex-user.html">
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
            <li><a href="/front-app/ex-user.html"><i class="bi bi-house icon-large"></i> หน้าแรก</a></li>
            <li><a href="/front-app/function/ManageProfile.php"><i class="bi bi-journal-text icon-large"></i> บทความตีพิมพ์</a></li>
            <li><a href="/front-app/function/ManageProfile.php"><i class="bi bi-envelope icon-large"></i> ติดต่อ</a></li>
            <li><a href="/front-app/function/ManageProfile.php"><i class="bi bi-info-circle icon-large"></i> เกี่ยวกับ</a></li>
           
        </ul>
   </nav>

<div class="articles-container">
    <section class="top-left-articles">
        <h1>บทความล่าสุด</h1>
            <div class="article-content">
                <div class="lef-pic">
                    <img src="/front-app/Pic/logo1.png" alt="">
                </div>
            <?php
                // เชื่อมต่อฐานข้อมูล
                $conn = new mysqli("localhost", "root", "", "public_teacher");
            
                // ดึงบทความล่าสุด 1 อัน
                $sql = "
                    SELECT p.pub_id, p.pub_name, p.upload_date, c.cname ,u.fname, u.lname
                    FROM publication p , user_acc ua , user u , category c
                    where p.acc_id = ua.acc_id and ua.user_id = u.user_id and p.c_id = c.c_id
                    ORDER BY p.upload_date DESC
                    LIMIT 1;
                ";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    ?>
                    <div class="left-text">
                        <h2>เรื่อง <?php echo htmlspecialchars($row['pub_name']); ?></h2>
                        <p>ชื่อผู้แต่ง: <?php echo htmlspecialchars($row['fname'] . " " . $row['lname']); ?></p>
                        <p>เกี่ยวกับ : <?php echo htmlspecialchars($row['cname']); ?></p>
                        <a href="detail.php?pub_id=<?php echo $row['pub_id']; ?>">อ่านเพิ่มเติม...</a>
                    </div>
                    <?php
                } else {
                    echo "<p>ยังไม่มีบทความ</p>";
                }

                $conn->close();
            ?>
        </div>
    </section>

                <?php
                    // เชื่อมต่อฐานข้อมูล
                    $conn = new mysqli("localhost", "root", "", "public_teacher");


                    $sql = "
                    SELECT p.pub_id, p.pub_name, p.upload_date, c.cname ,u.fname, u.lname
                    FROM publication p , user_acc ua , user u , category c
                    where p.acc_id = ua.acc_id and ua.user_id = u.user_id and p.c_id = c.c_id
                    ORDER BY RAND()
                    LIMIT 1;
                    ";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        ?>
                        <section class="top-right-articles">
                            <h1>บทความที่น่าสนใจ</h1>
                            <div class="article-content">
                                <div class="right-pic">
                                    <img src="/front-app/Pic/logo1.png" alt="">
                                </div>
                                <div class="right-text">
                                    <h2>เรื่อง <?php echo htmlspecialchars($row['pub_name']); ?></h2>
                                    <p>ชื่อผู้แต่ง: <?php echo htmlspecialchars($row['fname'] . " " . $row['lname']); ?></p>
                                    <p>เกี่ยวกับ: <?php echo htmlspecialchars($row['cname']); ?></p>
                                    <a href="detail.php?pub_id=<?php echo $row['pub_id']; ?>">อ่านเพิ่มเติม...</a>
                                </div>
                            </div>
                        </section>
                        <?php
                    } else {
                        echo "<p>ยังไม่มีบทความ</p>";
                    }

                    $conn->close();
                ?>
    </section>
</div>

    <main>
        <div class="main-wrapper">
            <h2>บทความตีพิมพ์ล่าสุด</h2>
            <div class="content-container">

            <?php
            // เชื่อมต่อฐานข้อมูล
            $conn = new mysqli("localhost", "root", "", "public_teacher");
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // ดึง 16 บทความล่าสุด พร้อมชื่อผู้แต่ง
            $sql = "
                SELECT p.pub_id, p.pub_name, p.upload_date, c.cname ,u.fname, u.lname
                    FROM publication p , user_acc ua , user u , category c
                    where p.acc_id = ua.acc_id and ua.user_id = u.user_id and p.c_id = c.c_id
                    ORDER BY p.upload_date DESC
                    LIMIT 8;
            ";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    // สมมติว่ารูปเก็บ path ไว้ใน c_id หรือมี column image_path ใช้ detail.php?pub_id= ในการคลิกไปดูรายละเอียดผลความตีพิม!!!!
                    $img = !empty($row['c_id']) ? $row['c_id'] : '/pub_teacher/front-app/Pic/bk1.jpg';
                    ?>  
                         
                        <div class="articles-list" onclick="location.href='detail.php?pub_id=<?php echo $row['pub_id']; ?>'">
                        <div class="pic-articles">
                            <img src="<?php echo htmlspecialchars($img); ?>" alt="ภาพบทความ">
                        </div>
                        <p><?php echo htmlspecialchars($row['pub_name']); ?></p>
                        <p>โดย: <?php echo htmlspecialchars($row['fname'] . " " . $row['lname']); ?></p>
                        </div>
                    <?php
                }
            } else {
                echo "<p>ยังไม่มีบทความ</p>";
            }

            $conn->close();
            ?>

            </div>
        </div>
    </main>




    <footer>
        <p>@มหาวิทยาลัย สงขลานครินทร์ วิทยาเขตหาดใหญ่. สมาชิก 143 251 253 254 325 378 </p>
    </footer>

</body>
</html>
