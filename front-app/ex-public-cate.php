<?php
    include("../condb.php");
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <title>ระบบจัดเก็บผลงานตีพิมพ์</title>
    <link rel="stylesheet" href="ex-public.css">
    <link rel="icon" href="/pub_teacher/front-app/Pic/logo3.png" type="image/png">
</head>
<body>
<header>
    <div class="header-container">
        <div class="logo-container">
            <a href="ex-user.html">
                <img src="/pub_teacher/front-app/pic/logo1.png" alt="logo">
            </a>
        </div>
        <h1>ระบบจัดเก็บผลงานตีพิมพ์อาจารย์</h1> 
    </div>
</header>

<nav class="nav">
    <ul>
        <div class="user-login-container">
            <div class="login-info">
                <h3>สำหรับบุคลากร</h3>
                <form action="/pub_teacher/back-app/login-exit/login.php" method="post">
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
        <li><a href="/pub_teacher/front-app/ex-user.php"><i class="bi bi-house icon-large"></i> หน้าแรก</a></li>
        <li><a href="/pub_teacher/front-app/ex-public.php"><i class="bi bi-journal-text icon-large"></i> บทความตีพิมพ์</a></li>
        <li><a href="/pub_teacher/front-app/contact.php"><i class="bi bi-envelope icon-large"></i> ติดต่อ</a></li>
        <li><a href="/front-app/function/ManageProfile.php"><i class="bi bi-info-circle icon-large"></i> เกี่ยวกับ</a></li>
    </ul>
</nav>

<main>
    <div class="main-wrapper">
        <h2>บทความตีพิมพ์</h2>

        <div class="nav-category">
            <button class="category-button" onclick="location.href='ex-public.php'">ทั้งหมด</button>
            <button class="category-button" onclick="location.href='ex-public-cate.php?c_id=1'">ระดับชาติ</button> 
            <button class="category-button" onclick="location.href='ex-public-cate.php?c_id=2'">ระดับนานาชาติ</button> 
            <button class="category-button" onclick="location.href='ex-public-cate.php?c_id=3'">วารสาร</button>                 
            <button class="category-button" onclick="location.href='ex-public-cate.php?c_id=4'">ตำรา</button>   
            <button class="category-button" onclick="location.href='ex-public-cate.php?c_id=5'">อื่น ๆ</button>   
        </div>

        <div class="content-container">     
            <?php
                // รับค่า c_id จาก URL
                $c_id = $_GET['c_id'] ?? null;

                // ดึงข้อมูลจาก Supabase
                $publications = getSupabaseData('publication');
                $users = getSupabaseData('user');
                $user_accs = getSupabaseData('user_acc');
                $categories = getSupabaseData('category');

                // Map สำหรับค้นหา
                $user_map = array_column($users, null, 'user_id');
                $user_acc_map = array_column($user_accs, null, 'acc_id');
                $category_map = array_column($categories, null, 'c_id');

                // กรองเฉพาะ approved และถ้ามี c_id ให้กรองตามหมวดหมู่
                $filtered_publications = array_filter($publications, function($pub) use ($c_id) {
                    if ($pub['status'] !== 'approve') return false;
                    if ($c_id && $pub['c_id'] != $c_id) return false;
                    return true;
                });

                // เรียงตามวันที่ล่าสุด
                usort($filtered_publications, function($a, $b) {
                    return strtotime($b['upload_date']) - strtotime($a['upload_date']);
                });

                if (!empty($filtered_publications)) {
                    foreach ($filtered_publications as $row) {
                        $acc_id = $row['acc_id'];
                        $user_id = $user_acc_map[$acc_id]['user_id'];
                        $author = $user_map[$user_id] ?? null;
                        $category = $category_map[$row['c_id']] ?? null;

                        $img = (!empty($row['pic'])) 
                            ? "/pub_teacher/src/pic_public/" . $row['pic']
                            : "/pub_teacher/front-app/Pic/bk1.jpg";
            ?>
            <div class="articles-list" onclick="location.href='detail.php?pub_id=<?php echo $row['pub_id']; ?>'">
                <div class="pic-articles">
                    <img src="<?php echo htmlspecialchars($img); ?>" alt="ภาพบทความ">
                </div>
                <p><?php echo htmlspecialchars($row['pub_name']); ?></p>
                <p>โดย: <?php echo htmlspecialchars(($author['fname'] ?? '') . " " . ($author['lname'] ?? '')); ?></p>
                <p>หมวดหมู่: <?php echo htmlspecialchars($category['cname'] ?? ''); ?></p>
            </div>
            <?php
                    }
                } else {
                    echo "<p>ยังไม่มีบทความในหมวดหมู่นี้</p>";
                }
            ?>
        </div>
    </div>
</main>

<footer>
    <p>@มหาวิทยาลัย สงขลานครินทร์ วิทยาเขตหาดใหญ่. สมาชิก 143 251 253 254 325 378 </p>
</footer>
</body>
</html>
