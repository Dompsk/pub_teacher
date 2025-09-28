<?php
include($_SERVER['DOCUMENT_ROOT'] . "/pub_teacher/condb.php");

// ดึงข้อมูลผู้ใช้ทั้งหมด
$user = getSupabaseData('user');
$account_type = getSupabaseData('account_type');
$login_log = getSupabaseData('login_log');
$user_acc = getSupabaseData('user_acc');

// ตรวจสอบว่ามีข้อมูลหรือไม่
$user = is_array($user) ? $user : [];
$account_type = is_array($account_type) ? $account_type : [];
$login_log = is_array($login_log) ? $login_log : [];
$user_acc = is_array($user_acc) ? $user_acc : [];

// สร้าง map: user_id => user data
$userMap = [];
foreach ($user as $u) {
    $userMap[$u['user_id']] = $u;
}

// สร้าง map: type_id => account_type data
$accMap = [];
foreach ($account_type as $acc) {
    $accMap[$acc['type_id']] = $acc;
}

// map: acc_id => user_id (จาก user_acc)
$userAccMap = [];
foreach ($user_acc as $ua) {
    if (isset($ua['acc_id'], $ua['user_id'])) {
        $userAccMap[$ua['acc_id']] = $ua['user_id'];
    }
}

// เวลาใหม่สุด -> เก่าสุด
usort($login_log, function ($a, $b) {
    return strtotime($b['login_time']) <=> strtotime($a['login_time']);
});
?>

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
                <a href="\pub_teacher\front-app\user-role-index\admin\index-role-admin.php">
                    <img src="\pub_teacher\front-app\Pic\logo1.png" alt="logo">
                </a>
            </div>
            <h1>ประวัติการเข้าสู่ระบบ</h1>
        </div>
    </header>

    <main>
        <button class="btn" onclick="window.history.back()">ย้อนกลับ</button>

        <div style="overflow-x:auto; max-width:100%;">
            <table>
                <thead>
                    <tr>
                        <th style="width: 1px;">ID</th>
                        <th style="width: 100px;">Name</th>
                        <th style="width: 80px;">Phone</th>
                        <th style="width: 80px;">Mail</th>
                        <th style="width: 50px;">Role</th>
                        <th style="width: 150px;">Date&Time</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $no = 1;

                    // สร้าง map: acc_id => type_id
                    $accTypeMap = [];
                    foreach ($user_acc as $ua) {
                        if (isset($ua['acc_id'], $ua['type_id'])) {
                            $accTypeMap[$ua['acc_id']] = $ua['type_id'];
                        }
                    }

                    foreach ($login_log as $log):
                        $accId = $log['acc_id'] ?? null;

                        // ดึง user
                        $userId = $accId && isset($userAccMap[$accId]) ? $userAccMap[$accId] : null;
                        $u = $userId && isset($userMap[$userId]) ? $userMap[$userId] : null;

                        // ดึง role
                        $typeId = $accId && isset($accTypeMap[$accId]) ? $accTypeMap[$accId] : null;
                        $acc = $typeId && isset($accMap[$typeId]) ? $accMap[$typeId] : null;
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars(($u['fname'] ?? '-') . ' ' . ($u['lname'] ?? '-')); ?></td>
                            <td><?php echo htmlspecialchars($u['tel'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($u['email'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($acc['type_name'] ?? '-'); ?></td>
                            <td>
                                <?php
                                if (!empty($log['login_time'])) {
                                    try {
                                        $dt = new DateTime($log['login_time'], new DateTimeZone("UTC"));
                                        $dt->setTimezone(new DateTimeZone("Asia/Bangkok"));
                                        echo htmlspecialchars($dt->format("Y-m-d H:i:s"));
                                    } catch (Exception $e) {
                                        echo '-';
                                    }
                                } else {
                                    echo '-';
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

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