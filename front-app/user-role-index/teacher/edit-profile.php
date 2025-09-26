<?php
include($_SERVER['DOCUMENT_ROOT'] . "/pub_teacher/condb.php");
session_start();

$current_username = $_SESSION['username'] ?? null;
$current_password = $_SESSION['password'] ?? null;

$row_user = null;

if ($current_username && $current_password) {
    $users = getSupabaseData('user');
    $user_accs = getSupabaseData('user_acc');
    $account_types = getSupabaseData('account_type');

    $user_map = array_column($users, null, 'user_id');
    $account_type_map = array_column($account_types, null, 'type_id');

    $current_acc = null;
    foreach ($user_accs as $ua) {
        if ($ua['username'] === $current_username && $ua['password'] === $current_password) {
            $current_acc = $ua;
            break;
        }
    }

    if ($current_acc) {
        $user_id = $current_acc['user_id'];
        $row_user = $user_map[$user_id] ?? null;
        $type_id = $current_acc['type_id'];
        $row_user['type_name'] = $account_type_map[$type_id]['type_name'] ?? '';
    }

    if (!$current_username || !$current_password) {
        die("กรุณาเข้าสู่ระบบก่อน");
    }
}

// ✅ อัปเดตข้อมูลเมื่อกด submit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $row_user) {
    $updated_data = [
        'fname' => !empty($_POST['fname']) ? $_POST['fname'] : $row_user['fname'],
        'lname' => !empty($_POST['lname']) ? $_POST['lname'] : $row_user['lname'],
        'fname_eng' => !empty($_POST['fname_eng']) ? $_POST['fname_eng'] : $row_user['fname_eng'],
        'lname_eng' => !empty($_POST['lname_eng']) ? $_POST['lname_eng'] : $row_user['lname_eng'],
        'major' => !empty($_POST['major']) ? $_POST['major'] : $row_user['major'],
        'tel' => !empty($_POST['tel']) ? $_POST['tel'] : $row_user['tel'],
        'age' => !empty($_POST['age']) ? $_POST['age'] : $row_user['age'],
    ];

    // ✅ อัปโหลดไฟล์รูปใหม่
    if (!empty($_FILES['pic']['name'])) {
        $upload_dir = $_SERVER['DOCUMENT_ROOT'] . "/pub_teacher/src/pic_user/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_name = time() . "_" . basename($_FILES["pic"]["name"]);
        $target_file = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES["pic"]["tmp_name"], $target_file)) {
            $updated_data['pic'] = $file_name;
        }
    }

    $result = updateSupabaseData('user', $updated_data, 'user_id', $user_id);

    if (!empty($result)) {
        header("Location: profile-teacher.php");
        exit();
    } else {
        echo "เกิดข้อผิดพลาดในการอัปเดตข้อมูล";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลส่วนตัว</title>
    <link rel="stylesheet" href="edit-profile.css">
    <style>
        .form-container { max-width: 600px; margin: 20px auto; padding: 20px; background: #fff; border-radius: 10px; }
        .form-container label { display: block; margin-top: 10px; font-weight: bold; }
        .form-container input { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px; }
        .form-container button { margin-top: 15px; padding: 10px; width: 100%; background: #007bff; color: #fff; border: none; border-radius: 5px; }
        .form-container button:hover { background: #0056b3; }
        .preview-img { margin-top: 10px; max-height: 150px; border-radius: 10px; }
    </style>
</head>
<body>

<div class="form-container">
    <h1>แก้ไขข้อมูลส่วนตัว</h1>

    <?php if ($row_user): ?>
        <form method="post" enctype="multipart/form-data">
            <label>ชื่อ</label>
            <input type="text" name="fname" value="<?php echo htmlspecialchars($row_user['fname']); ?>">

            <label>นามสกุล</label>
            <input type="text" name="lname" value="<?php echo htmlspecialchars($row_user['lname']); ?>">

            <label>Firstname (อังกฤษ)</label>
            <input type="text" name="fname_eng" value="<?php echo htmlspecialchars($row_user['fname_eng']); ?>">

            <label>Lastname (อังกฤษ)</label>
            <input type="text" name="lname_eng" value="<?php echo htmlspecialchars($row_user['lname_eng']); ?>">

            <label>สาขา</label>
            <input type="text" name="major" value="<?php echo htmlspecialchars($row_user['major']); ?>">

            <label>เบอร์โทร</label>
            <input type="text" name="tel" value="<?php echo htmlspecialchars($row_user['tel']); ?>">

            <label>อายุ</label>
            <input type="text" name="age" value="<?php echo htmlspecialchars($row_user['age']); ?>">

            <!-- ✅ อัปโหลดรูป -->
            <label>รูปโปรไฟล์ปัจจุบัน</label><br>
            <img src="/pub_teacher/src/pic_user/<?php echo htmlspecialchars($row_user['pic'] ?? 'df.png'); ?>" class="preview-img" id="currentPic">

            <label>เปลี่ยนรูปโปรไฟล์</label>
            <input type="file" name="pic" accept="image/*" onchange="previewImage(event)">
            <img id="preview" class="preview-img" style="display:none;">

            <button type="submit">บันทึกข้อมูล</button>
        </form>
    <?php else: ?>
        <p>ไม่พบข้อมูลผู้ใช้</p>
    <?php endif; ?>
</div>

<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById('preview');
        output.src = reader.result;
        output.style.display = 'block';
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>

</body>
</html>
