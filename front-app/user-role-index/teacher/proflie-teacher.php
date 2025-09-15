<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <title>ระบบจัดเก็บผลงานตีพิมพ์</title>
    <link rel="stylesheet" href="profile-teacher.css">
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
                    <h3>ชื่อ-นามสกุล</h3>
                    <p>นาย พงศกร จรัญรักษ์</p>
                    <p>ตำแหน่ง: อาจารย์</p>
                    <p>สาขา: วิทยาการคอมพิวเตอร์</p>
                </div>
            </div>
            <div class="line"></div>
            <li><a href="index.html"><i class="bi bi-house icon-large"></i> หน้าแรก</a></li>
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
    left: 700px;">โปรไฟล์ของฉัน</h1>

    <div class="card">
      <div class="title">Virtual Teacher Card</div>
    <div class="card-info">
      <div class="card-title">พงศกร ปานถาวร</div>
      <div class="card-title">PONGSAKORN PANTHAWORN</div>
      <div class="card-title">6610210252</div>
    </div>
    <div class="text">คณะวิทยาศาสตร์</div>
    <img class="img" src="" alt="">
    <div class="boximg">
       <img class="img1" src="\pub_teacher\front-app\Pic\logo1.png">
    </div>
    </div>

    <div class="x">
        <div>วิทยาเขต</div>
        <div>คณะ</div>
        <div>ภาควิชา</div>
        <div>สาขาวิชา</div>
    <div>

    <a href="\pub_teacher\front-app\user-role-index\teacher\edit-profile.php" class="edit">แก้ไขข้อมูลส่วนตัว</a>

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