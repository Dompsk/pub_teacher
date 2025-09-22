<?php
include($_SERVER['DOCUMENT_ROOT'] . "/pub_teacher/condb.php");
    // ดึงข้อมูลผู้ใช้ทั้งหมด
    $users = getSupabaseData('user');

    // ตรวจสอบว่ามีข้อมูลหรือไม่
    $users = is_array($users) ? $users : [];

    // เรียง user_id จากน้อยไปมาก
    usort($users, function($a, $b) {
        return $a['user_id'] <=> $b['user_id'];
    });
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <title>ระบบจัดการผู้ใช้</title>
    <link rel="stylesheet" href="manage-user.css">
    <link rel="icon" href="/pub_teacher/front-app/Pic/logo3.png" type="image/png">
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo-container">
                <a href="/pub_teacher/front-app/user-role-index/teacher/index-role-teacher.php">
                    <img src="/pub_teacher/front-app/Pic/logo1.png" alt="logo">
                </a>
            </div>
            <h1>ระบบจัดการผู้ใช้ของแอดมิน</h1>
        </div>
    </header>

    <main>
        <a href="/pub_teacher/front-app/user-role-index/admin/index-role-admin.php">
            <button class="btn">ย้อนกลับ</button>
        </a>

        <div style="overflow-x:auto; max-width:100%;">
            <table>
                <thead>
                    <tr style="height: 70px;">
                        <th>ID</th>
                        <th>ชื่อ-นามสกุล</th>
                        <th>เบอร์โทร</th>
                        <th>อายุ</th>
                        <th>สาขา</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                            <td><?php echo htmlspecialchars($user['fname'] . ' ' . $user['lname']); ?></td>
                            <td><?php echo htmlspecialchars($user['tel']); ?></td>
                            <td><?php echo htmlspecialchars($user['age']); ?></td>
                            <td><?php echo htmlspecialchars($user['major']); ?></td>
                       <td>
                            <form method="post" action="edit-user.php" style="display:inline;">
                                <input type="hidden" name="edit_user_id" value="<?php echo $user['user_id']; ?>">
                                <button type="submit">แก้ไข</button>
                            </form>
                        </td>
                        <td>
                            <form method="post" action="delete-user.php" style="display:inline;" onsubmit="return confirm('ยืนยันการลบ?');">
                                <input type="hidden" name="delete_user_id" value="<?php echo $user['user_id']; ?>">
                                <button type="submit">ลบ</button>
                            </form>
                        </td>

                        </tr>
                    <?php endforeach; ?>

                    <?php if(empty($users)): ?>
                        <tr>
                            <td colspan="7" style="text-align:center;">ไม่มีข้อมูลผู้ใช้</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <footer>
        <p>@มหาวิทยาลัย สงขลานครินทร์ วิทยาเขตหาดใหญ่. สมาชิก 143 251 253 254 325 378 </p>
    </footer>
</body>
</html>
