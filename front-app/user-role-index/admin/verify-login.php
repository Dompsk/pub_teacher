
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <title>ระบบจัดเก็บผลงานตีพิมพ์</title>
    <link rel="stylesheet" href="verify-login.css">
</head>
<body>
    <header>

        <div class="header-container">
            
                <div class="logo-container">
                    <a href="index.html">
                        <img src="/pro_teacher/front-app/Pic/logo1.png" alt="logo">
                    </a>
                </div>
            <h1 >ประวัติการเข้าสู่ระบบ</h1> 
        </div>

    </header>

    <main>

    <button class="btn">ย้อนกลับ</button>
      
    <div style="overflow-x:auto; max-width:100%;">
    <table>
        <thead>
        <tr>
            <th style="width: 1px;">ID</th>
            <th style="width: 100px;">Name</th>
            <th style="width: 80px;">Phone</th>
            <th style="width: 80px;">Mail</th>
            <th style="width: 50px;">Role</th>
            <th style="width: 50px;">Date&Time</th>
        </tr>
        </thead>

    </table>

        
    </div>
    
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