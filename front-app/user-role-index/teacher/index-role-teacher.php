<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <title>ระบบจัดเก็บผลงานตีพิมพ์</title>
    <link rel="stylesheet" href="index-role-teacher.css">
</head>
<body>
    <header>

        <div class="header-container">
            
                <div class="logo-container">
                    <a href="/pub_teacher/front-app/user-role-index/teacher/index-role-teacher.php">
                        <img src="/pub_teacher/front-app/Pic/logo1.png" alt="logo">
                    </a>
                </div>
            <h1>ระบบจัดเก็บผลงานตีพิมพ์อาจารย์</h1> 
        </div>

    </header>

   <nav class="nav">
        <ul>
            <div class="profile">
                <div class="profile-info">
                <?php
                $con = new mysqli("localhost", "root", "", "public_teacher");
                session_start();
                $username = $_SESSION["username"];
                $password = $_SESSION["password"];
                $sql_user = "SELECT u.fname , u.lname , a.type_name , u.major FROM user u , user_acc ua , account_type a WHERE ua.user_id = u.user_id AND ua.type_id = a.type_id AND ua.username = '$username' AND ua.password = '$password'";
                $result_user = mysqli_query($con , $sql_user);
                $row_user = mysqli_fetch_assoc($result_user);
                ?>
                    <h3>ชื่อ-นามสกุล</h3>
                    <p><?php echo $row_user["fname"]?> <?php echo $row_user["lname"]?></p>
                    <p>ตำแหน่ง: <?php echo $row_user["type_name"]?></p>
                    <p>สาขา: <?php echo $row_user["major"]?></p>
                </div>
            </div>
            <div class="line"></div>
            <li><a href="/pub_teacher/front-app/user-role-index/teacher/index-role-teacher.php"><i class="bi bi-house icon-large"></i> หน้าแรก</a></li>
            <li><a href="/front-app/function/ManageProfile.php"><i class="bi bi-person icon-large"></i> ข้อมูลส่วนตัว</a></li>
            <li>
                <a href="#" onclick="openModal()">
                    <i class="bi bi-gear icon-large"></i> คู่มือการใช้งาน
                </a>
            </li>
                <p></p>
            <li><a href="/pub_teacher/back-app/login-exit/logout.php"><i class="bi bi-box-arrow-right icon-large"></i> ออกจากระบบ</a></li>
            
        </ul>
   </nav>

    <main>
        <div class="main-wrapper">
                <div class="search-bar">
                    <form action="#" method="GET" class="search-form">
                        <input type="text" name="q" placeholder="ค้นหาบทความ..." aria-label="Search">
                        <button type="submit"><i class="bi bi-search"></i></button>
                    </form>
                </div>

                <div class="bar">
                    <ul>
                        <li><a href="index.html"><i class="bi bi-journal-text icon-large"></i> จัดการบทความ</a></li>
                        <li><a href="index.html"><i class="bi-file-earmark-text icon-large"></i> จัดทำรายงานสรุป </a></li>
                        <li><a href="index.html"><i class="bi bi-pencil-square icon-large"></i> แก้ไขอัพเดตบทความ</a></li>
                        <li><a href="index.html"><i class="bi bi-bar-chart-line icon-large"></i> สถานะการอัพผลงานการตีพิมพ์</a></li>
                    </ul>           
                </div>

        <div class="content-container">
            <h2>บทความตีพิมพ์ล่าสุด</h2>
            <div class="articles-list-container">
                <?php
                // เชื่อมต่อฐานข้อมูล
                $conn = new mysqli("localhost", "root", "", "public_teacher");

                $sql = "
                     SELECT p.pub_id, p.pub_name, p.upload_date, c.cname ,u.fname, u.lname , p.pic
                            FROM publication p , user_acc ua , user u , category c
                            WHERE p.acc_id = ua.acc_id 
                            AND ua.user_id = u.user_id 
                            AND p.c_id = c.c_id
                            ORDER BY p.upload_date DESC
                    
                    
                ";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()){
                        $img = !empty($row['pic']) 
                            ? "/pub_teacher/src/pic_public/" . $row['pic'] 
                            : "/pub_teacher/front-app/Pic/bk1.jpg";
                        ?>
                        <div class="articles-list">
                            <div class="article-pic">
                                <img src="<?php echo htmlspecialchars($img); ?>" alt="รูปบทความ">
                            </div>
                            <div class="article-text">
                                <h3><?php echo htmlspecialchars($row['pub_name']); ?></h3>
                                <p>ผู้แต่ง: <?php echo htmlspecialchars($row['fname'] . " " . $row['lname']); ?></p>
                                <p>หมวดหมู่: <?php echo htmlspecialchars($row['cname']); ?></p>
                                <a href="detail.php?pub_id=<?php echo $row['pub_id']; ?>">อ่านเพิ่มเติม...</a>
                            </div>
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

        </div>
    </main>

    <footer>
        <p>@มหาวิทยาลัย สงขลานครินทร์ วิทยาเขตหาดใหญ่. สมาชิก 143 251 253 254 325 378 </p>
    </footer>

</body>
</html>

<!-- Modal หน้าต่าง popup -->
<div id="settingsModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h2>คู่มือการใช้งาน</h2>
      <span class="close" onclick="closeModal()">&times;</span>
    </div>
    <div class="modal-body">
      <!-- แทนที่ form ด้วย iframe สำหรับ PDF -->
      <iframe src="/front-app/UserGuide/finalReq-Publication-group9.pdf" width="100%" height="800px" style="border:none;"></iframe>
    </div>
    <div class="modal-footer">
      <button class="btn cancel" onclick="closeModal()">ปิด</button>
    </div>
  </div>
</div>

<script>
function openModal() {
  const modal = document.getElementById("settingsModal");
  modal.style.display = "flex";
  setTimeout(() => modal.classList.add("show"), 10);
}

function closeModal() {
  const modal = document.getElementById("settingsModal");
  modal.classList.remove("show");
  setTimeout(() => modal.style.display = "none", 400);
}
</script>