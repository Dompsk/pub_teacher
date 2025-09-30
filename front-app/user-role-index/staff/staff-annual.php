<?php
include($_SERVER['DOCUMENT_ROOT'] . "/pub_teacher/condb.php");

// ฟังก์ชันดึงข้อมูลจาก Supabase
function fetchSupabaseData($table, $select = "*", $filters = []) {
    $api_url = "https://jibnhzwxuzoccvxhzqri.supabase.co/rest/v1/" . $table . "?select=" . $select;
    
    // เพิ่ม filters
    foreach ($filters as $key => $value) {
        $api_url .= "&" . $key . "=eq." . urlencode($value);
    }
    
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
    
    return json_decode($response, true);
}

// รับค่าจาก GET parameters
$selected_author = $_GET['author'] ?? 'all';
$selected_category = $_GET['category'] ?? 'all';
$start_year = $_GET['start_year'] ?? '';
$end_year = $_GET['end_year'] ?? '';

// ดึงข้อมูล publications (เพิ่ม pic)
$publications = fetchSupabaseData('publication', 'pub_id,pub_name,upload_date,c_id,acc_id,pic');

// ดึงข้อมูล participants
$participants = fetchSupabaseData('participants', 'pub_id,par_id,name');

// ดึงข้อมูล categories
$categories = fetchSupabaseData('category', 'c_id,cname');

// ดึงข้อมูล users สำหรับเจ้าของผลงาน
$users = fetchSupabaseData('user', 'user_id,fname,lname');
$user_accs = fetchSupabaseData('user_acc', 'acc_id,user_id');

// สร้าง mapping
$category_map = array_column($categories, 'cname', 'c_id');
$user_acc_map = array_column($user_accs, 'user_id', 'acc_id');
$user_map = array_column($users, null, 'user_id');

// จัดกลุ่ม participants ตาม pub_id
$participants_by_pub = [];
foreach ($participants as $p) {
    $participants_by_pub[$p['pub_id']][] = $p['name'];
}

// สร้าง mapping ชื่อผู้ใช้จาก acc_id
$author_names = [];
foreach ($user_accs as $ua) {
    $user_id = $ua['user_id'];
    $acc_id = $ua['acc_id'];
    if (isset($user_map[$user_id])) {
        $user = $user_map[$user_id];
        $author_names[$acc_id] = $user['fname'] . ' ' . $user['lname'];
    }
}

// รวบรวมรายชื่อผู้แต่งทั้งหมด (ไม่ซ้ำ)
$all_authors = [];
foreach ($publications as $pub) {
    if (!empty($pub['acc_id'])) {
        $author_name = $author_names[$pub['acc_id']] ?? 'ไม่ระบุผู้แต่ง';
        if (!in_array($author_name, $all_authors)) {
            $all_authors[] = $author_name;
        }
    }
}
sort($all_authors);

// กรองข้อมูล publications
$filtered_publications = [];
foreach ($publications as $pub) {
    // กรองตามปี
    if (!empty($pub['upload_date'])) {
        $pub_year = substr($pub['upload_date'], 0, 4);
        
        if ($start_year && $pub_year < $start_year) continue;
        if ($end_year && $pub_year > $end_year) continue;
    }
    
    // กรองตามประเภท
    if ($selected_category !== 'all' && $pub['c_id'] != $selected_category) continue;
    
    // กรองตามผู้แต่ง
    if ($selected_author !== 'all') {
        $pub_author = $author_names[$pub['acc_id']] ?? 'ไม่ระบุผู้แต่ง';
        if ($pub_author !== $selected_author) continue;
    }
    
    $filtered_publications[] = $pub;
}

// สรุปข้อมูลตามปี
$year_summary = [];
foreach ($filtered_publications as $pub) {
    if (!empty($pub['upload_date']) && !empty($pub['c_id'])) {
        $year = substr($pub['upload_date'], 0, 4);
        $cat_name = $category_map[$pub['c_id']] ?? 'ไม่ระบุ';
        
        if (!isset($year_summary[$year])) {
            $year_summary[$year] = [];
            foreach ($categories as $cat) {
                $year_summary[$year][$cat['cname']] = 0;
            }
            $year_summary[$year]["รวม"] = 0;
        }
        
        $year_summary[$year][$cat_name]++;
        $year_summary[$year]["รวม"]++;
    }
}

krsort($year_summary);

// ดึงข้อมูลผู้ใช้ปัจจุบัน
session_start();
$current_username = $_SESSION['username'] ?? null;
$current_password = $_SESSION['password'] ?? null;

$row_user = null;
$pic_path = "/pub_teacher/src/pic_user/df.png";

if ($current_username && $current_password) {
    $users = getSupabaseData('user');
    $user_accs = getSupabaseData('user_acc');
    $account_types = getSupabaseData('account_type');
    
    $user_map_login = array_column($users, null, 'user_id');
    $account_type_map = array_column($account_types, null, 'type_id');
    
    foreach ($user_accs as $ua) {
        if ($ua['username'] === $current_username && $ua['password'] === $current_password) {
            $user_id = $ua['user_id'];
            $row_user = $user_map_login[$user_id] ?? null;
            $row_user['type_name'] = $account_type_map[$ua['type_id']]['type_name'] ?? '';
            
            if (!empty($row_user['pic'])) {
                $pic_path = "/pub_teacher/src/pic_user/" . htmlspecialchars($row_user['pic']);
            }
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <title>ระบบจัดเก็บผลงานตีพิมพ์</title>
    <link rel="stylesheet" href="staff-annual.css">
    <style>
        .filter-container {
            background: #f8f9fa;
            padding: 20px;
            margin: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            width: 1300px;
            position: relative;
            left: 500px;
        }
        .articles-list {
            margin: 20px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: relative;
            top: 100px;
        }
        .article-item {
            display: flex;
            gap: 20px;
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            transition: all 0.3s ease;
            background: #f9f9f9;
        }
        .article-item:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }
        .article-thumbnail {
            width: 150px;
            height: 200px;
            object-fit: cover;
            border-radius: 6px;
            flex-shrink: 0;
        }
        .article-details {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .article-title {
            font-size: 18px;
            font-weight: bold;
            color: #004085;
            margin: 0;
        }
        .article-meta {
            color: #666;
            font-size: 14px;
            margin: 0;
        }
        .article-meta i {
            margin-right: 5px;
            color: #007bff;
        }
        .btn-view {
            padding: 8px 16px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            margin-top: 10px;
            transition: background 0.3s;
        }
        .btn-view:hover {
            background: #218838;
        }
        .section-title {
            color: #004085;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .filter-row {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
            margin-bottom: 15px;
        }
        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        .filter-group label {
            font-weight: bold;
            font-size: 14px;
        }
        .filter-group select,
        .filter-group input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .btn-filter {
            padding: 8px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 20px;
        }
        .btn-filter:hover {
            background: #0056b3;
        }
        .btn-reset {
            padding: 8px 20px;
            background: #6c757d;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 20px;
            margin-left: 10px;
        }
        .btn-reset:hover {
            background: #5a6268;
        }
        .summary-info {
            background: #e7f3ff;
            padding: 15px;
            margin: 20px;
            border-radius: 8px;
            border-left: 4px solid #007bff;
            position: relative;
            width: 1300px;
            left: 500px;
        }
        .summary-info h3 {
            margin: 0 0 10px 0;
            color: #007bff;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo-container">
                <a href="index.html">
                    <img src="/pub_teacher/front-app/Pic/logo1.png" alt="logo">
                </a>
            </div>
            <h1>ระบบจัดเก็บผลงานตีพิมพ์อาจารย์</h1> 
        </div>
    </header>

    <nav class="nav">
        <ul>
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
            <li><a href="#" onclick="openModal()"><i class="bi bi-gear icon-large"></i> คู่มือการใช้งาน</a></li>
            <p></p>
            <li><a href="/pub_teacher/back-app/login-exit/logout.php"><i class="bi bi-box-arrow-right icon-large"></i> ออกจากระบบ</a></li>
        </ul>
    </nav>

    <main>
        <!-- ส่วนกรองข้อมูล -->
        <div class="filter-container">
            <h2>กรองข้อมูล</h2>
            <form method="GET" action="">
                <div class="filter-row">
                    <div class="filter-group">
                        <label>ผู้แต่ง:</label>
                        <select name="author">
                            <option value="all" <?= $selected_author === 'all' ? 'selected' : '' ?>>ทั้งหมด</option>
                            <?php foreach ($all_authors as $author): ?>
                                <option value="<?= htmlspecialchars($author) ?>" <?= $selected_author === $author ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($author) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label>ประเภทบทความ:</label>
                        <select name="category">
                            <option value="all" <?= $selected_category === 'all' ? 'selected' : '' ?>>ทั้งหมด</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['c_id'] ?>" <?= $selected_category == $cat['c_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['cname']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label>ปีเริ่มต้น:</label>
                        <input type="number" name="start_year" value="<?= htmlspecialchars($start_year) ?>" placeholder="เช่น 2020" min="2000" max="2099">
                    </div>

                    <div class="filter-group">
                        <label>ปีสิ้นสุด:</label>
                        <input type="number" name="end_year" value="<?= htmlspecialchars($end_year) ?>" placeholder="เช่น 2025" min="2000" max="2099">
                    </div>
                </div>
                
                <button type="submit" class="btn-filter">
                    <i class="bi bi-search"></i> ค้นหา
                </button>
                <button type="button" class="btn-reset" onclick="window.location.href='<?= $_SERVER['PHP_SELF'] ?>'">
                    <i class="bi bi-arrow-clockwise"></i> รีเซ็ต
                </button>
            </form>
        </div>

        <!-- ข้อมูลสรุป -->
        <div class="summary-info">
            <h3><i class="bi bi-info-circle"></i> ข้อมูลที่แสดง</h3>
            <p>
                <?php 
                $filters_text = [];
                if ($selected_author !== 'all') $filters_text[] = "ผู้แต่ง: " . htmlspecialchars($selected_author);
                if ($selected_category !== 'all') {
                    $cat_name = $category_map[$selected_category] ?? '';
                    $filters_text[] = "ประเภท: " . htmlspecialchars($cat_name);
                }
                if ($start_year) $filters_text[] = "ปีเริ่มต้น: " . htmlspecialchars($start_year);
                if ($end_year) $filters_text[] = "ปีสิ้นสุด: " . htmlspecialchars($end_year);
                
                if (empty($filters_text)) {
                    echo "แสดงข้อมูลทั้งหมด";
                } else {
                    echo implode(" | ", $filters_text);
                }
                ?>
                <br>
                <strong>จำนวนผลงานที่พบ: <?= count($filtered_publications) ?> รายการ</strong>
            </p>
        </div>

        <!-- ตารางสรุปข้อมูล -->
        <div style="overflow-x:auto; max-width:100%;">
            <table>
                <thead>
                    <tr>
                        <th style="width: 100px;">ประจำปี</th>
                        <?php foreach ($categories as $cat): ?>
                            <th><?= htmlspecialchars($cat['cname']) ?></th>
                        <?php endforeach; ?>
                        <th style="width: 150px;">รวมทั้งหมดประจำปี</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($year_summary)): ?>
                        <?php foreach ($year_summary as $year => $counts): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($year) ?></strong></td>
                                <?php foreach ($categories as $cat): ?>
                                    <td><?= $counts[$cat['cname']] ?? 0 ?></td>
                                <?php endforeach; ?>
                                <td><strong><?= $counts["รวม"] ?></strong></td>
                            </tr>
                        <?php endforeach; ?>
                        
                        <!-- แถวรวมทั้งหมด -->
                        <tr style="background-color: #e7f3ff; font-weight: bold;">
                            <td>รวมทั้งหมด</td>
                            <?php 
                            $total_by_category = [];
                            $grand_total = 0;
                            foreach ($categories as $cat) {
                                $total = 0;
                                foreach ($year_summary as $counts) {
                                    $total += $counts[$cat['cname']] ?? 0;
                                }
                                $total_by_category[$cat['cname']] = $total;
                                $grand_total += $total;
                            }
                            ?>
                            <?php foreach ($categories as $cat): ?>
                                <td><?= $total_by_category[$cat['cname']] ?></td>
                            <?php endforeach; ?>
                            <td><?= $grand_total ?></td>
                        </tr>
                    <?php else: ?>
                        <tr><td colspan="<?= count($categories) + 2 ?>">ไม่พบข้อมูลตามเงื่อนไขที่เลือก</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- รายการบทความที่พบ -->
        <?php if (!empty($filtered_publications)): ?>
        <div class="articles-list">
            <h2 class="section-title">
                <i class="bi bi-journal-text"></i> รายการบทความที่พบ (<?= count($filtered_publications) ?> รายการ)
            </h2>
            
            <?php foreach ($filtered_publications as $pub): 
                $pub_acc_id = $pub['acc_id'] ?? null;
                $pub_c_id = $pub['c_id'] ?? null;
                
                // ดึงข้อมูลผู้แต่ง
                $author_name = 'ไม่ระบุผู้แต่ง';
                if ($pub_acc_id && isset($author_names[$pub_acc_id])) {
                    $author_name = $author_names[$pub_acc_id];
                }
                
                // ดึงข้อมูลหมวดหมู่
                $category_name = 'ไม่ระบุ';
                if ($pub_c_id && isset($category_map[$pub_c_id])) {
                    $category_name = $category_map[$pub_c_id];
                }
                
                // ปีที่เผยแพร่
                $pub_year = !empty($pub['upload_date']) ? substr($pub['upload_date'], 0, 4) : 'ไม่ระบุ';
                
                // path รูปภาพ
                $pic_path = !empty($pub['pic']) 
                    ? "/pub_teacher/src/pic_public/" . htmlspecialchars($pub['pic']) 
                    : "/pub_teacher/front-app/Pic/bk1.jpg";
            ?>
            <div class="article-item">
                <img src="<?= $pic_path ?>" alt="รูปบทความ" class="article-thumbnail">
                
                <div class="article-details">
                    <h3 class="article-title"><?= htmlspecialchars($pub['pub_name']) ?></h3>
                    
                    <p class="article-meta">
                        <i class="bi bi-person-fill"></i>
                        <strong>ผู้แต่ง:</strong> 
                        <?= htmlspecialchars($author_name) ?>
                    </p>
                    
                    <p class="article-meta">
                        <i class="bi bi-tag-fill"></i>
                        <strong>หมวดหมู่:</strong> 
                        <?= htmlspecialchars($category_name) ?>
                    </p>
                    
                    <p class="article-meta">
                        <i class="bi bi-calendar-fill"></i>
                        <strong>ปีที่เผยแพร่:</strong> 
                        <?= htmlspecialchars($pub_year) ?>
                    </p>
                    
                    <a href="/pub_teacher/front-app/detail.php?pub_id=<?= $pub['pub_id'] ?>" 
                       class="btn-view" 
                       target="_blank">
                        <i class="bi bi-eye-fill"></i> ดูรายละเอียด
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </main>

    <footer>
        <p>@มหาวิทยาลัย สงขลานครินทร์ วิทยาเขตหาดใหญ่. สมาชิก 143 251 253 254 325 378 </p>
    </footer>
</body>
</html>