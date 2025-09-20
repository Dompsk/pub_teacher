
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <title>ระบบจัดเก็บผลงานตีพิมพ์</title>
    <link rel="stylesheet" href="edit-user.css">
</head>
<body>
    <header>

        <div class="header-container">
            
                <div class="logo-container">
                    <a href="\pub_teacher\front-app\user-role-index\teacher\index-role-teacher.php">
                        <img src="/pub_teacher/front-app/Pic/logo1.png" alt="logo">
                    </a>
                </div>
            <h1 >แก้ไขรายชื่อผู้ใช้</h1> 
        </div>

    </header>

    <main>
    
    <?php

    ?>

    <div class="box">
      <div>ID :<span style="color: red;">*</span></div>
      <input type="number" name="" >
    
      <br>

      <div>Name :<span style="color: red;">*</span></div>
      <input type="text" name="" >

      <br>

      <div>Phone :<span style="color: red;">*</span></div>
      <input type="text" name="" >

      <br>

      <div>Mail :<span style="color: red;">*</span></div>
      <input type="text" name="" >

      <br>

      <div>Role :<span style="color: red;">*</span></div>
      <input type="text" name="" >

    </div>
   
    <button class="btn btn-cancel">ยกเลิก</button>
    <button class="btn btn-save">ยืนยันการแก้ไข</button>

    </main>

    <footer>
        <p>@มหาวิทยาลัย สงขลานครินทร์ วิทยาเขตหาดใหญ่. สมาชิก 143 251 253 254 325 378 </p>
    </footer>

</body>
</html>


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