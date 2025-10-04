<?php
include($_SERVER['DOCUMENT_ROOT'] . "/pub_teacher/condb.php");

// ดึงข้อมูล
$history = getSupabaseData('history');
$publication = getSupabaseData('publication');
$user = getSupabaseData('user');
$user_acc = getSupabaseData('user_acc');

$history = is_array($history) ? $history : [];
$publication = is_array($publication) ? $publication : [];
$user = is_array($user) ? $user : [];
$user_acc = is_array($user_acc) ? $user_acc : [];

// map user_id => user
$userMap = [];
foreach ($user as $u) {
    $userMap[$u['user_id']] = $u;
}

// map pub_id => publication
$pubMap = [];
foreach ($publication as $pub) {
    $pubMap[$pub['pub_id']] = $pub;
}

// map acc_id => user_id
$userAccMap = [];
foreach ($user_acc as $ua) {
    if (isset($ua['acc_id'], $ua['user_id'])) {
        $userAccMap[$ua['acc_id']] = $ua['user_id'];
    }
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <title>ระบบจัดเก็บผลงานตีพิมพ์</title>
    <link rel="stylesheet" href="manage-public.css">
</head>

<body>
    <header>
        <div class="header-container">
            <div class="logo-container">
                <a href="/pub_teacher/front-app/user-role-index/staff/index-role-staff.php">
                    <img src="\pub_teacher\front-app\Pic\logo1.png" alt="logo">
                </a>
            </div>
            <h1>ประวัติการจัดการผลงานตีพิมพ์</h1>
        </div>
    </header>

    <main>
        <a href="/pub_teacher/front-app/user-role-index/staff/index-role-staff.php">
            <button class="btn">ย้อนกลับ</button>
        </a>

        <divstyle="overflow-x:auto; max-width:100%;">
            <table>
                <thead>
                    <tr>
                        <th style="width: 1px;">Publication Name</th>
                        <th style="width: 120px;">Author's Name</th>
                        <th style="width: 80px;">Date & Time</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($history as $h):
                        // 1. ดึง publication
                        $pubId = $h['pub_id'] ?? null;
                        $p = $pubId && isset($pubMap[$pubId]) ? $pubMap[$pubId] : null;

                        // 2. ดึง acc_id จาก publication
                        $accId = $p['acc_id'] ?? null;

                        // 3. ดึง user_id จาก user_acc
                        $userId = $accId && isset($userAccMap[$accId]) ? $userAccMap[$accId] : null;

                        // 4. ดึง user
                        $u = $userId && isset($userMap[$userId]) ? $userMap[$userId] : null;
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($p['pub_name'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($u['fname'] ?? '-') . ' ' . htmlspecialchars($u['lname'] ?? '-'); ?> </td>
                            <td>
                                <?php
                                if (!empty($h['edit_time'])) {
                                    try {
                                        $dt = new DateTime($h['edit_time'], new DateTimeZone("UTC"));
                                        $dt->setTimezone(new DateTimeZone("Asia/Bangkok"));
                                        $dt = new DateTime("now", new DateTimeZone('Asia/Bangkok'));
                                        echo htmlspecialchars($dt->format("d/m/Y H:i:s"));
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
            </divstyle=>
    </main>

    <footer>
        <p>@มหาวิทยาลัย สงขลานครินทร์ วิทยาเขตหาดใหญ่. สมาชิก 143 251 253 254 325 378 </p>
    </footer>
</body>

</html>