<?php
     include($_SERVER['DOCUMENT_ROOT'] . "/condb.php");
?>

<?php
// ดึงข้อมูลจาก Supabase
$api_url = "https://jibnhzwxuzoccvxhzqri.supabase.co/rest/v1/publication?select=upload_date,c_id";
$api_key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImppYm5oend4dXpvY2N2eGh6cXJpIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTgzNzg3MjMsImV4cCI6MjA3Mzk1NDcyM30.5rg489NwkhiVvkXI2Y5wJy56Ads9JjFVX6snArPlrPc";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "apikey: $api_key",
    "Authorization: Bearer $api_key",
    "Content-Type: application/json"
));
$response = curl_exec($ch);
curl_close($ch);

// แปลง JSON เป็น array
$data = json_decode($response, true);

// ประเภทบทความ
$categories = [
    1 => "ระดับชาติ",
    2 => "ระดับนานาชาติ",
    3 => "วารสาร",
    4 => "ตำรา"
];

// เตรียมข้อมูลสรุป
$year_summary = [];

if ($data) {
    foreach ($data as $row) {
        if (!empty($row['upload_date']) && !empty($row['c_id'])) {
            $year = substr($row['upload_date'], 0, 4);
            $cat_id = $row['c_id'];

            if (!isset($year_summary[$year])) {
                $year_summary[$year] = [
                    "ระดับชาติ" => 0,
                    "ระดับนานาชาติ" => 0,
                    "วารสาร" => 0,
                    "ตำรา" => 0,
                    "รวม" => 0
                ];
            }

            if (isset($categories[$cat_id])) {
                $label = $categories[$cat_id];
                $year_summary[$year][$label]++;
                $year_summary[$year]["รวม"]++;
            }
        }
    }

    krsort($year_summary); // เรียงปี
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <title>ระบบจัดเก็บผลงานตีพิมพ์</title>
    <link rel="stylesheet" href="staff-annual.css">
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo-container">
                <a href="#">
                    <img src="Pic/logo1.png" alt="logo">
                </a>
            </div>
            <h1>ระบบจัดเก็บผลงานตีพิมพ์อาจารย์</h1> 
        </div>
    </header>

     <nav class="nav">
        <ul>
           <?php

            session_start();

            // ดึง username/password จาก session
            $current_username = $_SESSION['username'] ?? null;
            $current_password = $_SESSION['password'] ?? null;

            $row_user = null;
            $pic_path = "/src/pic_user/df.png"; // default image

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
                        $pic_path = "/src/pic_user/" . htmlspecialchars($row_user['pic']);
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
            <li><a href="/front-app/user-role-index/staff/index-role-staff.php"><i class="bi bi-house icon-large"></i> หน้าแรก</a></li>
            <li><a href="/front-app/user-role-index/staff/profile-staff.php"><i class="bi bi-person icon-large"></i> ข้อมูลส่วนตัว</a></li>

            <li>
                <a href="#" onclick="openModal()">
                    <i class="bi bi-gear icon-large"></i> คู่มือการใช้งาน
                </a>
            </li>
            <p></p>
            <li><a href="/back-app/login-exit/logout.php"><i class="bi bi-box-arrow-right icon-large"></i> ออกจากระบบ</a></li>

        </ul>
    </nav>

    <main>
        <div style="overflow-x:auto; max-width:100%;">
            <table>
                <thead>
                    <tr>
                        <th style="width: 30px;">ประจำปี</th>
                        <th style="width: 30px;">วิชาการระดับชาติ</th>
                        <th style="width: 30px;">วิชาการระดับนานาชาติ</th>
                        <th style="width: 30px;">วารสาร</th>
                        <th style="width: 30px;">ตำรา</th>
                        <th style="width: 150px;">รวมทั้งหมดประจำปี</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($year_summary)): ?>
                        <?php foreach ($year_summary as $year => $counts): ?>
                            <tr>
                                <td><?= htmlspecialchars($year) ?></td>
                                <td><?= $counts["ระดับชาติ"] ?></td>
                                <td><?= $counts["ระดับนานาชาติ"] ?></td>
                                <td><?= $counts["วารสาร"] ?></td>
                                <td><?= $counts["ตำรา"] ?></td>
                                <td><?= $counts["รวม"] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6">ไม่พบข้อมูล</td></tr>
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
