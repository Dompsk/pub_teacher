<?php
include("../condb.php");

// รับค่าค้นหาและหมวดหมู่
$search_query = $_GET['q'] ?? '';
$c_id = $_GET['c_id'] ?? null;

// ดึงข้อมูลจาก Supabase ครั้งเดียว
$publications = getSupabaseData('publication') ?? [];
$users = getSupabaseData('user') ?? [];
$user_accs = getSupabaseData('user_acc') ?? [];
$categories = getSupabaseData('category') ?? [];

// Map สำหรับค้นหา
$user_map = array_column($users, null, 'user_id');
$user_acc_map = array_column($user_accs, null, 'acc_id');
$category_map = array_column($categories, null, 'c_id');

// กรองเฉพาะ approved + หมวดหมู่ สำหรับ articles-list
$filtered_publications = array_filter($publications, function ($pub) use ($c_id) {
    return $pub['status'] === 'approve' && (!$c_id || $pub['c_id'] == $c_id);
});
usort($filtered_publications, function ($a, $b) {
    return strtotime($b['upload_date']) - strtotime($a['upload_date']);
});

// กรองเฉพาะ search-result แยกจาก filtered_publications
$search_articles = [];
if ($search_query !== '') {
    $search_articles = array_filter($publications, function ($pub) use ($c_id, $search_query) {
        return $pub['status'] === 'approve'
            && (!$c_id || $pub['c_id'] == $c_id)
            && stripos($pub['pub_name'], $search_query) !== false;
    });
    usort($search_articles, function ($a, $b) {
        return strtotime($b['upload_date']) - strtotime($a['upload_date']);
    });
}
?>


<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <title>ระบบจัดเก็บผลงานตีพิมพ์</title>
    <link rel="stylesheet" href="ex-public-cate.css">
    <link rel="icon" href="/pub_teacher/front-app/Pic/logo3.png" type="image/png">
</head>

<body>
    <header>
        <div class="header-container">
            <div class="logo-container">
                <a href="/pub_teacher/front-app/ex-user.php">
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
            <li><a href="/pub_teacher/front-app/objective.php"><i class="bi bi-info-circle icon-large"></i> เกี่ยวกับ</a></li>
        </ul>
    </nav>

    <main>
        <div class="main-wrapper">
            <h2>บทความตีพิมพ์ล่าสุด</h2>
            <div class="top-bar">
                <div class="nav-category">
                    <button class="category-button" onclick="location.href='ex-public.php'">ทั้งหมด</button>
                    <button class="category-button" onclick="location.href='ex-public-cate.php?c_id=1'">ระดับชาติ</button>
                    <button class="category-button" onclick="location.href='ex-public-cate.php?c_id=2'">ระดับนานาชาติ</button>
                    <button class="category-button" onclick="location.href='ex-public-cate.php?c_id=3'">วารสาร</button>
                    <button class="category-button" onclick="location.href='ex-public-cate.php?c_id=4'">ตำรา</button>
                    <button class="category-button" onclick="location.href='ex-public-cate.php?c_id=5'">อื่น ๆ</button>
                </div>
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
                $filtered_publications = array_filter($publications, function ($pub) use ($c_id) {
                    if ($pub['status'] !== 'approve') return false;
                    if ($c_id && $pub['c_id'] != $c_id) return false;
                    return true;
                });

                // เรียงตามวันที่ล่าสุด
                usort($filtered_publications, function ($a, $b) {
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
                    echo "<p>ยังไม่มีบทความ</p>";
                }
                ?>
            </div>
            <div class="search-bar">
                <form action="ex-public-cate.php" method="GET" class="search-form">
                    <input type="text" name="q" placeholder="ค้นหาบทความ..." value="<?php echo htmlspecialchars($search_query); ?>">
                    <input type="hidden" name="c_id" value="<?php echo htmlspecialchars($c_id); ?>">
                    <button type="submit"><i class="bi bi-search"></i></button>
                </form>


                <?php if ($search_query !== ''): ?>
                    <div class="search-results">
                        <?php if (!empty($search_articles)): ?>
                            <?php foreach ($search_articles as $article):
                                $acc_id = $article['acc_id'];
                                $user_id = $user_acc_map[$acc_id]['user_id'] ?? null;
                                $author = $user_map[$user_id] ?? null;
                                $category = $category_map[$article['c_id']] ?? null;

                                // รูป fallback ถ้าไม่มีภาพ
                                $img = !empty($article['pic'])
                                    ? "/pub_teacher/src/pic_public/" . $article['pic']
                                    : "/pub_teacher/front-app/Pic/bk1.jpg";
                            ?>
                                <div class="article-card" onclick="location.href='detail.php?pub_id=<?= $article['pub_id'] ?>'">
                                    <div class="pic-left-info">
                                        <div class="article-pic">
                                            <img src="<?= htmlspecialchars($img) ?>" alt="รูปบทความ">
                                        </div>
                                        <div class="article-text">
                                            <h4><?= htmlspecialchars($article['pub_name']) ?></h4>
                                            <p>ผู้แต่ง: <?= htmlspecialchars(($author['fname'] ?? '') . " " . ($author['lname'] ?? '')) ?></p>
                                            <p>หมวดหมู่: <?= htmlspecialchars($category['cname'] ?? '') ?></p>
                                            <p>ปี: <?= htmlspecialchars($article['year'] ?? 'ไม่ระบุ') ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p style="text-align:center; color:red;">ไม่พบบทความที่ค้นหา</p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            </div>

    </main>

    <footer>
        <p>@มหาวิทยาลัย สงขลานครินทร์ วิทยาเขตหาดใหญ่. สมาชิก 143 251 253 254 325 378 </p>
    </footer>
</body>

</html>