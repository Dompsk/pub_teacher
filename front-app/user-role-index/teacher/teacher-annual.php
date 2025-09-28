
        <?php
        include($_SERVER['DOCUMENT_ROOT'] . "/condb.php");

        session_start();

        // ดึง username/password จาก session
        $current_username = $_SESSION['username'] ?? null;
        $current_password = $_SESSION['password'] ?? null;

        $current_user_acc = null;
        $current_user_id = null;

        // ดึงข้อมูลจาก Supabase
        $user_accs = getSupabaseData('user_acc'); // acc_id, username, password, user_id, type_id
        $account_types = getSupabaseData('account_type');
        $users = getSupabaseData('user');

        // map
        $account_type_map = array_column($account_types, null, 'type_id');
        $user_map = array_column($users, null, 'user_id');

        // หา account ปัจจุบัน
        foreach ($user_accs as $ua) {
            if ($ua['username'] === $current_username && $ua['password'] === $current_password) {
                $current_user_acc = $ua;
                $current_user_id = $ua['user_id'];
                break;

            }
        }

        // ดึง publication ของผู้ใช้ล็อกอินและ approve เท่านั้น
        $publications = getSupabaseData('publication'); // upload_date, c_id, acc_id, status
        $categories = [
            1 => "ระดับชาติ",
            2 => "ระดับนานาชาติ",
            3 => "วารสาร",
            4 => "ตำรา"
        ];

        $year_summary = [];

        if ($current_user_acc && $current_user_acc['type_id'] == 3) { // อาจารย์
            foreach ($publications as $pub) {
                if ($pub['status'] === 'approve' && $pub['acc_id'] == $current_user_acc['acc_id']) {
                    $year = substr($pub['upload_date'], 0, 4);
                    $cat_id = $pub['c_id'];

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
        }

        krsort($year_summary); // เรียงปีจากน้อยไปมาก
        ?>




    <!DOCTYPE html>
    <html lang="th">

    <head>
        <meta charset="UTF-8">
        <title>ระบบจัดเก็บผลงานตีพิมพ์</title>
        <link rel="stylesheet" href="teacher-annual.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    </head>

    <body>
        <header>
            <div class="header-container">
                <div class="logo-container">
                    <a href="index.html">
                        <img src="Pic/logo1.png" alt="logo">
                    </a>
                </div>
                <h1>ระบบจัดเก็บผลงานตีพิมพ์อาจารย์</h1>
            </div>
        </header>
        <nav class="nav">
            <ul>
                <?php

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
                <li><a href="/front-app/user-role-index/teacher/index-role-teacher.php"><i class="bi bi-house icon-large"></i> หน้าแรก</a></li>
                <li><a href="/front-app/user-role-index/teacher/profile-teacher.php"><i class="bi bi-person icon-large"></i> ข้อมูลส่วนตัว</a></li>

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
                            <th>ประจำปี</th>
                            <th>วิชาการระดับชาติ</th>
                            <th>วิชาการระดับนานาชาติ</th>
                            <th>วารสาร</th>
                            <th>ตำรา</th>
                            <th>รวมทั้งหมดประจำปี</th>
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
                            <tr>
                                <td colspan="6">ไม่พบข้อมูล</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

            </div>
        </main>

        <footer>
            <p>@มหาวิทยาลัย สงขลานครินทร์ วิทยาเขตหาดใหญ่. สมาชิก 143 251 252 253 254 325 378 </p>
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

