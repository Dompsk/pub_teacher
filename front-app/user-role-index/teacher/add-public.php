<?php
$SUPABASE_URL = "https://jibnhzwxuzoccvxhzqri.supabase.co";
$SUPABASE_KEY = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImppYm5oend4dXpvY2N2eGh6cXJpIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTgzNzg3MjMsImV4cCI6MjA3Mzk1NDcyM30.5rg489NwkhiVvkXI2Y5wJy56Ads9JjFVX6snArPlrPc";

session_start();
// ฟังก์ชัน Insert ข้อมูลเข้า Supabase
function insertSupabaseData($table, $data) {
    global $SUPABASE_URL, $SUPABASE_KEY;

    $url = $SUPABASE_URL . "/rest/v1/" . $table;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "apikey: $SUPABASE_KEY",
        "Authorization: Bearer $SUPABASE_KEY",
        "Content-Type: application/json",
        "Prefer: return=representation"
    ]);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        die("cURL Error: " . curl_error($ch));
    }
    curl_close($ch);

    return json_decode($response, true);
}

// ฟังก์ชันดึงข้อมูลจาก Supabase
function fetchSupabaseData($table, $query = "") {
    global $SUPABASE_URL, $SUPABASE_KEY;

    $url = $SUPABASE_URL . "/rest/v1/" . $table . "?select=*" . $query;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "apikey: $SUPABASE_KEY",
        "Authorization: Bearer $SUPABASE_KEY",
        "Content-Type: application/json"
    ]);
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        die("cURL Error: " . curl_error($ch));
    }
    curl_close($ch);

    return json_decode($response, true);
}

// ดึง category จาก Supabase
$categories = fetchSupabaseData("category");

// เมื่อกด submit ฟอร์มเพิ่มบทความ
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['save_pub'])) {
    $pub_name = $_POST['pub_name'];
    $c_id = (int)$_POST['c_id'];
    $upload_date = date("Y-m-d H:i:s");
    $status = "not approve";
    $acc_id = $_SESSION['id']; // สมมติล็อกอินเป็น user acc_id = 3

    // ดึง pub_id ล่าสุด
    $lastPub = fetchSupabaseData("publication", "&order=pub_id.desc&limit=1");
    $new_pub_id = ($lastPub && isset($lastPub[0]['pub_id'])) ? $lastPub[0]['pub_id'] + 1 : 1;

    // จัดการไฟล์
    $fileName = $_FILES['file']['name'] ?? null;
    if (!empty($fileName)) {
        $targetDir = $_SERVER['DOCUMENT_ROOT'] . "/pub_teacher/src/file_public/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
        move_uploaded_file($_FILES['file']['tmp_name'], $targetDir . $fileName);
    }

    //รูป
    $picName = $_FILES['pic']['name'] ?? null;
    if (!empty($picName)) {
        $targetDir = $_SERVER['DOCUMENT_ROOT'] . "/pub_teacher/src/pic_public/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
        move_uploaded_file($_FILES['pic']['tmp_name'], $targetDir . $picName);
    }

    // เตรียมข้อมูลสำหรับ Supabase
    $data = [
        "pub_id" => $new_pub_id,
        "pub_name" => $pub_name,
        "c_id" => $c_id,
        "file" => $fileName,
        "pic" => $picName,
        "upload_date" => $upload_date,
        "status" => $status,
        "acc_id" => $acc_id
    ];

    $result = insertSupabaseData("publication", $data);

    if ($result) {
        echo "<script>alert('บันทึกบทความเรียบร้อย'); window.location.href='index-role-teacher.php';</script>";
    } else {
        echo "<script>alert('ผิดพลาด! ไม่สามารถบันทึกได้');</script>";
    }
}
?>

<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <title>ระบบจัดเก็บผลงานตีพิมพ์</title>
  <link rel="stylesheet" href="add-public.css">
  <?php
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
                <a href="index-role-teacher.php">
                    <img src="/pub_teacher/front-app/Pic/logo1.png" alt="logo">
                </a>
            </div>
            <h1>เพิ่มบทความ</h1> 
        </div>
    </header>

    <main>
    <!-- ฟอร์มเพิ่มบทความ -->
    <form method="POST" enctype="multipart/form-data">

        <div class="box">
            <div>ชื่อบทความ :</div>
            <input type="text" name="pub_name" placeholder="กรอกชื่อบทความ" required>
            <br>

            <div>ประเภทบทความ :</div>
            <select name="c_id" required>
                <option value="" hidden>-- เลือกประเภทบทความ --</option>
                <?php 
                    // เรียง array ตาม c_id จากน้อยไปมาก
                    usort($categories, function($a, $b) {
                        return $a['c_id'] <=> $b['c_id'];
                    });

                    foreach($categories as $cat): 
                ?>
                    <option value="<?= $cat['c_id'] ?>">
                        <?= htmlspecialchars($cat['cname']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <br>

            <div>ไฟล์ :</div>
            <input class="file-input" type="file" name="file" accept=".pdf,.doc,.docx">
            <br>

            <div>รูปหน้าปก (มีหรือไม่มีก็ได้) :</div>
            <input class="file-input" type="file" name="pic">
        </div>

        <button type="button" class="btn btn-cancel" onclick="window.location.href='index-role-teacher.php'">ยกเลิก</button>
        <button type="submit" name="save_pub" class="btn btn-save">เพิ่มบทความ</button>
        
    </form>
    </main>

    <footer>
        <p>@มหาวิทยาลัย สงขลานครินทร์ วิทยาเขตหาดใหญ่. สมาชิก 143 251 253 254 325 378</p>
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
