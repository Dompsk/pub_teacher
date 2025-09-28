<?php
include("../condb.php");
session_start();
// รับ pub_id จาก query string
$pub_id = $_GET['pub_id'] ?? null;

$publications = getSupabaseData('publication');
$users = getSupabaseData('user');
$user_accs = getSupabaseData('user_acc');
$categories = getSupabaseData('category');

// map ข้อมูล
$user_map = array_column($users, null, 'user_id');
$user_acc_map = array_column($user_accs, null, 'acc_id');
$category_map = array_column($categories, null, 'c_id');

// หาบทความที่ตรงกับ pub_id
$article = null;
foreach ($publications as $pub) {
    if ($pub['pub_id'] == $pub_id && $pub['status'] === 'approve') {
        $article = $pub;
        break;
    }
}
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายละเอียดบทความ</title>
    <link rel="stylesheet" href="detail.css">
    <link rel="icon" href="/front-app/Pic/logo3.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .pdf-viewer {
            display: flex;
            justify-content: center;
            margin: 20px auto;
            width: 80%;
            height: 800px;
            border: 2px solid #004085;
            border-radius: 8px;
            overflow: hidden;
        }

        .article-info {
            text-align: center;
            margin: 20px;
        }
    </style>
</head>

<body>
    <header>
        <div class="header-container">
            <div class="logo-container">
                <a href="/front-app/ex-user.php">
                    <img src="/front-app/pic/logo1.png" alt="logo">
                </a>
            </div>
            <h1>ระบบจัดเก็บผลงานตีพิมพ์อาจารย์</h1> 
        </div>
    </header>

    <?php

    if (!isset($_SESSION['id'])) {
        $_SESSION['id'] = '';
    }

    if ($_SESSION['id'] != null && $_SESSION['id'] != '') { ?>
        <nav class="nav">
            <ul>
                <?php
                // ดึง user_id ของผู้ใช้ปัจจุบันจาก session
                $current_username = $_SESSION['username'] ?? null;
                $current_password = $_SESSION['password'] ?? null;
                $row_user = null;
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
    <?php } else { ?>
        <nav class="nav">
            <ul>
                <div class="user-login-container">
                    <div class="login-info">
                        <h3>สำหรับบุคลากร</h3>
                        <form action="/back-app/login-exit/login.php" method="post">
                            <div class="input-box">
                                <input type="text" name="username" placeholder="ชื่อผู้ใช้" required>
                            </div>
                            <div class="input-box">
                                <input type="password" name="pass" placeholder="รหัสผ่าน" required>
                            </div>
                            <button type="submit" class="btn">ลงชื่อเข้าใช้</button>
                        </form>
                    </div>
                </div>
                <div class="line"></div>
                <li><a href="/front-app/ex-user.php"><i class="bi bi-house icon-large"></i> หน้าแรก</a></li>
                <li><a href="/front-app/ex-public.php"><i class="bi bi-journal-text icon-large"></i> บทความตีพิมพ์</a></li>
                <li><a href="/front-app/contact.php"><i class="bi bi-envelope icon-large"></i> ติดต่อ</a></li>
                <li><a href="/front-app/objective.php"><i class="bi bi-info-circle icon-large"></i> เกี่ยวกับ</a></li>
            </ul>
        </nav>
    <?php } ?>
    <main>
        <?php if ($article):
            $acc_id = $article['acc_id'];
            $c_id = $article['c_id'];
            $user_id = $user_acc_map[$acc_id]['user_id'];
            $author = $user_map[$user_id] ?? null;
            $category = $category_map[$c_id] ?? null;

            // path ไฟล์ pdf
            $file = "/src/file_public/" . $article['file'];
        ?>
            <div class="article-info">
                <div class="pic-left-info">
                    <?php if (!empty($article['pic'])): ?>
                        <div class="article-pic">
                            <img src="/src/pic_public/<?php echo htmlspecialchars($article['pic']); ?>" alt="รูปบทความ">
                        </div>
                    <?php elseif (empty($article['pic'])): ?>
                        <div class="article-pic">
                            <img src="/front-app/Pic/bk1.jpg" alt="รูปบทความ">
                        </div>
                    <?php endif; ?>

                    <div class="article-text">
                        <h2><?php echo htmlspecialchars($article['pub_name']); ?></h2>
                        <p>ผู้แต่ง: <?php echo htmlspecialchars(($author['fname'] ?? '') . " " . ($author['lname'] ?? '')); ?></p>
                        <p>หมวดหมู่: <?php echo htmlspecialchars($category['cname'] ?? ''); ?></p>
                    </div>
                </div>
            </div>


            <?php if (!empty($article['file'])): ?>
                <div class="pdf-viewer">
                    <iframe src="<?php echo htmlspecialchars($file); ?>" width="95%" height="10%"></iframe>
                </div>
            <?php elseif (empty($article['pic']) && $article['file'] == null): ?>
                <div class="pdf-viewer">
                    <p>ไม่มีการอัปโหลดไฟล์</p>
                </div>
            <?php endif; ?>


        <?php else: ?>
            <p style="text-align:center; color:red;">ไม่พบบทความ</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>@มหาวิทยาลัย สงขลานครินทร์ วิทยาเขตหาดใหญ่. สมาชิก 143 251 253 254 325 378 </p>
    </footer>
</body>

</html>