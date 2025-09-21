<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <title>ระบบจัดเก็บผลงานตีพิมพ์</title>
  <link rel="stylesheet" href="add-public.css">
  <?php
  session_start();
  $con = new mysqli("localhost", "root", "", "public_teacher");

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pub_name = $_POST['pub_name'];
    $btn = $_POST['btn'];
    if ($btn == "เพิ่มบทความ") {
      
    }
    else {
      echo "<script> window.location='/pub_teacher/front-app/user-role-index/teacher/public.php'; </script>";
    }

  }
  ?>
</head>

<body>
  <header>

    <div class="header-container">

      <div class="logo-container">
        <a href="\pub_teacher\front-app\user-role-index\teacher\index-role-teacher.php">
          <img src="/pub_teacher/front-app/Pic/logo1.png" alt="logo">
        </a>
      </div>
      <h1>เพิ่มบทความ</h1>
    </div>

  </header>

  <main>
    <form action="" method="post" enctype="multipart/form-data">
      <div class="box">
        <div>ชื่อบทความ :</div>
        <input type="text" name="pub_name" placeholder="กรอกชื่อบทความ">

        <br>

        <div>ประเภทบทความ :</div>
        <select>
          <option value="" hidden>-- เลือกประเภทบทความ --</option>
          <option value="1">ผลงานตีพิมพ์ในที่ประชุมวิชาการระดับชาติ</option>
          <option value="2">ผลงานตีพิมพ์ในที่ประชุมวิชาการระดับนานาชาติ</option>
          <option value="3">วารสาร</option>
          <option value="4">ตำรา</option>
          <option value="5">อื่นๆ</option>
        </select>

        <br>

        <div>ไฟล์ :</div>
        <input class="file-input" type="file" name="" accept=".pdf,.doc,.docx">

        <br>

        <div>รูปหน้าปก (มีหรือไม่มีก็ได้) :</div>
        <input class="file-input" type="file" name="">

      </div>
    <input type="submit" class="btn btn-cancel" value="ยกเลิก" name="btn">
    <input type="submit" class="btn btn-save" value="เพิ่มบทความ" name="btn">
    </form>
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