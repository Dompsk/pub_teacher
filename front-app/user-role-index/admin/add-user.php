<?php
$SUPABASE_URL = "https://jibnhzwxuzoccvxhzqri.supabase.co";
$SUPABASE_KEY = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImppYm5oend4dXpvY2N2eGh6cXJpIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTgzNzg3MjMsImV4cCI6MjA3Mzk1NDcyM30.5rg489NwkhiVvkXI2Y5wJy56Ads9JjFVX6snArPlrPc";

session_start();

// ฟังก์ชัน Insert ข้อมูลเข้า Supabase
function insertSupabaseData($table, $data)
{
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
function fetchSupabaseData($table, $query = "")
{
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

// เมื่อกด submit ฟอร์มเพิ่มผู้ใช้
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['save_user'])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $age = $_POST['age'];
    $role = $_POST['role'];
    $tel = $_POST['phone'];
    $email = $_POST['email'];
    $faculty = $_POST['faculty'];
    $major = $_POST['major'];
    $fname_eng = $_POST['fname_eng'];
    $lname_eng = $_POST['lname_eng'];
    $upload_date = date("Y-m-d H:i:s");

    // map role -> type_id (คุณต้องปรับให้ตรงกับตาราง account_type ของคุณ)
    $role_map = [
        "แอดมิน" => 1,
        "เจ้าหน้าที่" => 2,
        "อาจารย์" => 3
    ];
    $type_id = $role_map[$role] ?? 1;

    // หา user_id ล่าสุด
    $lastuser = fetchSupabaseData("user", "&order=user_id.desc&limit=1");
    $new_user_id = ($lastuser && isset($lastuser[0]['user_id'])) ? $lastuser[0]['user_id'] + 1 : 1;

    // อัปโหลดรูป
    $picName = $_FILES['image']['name'] ?? null;
    if (!empty($picName)) {
        $targetDir = $_SERVER['DOCUMENT_ROOT'] . "/pub_teacher/src/pic_user/";
        if (!is_dir($targetDir))
            mkdir($targetDir, 0755, true);
        move_uploaded_file($_FILES['image']['tmp_name'], $targetDir . $picName);
    }

    // เตรียมข้อมูลส่งเข้า Supabase
    $data = [
        "user_id" => $new_user_id,
        "fname" => $fname,
        "lname" => $lname,
        "age" => $age,
        "tel" => $tel,
        "email" => $email,
        "faculty" => $faculty,
        "major" => $major,
        "fname_eng" => $fname_eng,
        "lname_eng" => $lname_eng,
        "pic" => $picName ?? null
    ];

    $result = insertSupabaseData("user", $data);

    if ($result) {
        echo "<script>
            alert('เพิ่มผู้ใช้เรียบร้อย');
            window.location.href='\pub_teacher\front-app\user-role-index\admin\manage-user.php';
        </script>";
    } else {
        echo "<script>
            alert('ผิดพลาด! ไม่สามารถบันทึกได้');
            window.history.back();
        </script>";
    }
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <title>ระบบจัดเก็บผลงานตีพิมพ์</title>
    <link rel="stylesheet" href="add-user.css">
    <link rel="icon" href="/pub_teacher/front-app/Pic/logo3.png" type="image/png">

</head>

<body>
    <header>

        <div class="header-container">

            <div class="logo-container">
                <a href="/pub_teacher/front-app/user-role-index/admin/index-role-admin.php">
                    <img src="/pub_teacher/front-app/pic/logo1.png" alt="logo">
                </a>
            </div>
            <h1>ระบบจัดเก็บผลงานตีพิมพ์อาจารย์</h1>
        </div>

    </header>

    <main>
        <form method="POST" enctype="multipart/form-data">
            <div class="box">
                <div>ชื่อผู้ใช้ :</div>
                <input type="text" name="fname" required>

                <div>นามสกุล :</div>
                <input type="text" name="lname" required>

                <div>Name :</div>
                <input type="text" name="fname_eng" required>

                <div>Lastname :</div>
                <input type="text" name="lname_eng" required>

                <div>อายุ :</div>
                <input type="number" name="age" required>

                <div>ประเภทผู้ใช้ :</div>
                <select name="role" required>
                    <option value="" disabled selected>กรุณาเลือกประเภทผู้ใช้</option>
                    <option value="อาจารย์">อาจารย์</option>
                    <option value="เจ้าหน้าที่">เจ้าหน้าที่</option>
                    <option value="แอดมิน">แอดมิน</option>
                </select>

                <div>เบอร์โทรศัพท์ :</div>
                <input type="text" name="phone" required>

                <div>อีเมล :</div>
                <input type="email" name="email" required>

                <div>คณะ :</div>
                <input type="text" name="faculty" required>

                <div>สาขา :</div>
                <input type="text" name="major" required>

                <div>รูป :</div>
                <input type="file" name="image" accept="image/*">

                <div class="button-group">
                    <button type="button" class="btn-cancel"
                        onclick="window.location.href='index-role-admin.php'">ยกเลิก</button>
                    <button type="submit" class="btn-save" name="save_user">เพิ่มผู้ใช้</button>
                </div>
            </div>
        </form>
    </main>
    <footer>
        <p>@มหาวิทยาลัย สงขลานครินทร์ วิทยาเขตหาดใหญ่. สมาชิก 143 251 253 254 325 378 </p>
    </footer>
</body>

</html>