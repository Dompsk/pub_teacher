
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="icon" href="/pub_teacher/front-app/Pic/logo3.png" type="image/png">


    <title>ระบบจัดเก็บผลงานตีพิมพ์</title>
    <link rel="stylesheet" href="index-role-staff.css">
</head>

<body>
    <header>

        <div class="header-container">

            <div class="logo-container">
                <a href="/pub_teacher/front-app/user-role-index/staff/index-role-staff.php">
                    <img src="/pub_teacher/front-app/Pic/logo1.png" alt="logo">
                </a>
            </div>
            <h1>ระบบจัดเก็บผลงานตีพิมพ์อาจารย์</h1>
        </div>

    </header>

    <nav class="nav">
        <ul>
            <?php
            include($_SERVER['DOCUMENT_ROOT'] . "/pub_teacher/condb.php");
            session_start(); // เริ่ม session

            // ดึง user_id ของผู้ใช้ปัจจุบันจาก session
            $current_username = $_SESSION['username'] ?? null;
            $current_password = $_SESSION['password'] ?? null;

            $row_user = null;

            if ($current_username && $current_password) {
                // ดึงข้อมูลจาก Supabase
                $users = getSupabaseData('user', [
                    'select' => ['user_id', 'fname', 'lname'],
                ]);
                $user_accs = getSupabaseData('user_acc',[
                    'select' => ['acc_id', 'user_id', 'type_id', 'username', 'password'],
                ]);
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
            <li><a href="/pub_teacher/front-app/user-role-index/staff/index-role-staff.php"><i class="bi bi-house icon-large"></i> หน้าแรก</a></li>
            <li><a href="/pub_teacher/front-app/user-role-index/staff/profile-staff.php"><i class="bi bi-person icon-large"></i> ข้อมูลส่วนตัว</a></li>

            <li>
                <a href="#" onclick="openModal()">
                    <i class="bi bi-gear icon-large"></i> คู่มือการใช้งาน
                </a>
            </li>
            <p></p>
            <li><a href="/pub_teacher/back-app/login-exit/logout.php"><i class="bi bi-box-arrow-right icon-large"></i> ออกจากระบบ</a></li>

        </ul>
    </nav>

    <main>
        <div class="main-wrapper">
            <div class="search-bar">
                <form action="#" method="GET" class="search-form">
                    <input type="text" name="q" placeholder="ค้นหาบทความ..." aria-label="Search">
                    <button type="submit"><i class="bi bi-search"></i></button>
                </form>
            </div>

            <div class="bar">
                <ul>

                    <li><a href="staff-annual.php"><i class="bi bi-journal-check icon-large"></i> จัดทำรายงานสรุป</a></li>

                    <li><a href="/pub_teacher/front-app/user-role-index/staff/verify-public.php"><i class="bi bi-search icon-large"></i> ตรวจสอบผลงานตีพิมพ์ </a></li>
                    <li><a href="#"><i class="bi bi-folder-check icon-large"></i> ประวัติจัดการผลงานตีพิมพ์</a></li>

                </ul>
            </div>

            <div class="content-container">
                <h2>บทความตีพิมพ์ล่าสุด</h2>
                <div class="articles-list-container">
                    <?php
                    // ดึงข้อมูลจาก Supabase
                    $publications = getSupabaseData('publication',[
                        'select' => ['pub_id', 'pub_name', 'pic', 'acc_id', 'c_id', 'upload_date', 'status'],
                    ]);
                    $users = getSupabaseData('user', [
                        'select' => ['user_id', 'fname', 'lname']
                    ]);
                    $user_accs = getSupabaseData('user_acc', [
                        'select' => ['acc_id', 'user_id', 'type_id', 'username', 'password']
                    ]);
                    $categories = getSupabaseData('category', [
                        'select' => ['c_id', 'cname']
                    ]);

                    // สร้าง map เพื่อค้นหาข้อมูลได้ง่าย
                    $user_map = array_column($users, null, 'user_id');
                    $user_acc_map = array_column($user_accs, null, 'acc_id');
                    $category_map = array_column($categories, null, 'c_id');

                    // กรองเฉพาะบทความที่ approved
                    $approved_publications = array_filter($publications, function ($pub) {
                        return $pub['status'] === 'approve';
                    });

                    // เรียงลำดับตามวันที่อัปโหลดล่าสุด
                    usort($approved_publications, function ($a, $b) {
                        return strtotime($b['upload_date']) - strtotime($a['upload_date']);
                    });

                    // ดึง 8 บทความล่าสุด
                    $recent_articles = $approved_publications;

                    if (!empty($recent_articles)) {
                        foreach ($recent_articles as $row) {
                            $acc_id = $row['acc_id'];
                            $c_id = $row['c_id'];

                            $user_id = $user_acc_map[$acc_id]['user_id'] ?? null;
                            $author = $user_map[$user_id] ?? null;
                            $category = $category_map[$c_id] ?? null;

                            $img = (!empty($row['pic']) && $row['pic'] !== null)
                                ? "/pub_teacher/src/pic_public/" . $row['pic']
                                : "/pub_teacher/front-app/Pic/bk1.jpg";
                    ?>
                            <div class="articles-list">
                                <div class="article-pic">
                                    <img src="<?php echo htmlspecialchars($img); ?>" alt="รูปบทความ">
                                </div>
                                <div class="article-text">
                                    <h3><?php echo htmlspecialchars($row['pub_name']); ?></h3>
                                    <p>ผู้แต่ง: <?php echo htmlspecialchars(($author['fname'] ?? '') . " " . ($author['lname'] ?? '')); ?></p>
                                    <p>หมวดหมู่: <?php echo htmlspecialchars($category['cname'] ?? ''); ?></p>
                                    <a href="detail.php?pub_id=<?php echo $row['pub_id']; ?>">อ่านเพิ่มเติม...</a>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        echo "<p>ยังไม่มีบทความ</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <p>@มหาวิทยาลัย สงขลานครินทร์ วิทยาเขตหาดใหญ่. สมาชิก 143 251 253 254 325 378 </p>
    </footer>

</body>

</html>

<!-- Modal หน้าต่าง popup -->
<div id="settingsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>คู่มือการใช้งาน</h2>
            <span class="close" onclick="closeModal()">&times;</span>
        </div>
        <div class="modal-body">
            <!-- แทนที่ form ด้วย iframe สำหรับ PDF -->
            <iframe src="/pub_teacher/front-app/UserGuide/guide.pdf" width="100%" height="800px" style="border:none;"></iframe>
        </div>
        <div class="modal-footer">
            <button class="btn cancel" onclick="closeModal()">ปิด</button>
        </div>
    </div>
</div>

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