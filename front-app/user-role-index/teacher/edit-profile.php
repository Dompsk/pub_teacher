<?php
include($_SERVER['DOCUMENT_ROOT'] . "/pub_teacher/condb.php");
session_start();

$current_username = $_SESSION['username'] ?? null;
$current_password = $_SESSION['password'] ?? null;

$row_user = null;

if ($current_username && $current_password) {
    // ดึงข้อมูลจาก Supabase
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

// หากมีการส่งฟอร์มบันทึกข้อมูล
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


    // อัปเดตข้อมูลใน Supabase
    $result = updateSupabaseData('user', $updated_data, 'user_id', $user_id);

   if (!empty($result)) {
        header("Location: profile-teacher.php");
        exit();
    } else {
        echo "เกิดข้อผิดพลาดในการอัปเดตข้อมูล";
    }
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>ระบบจัดเก็บผลงานตีพิมพ์</title>
    <link rel="stylesheet" href="edit-profile.css">
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
            <?php

                // ดึง username/password จาก session
                $current_username = $_SESSION['username'] ?? null;
                $current_password = $_SESSION['password'] ?? null;

                $row_user = null;
                $pic_path = "/pub_teacher/src/pic_user/df.png"; // default image

                if ($current_username && $current_password) {
                    // ดึงข้อมูลจาก Supabase
                    $users = getSupabaseData('user');
                    $user_accs = getSupabaseData('user_acc');
                    $account_types = getSupabaseData('account_type');

                    // map สำหรับค้นหาข้อมูลง่าย
                    $user_map = array_column($users, null, 'user_id');
                    $user_acc_map = array_column($user_accs, null, 'acc_id');
                    $account_type_map = array_column($account_types, null, 'type_id');

                    // ค้นหา account ที่ตรงกับ username + password
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

                        // ตรวจสอบรูปผู้ใช้
                        if (!empty($row_user['pic'])) {
                            $pic_path = "/pub_teacher/src/pic_user/" . htmlspecialchars($row_user['pic']);
                        }
                    }
                }
            ?>


            <div class="profile">
                <div class="profile-info">
                    <?php if ($row_user): ?>
                        <h3>ชื่อ-นามสกุล</h3>
                        <p><?php echo htmlspecialchars($row_user["fname"] . ' ' . $row_user["lname"]); ?></p>
                        <p>ตำแหน่ง: <?php echo htmlspecialchars($row_user["type_name"]); ?></p>
                        <p>สาขา: <?php echo htmlspecialchars($row_user["major"]); ?></p>
                    <?php else: ?>
                        <p>ไม่พบข้อมูลผู้ใช้</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="line"></div>
              <li><a href="/pub_teacher/front-app/user-role-index/teacher/index-role-teacher.php"><i class="bi bi-house icon-large"></i> หน้าแรก</a></li>
              <li><a href="/pub_teacher/front-app/user-role-index/teacher/profile-teacher.php"><i class="bi bi-person icon-large"></i> ข้อมูลส่วนตัว</a></li>

            <li>
                <a href="#" onclick="openModal()">
                    <i class="bi bi-gear icon-large"></i> คู่มือการใช้งาน
                </a>
            </li>
                <p></p>
            <li><a href="/pub_teacher/back-app/login-exit/logout.php"><i class="bi bi-box-arrow-right icon-large"></i> ออกจากระบบ</a></li>
            
        </ul>
   </nav>
          <div class="form-container">
            <h1>แก้ไขข้อมูลส่วนตัว</h1>

              <?php if ($row_user): ?>
                <form method="post">
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

                    <button type="submit">บันทึกข้อมูล</button>
                </form>
                
              <?php else: ?>
                  <p>ไม่พบข้อมูลผู้ใช้</p>
              <?php endif; ?>

          </div>
              
    <footer>
        <p>@มหาวิทยาลัย สงขลานครินทร์ วิทยาเขตหาดใหญ่. สมาชิก 143 251 253 254 325 378 </p>
    </footer>
        </body>
</html>
