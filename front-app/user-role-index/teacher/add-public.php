
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <title>ระบบจัดเก็บผลงานตีพิมพ์</title>
    <link rel="stylesheet" href="add-public.css">
</head>
<body>
    <header>

        <div class="header-container">
            
                <div class="logo-container">
                    <a href="index.html">
                        <img src="/pro_teacher/front-app/Pic/logo1.png" alt="logo">
                    </a>
                </div>
            <h1 >เพิ่มบทความ</h1> 
        </div>

    </header>

    <main>
      
    <div class="box">
      <div>ชื่อบทความ :</div>
      <input type="text">

      <br>

      <div>ประเภทบทความ :</div>
      <select>
        <option value="">-- ผลงานตีพิมพ์ในที่ประชุมวิชาการระดับชาติ --</option>
        <option value="news">ผลงานตีพิมพ์ในที่ประชุมวิชาการระดับนานาชาติ</option>
        <option value="review">วารสาร</option>
        <option value="tutorial">ตำรา</option>
        <option value="opinion">อื่นๆ</option>
      </select>

      <br>

      <div>ไฟล์ :</div>
      <input class="file-input" type="file" name="" accept=".pdf,.doc,.docx">

      <br>

      <div>รูปหน้าปก (มีหรือไม่มีก็ได้) :</div>
      <input class="file-input" type="file" name="">

    </div>
   
    <button class="btn btn-cancel">ยกเลิก</button>
    <button class="btn btn-save">เพิ่มบทความ</button>

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