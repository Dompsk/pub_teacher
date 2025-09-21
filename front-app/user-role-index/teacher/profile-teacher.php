<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>ระบบจัดเก็บผลงานตีพิมพ์</title>
    <link rel="stylesheet" href="profile-teacher.css">
    <link rel="icon" href="/pub_teacher/front-app/Pic/logo3.png" type="image/png">

</head>
<body>
    <header>

        <div class="header-container">
            
                <div class="logo-container">
                    <a href="index.html">
                        <img src="/pro_teacher/front-app/Pic/logo1.png" alt="logo">
                    </a>
                </div>
            <h1 >ระบบจัดเก็บผลงานตีพิมพ์อาจารย์</h1> 
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
            <li><a href="/pro_teacher/back-app/login-exit/logout.php"><i class="bi bi-box-arrow-right icon-large"></i> ออกจากระบบ</a></li>
            
        </ul>
   </nav>

    <main>
        
    <h1 style="
    color: #004085;
    position: absolute;
    top: 180px;
    left: 700px;
    font-weight: 500;
    font-size: 48px;">โปรไฟล์ของฉัน</h1>


    <div class="card">
        <div class="watermark">PSU PSU PSU PSU <br><br> PSU PSU PSU PSU PSU PSU <br><br> PSU PSU PSU PSU PSU PSU</div>
        <div class="title">Virtual Teacher Card</div>
        <div class="card-info">
            <div class="card-title"><?php echo $row_user["fname"] ?> <?php echo $row_user["lname"] ?></div>
            <div class="card-title"><?php echo strtoupper($row_user["fname"] . ' ' . $row_user["lname"]) ?></div>
        </div>
        <div class="text"><?php echo $row_user["major"] ?></div>
        <div class="box_img">
            <img class="img1" src="\pub_teacher\front-app\Pic\logo1.png" alt="Logo">
        </div>
    </div>



    <div class="footer-card">
        <h2>วิทยาเขต หาดใหญ่</h2>
        <h3>สาขาวิชา : <?php echo $row_user["major"] ?> </h3>
    <div>

    <a href="\pub_teacher\front-app\user-role-index\teacher\edit-profile.php" class="edit" ><div class="fas fa-edit me-2"></div> แก้ไขข้อมูลส่วนตัว</a>

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
      <iframe src="/pub_teacher/front-app/UserGuide/guide.pdf" width="100%" height="800px" style="border:none;"></iframe>
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