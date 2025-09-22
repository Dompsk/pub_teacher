<?php
// condb.php (ใช้ Supabase)
$SUPABASE_URL = "https://jibnhzwxuzoccvxhzqri.supabase.co"; 
$SUPABASE_KEY = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."; 

<<<<<<< HEAD
// ฟังก์ชันสำหรับดึงข้อมูล (Read/Select)
function getSupabaseData($table, $query = "") {
=======
function getSupabaseData($table) {
>>>>>>> 3dab4e7899de71ad468510471273653693a085d4
    global $SUPABASE_URL, $SUPABASE_KEY;

    $url = $SUPABASE_URL . "/rest/v1/" . $table . "?select=*" . $query;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "apikey: $SUPABASE_KEY",
        "Authorization: Bearer $SUPABASE_KEY",
        "Content-Type: application/json",
        "Accept: application/json"
    ]);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        die("cURL Error: " . curl_error($ch));
    }
    curl_close($ch);
    return json_decode($response, true);
}

// กรอง acc_id = 3 และ join ไปยัง user_acc
$publication = getSupabaseData('publication', "&acc_id=eq.4&user_acc(*)");

// รวมข้อมูล
$combinedData = [];
if (!empty($publication) && is_array($publication)) {
    foreach ($publication as $p) {
        $combinedData[] = [
            'pub_id'   => $p['pub_id'],
            'pub_name' => $p['pub_name'],
            'file'     => $p['file'],
            'status'   => $p['status'],
            'username' => isset($p['user_acc']['username']) ? $p['user_acc']['username'] : '-' // เพิ่ม username จาก user_acc
        ];
    }
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <title>ระบบจัดเก็บผลงานตีพิมพ์</title>
    <link rel="stylesheet" href="public.css">
    <?php
    session_start();
    $con = new mysqli("localhost", "root", "", "public_teacher");
    ?>
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
            <h1>ระบบจัดเก็บผลงานตีพิมพ์อาจารย์</h1>
        </div>
    </header>

    <main>
        <a href="/pub_teacher/front-app/user-role-index/teacher/index-role-teacher.php">
            <button class="btn">ย้อนกลับ</button>
        </a>

        <div style="overflow-x:auto; max-width:100%;">
            <table>
                <thead>
<<<<<<< HEAD
    <tr>
        <th style="width: 30px;">NO</th>
        <th style="width: 200px;">Publication Name</th>
        <th style="width: 120px;">File</th>
        <th style="width: 80px;">Status</th>
        <th style="width: 50px;">Edit</th>
        <th style="width: 50px;">Delete</th>
    </tr>
</thead>
<tbody>
<?php foreach ($combinedData as $row): ?>
    <tr>
        <td><?php echo $row['pub_id']; ?></td>
        <td><?php echo $row['pub_name']; ?></td>
        <td><?php echo $row['file']; ?></td>
        <td><?php echo $row['status']; ?></td>
        <td>
            <form method="post" style="display:inline;">
                <input type="hidden" name="edit_pub_id" value="<?php echo $row['pub_id']; ?>">
                <button type="submit">แก้ไข</button>
            </form>
        </td>
        <td>
            <form method="post" style="display:inline;" onsubmit="return confirm('ยืนยันการลบ?');">
                <input type="hidden" name="delete_pub_id" value="<?php echo $row['pub_id']; ?>">
                <button type="submit">ลบ</button>
            </form>
        </td>
    </tr>
<?php endforeach; ?>
</tbody>
=======
                    <tr style="height: 70px;">
                        <th style="width: 30px;">NO</th>
                        <th style="width: 200px;">Publication Name</th>
                        <th style="width: 120px;">File</th>
                        <th style="width: 50px;">Edit</th>
                        <th style="width: 50px;">Delete</th>
                        <th style="width: 80px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($combinedData as $i => $row): ?>
                        <tr style="height: 70px;">
                            <td><?php echo $i + 1; ?></td>
                            <td><?php echo $row['pub_name']; ?></td>
                            <td>
                                <a href="/pub_teacher/src/file_public/<?php echo $row['file']; ?>">
                                    <?php echo $row['file']; ?>
                                </a>
                            </td>
                            <td>
                                <a href="edit-public.php?pub_id=<?php echo $row['pub_id']; ?>">แก้ไข</a>
                            </td>
                            <td>
                                <a href="/pub_teacher/back-app/delete-publication.php?pub_id=<?php echo $row['pub_id']; ?>"
                                   onclick="return confirm('Do you want to delete this publication? !!!')">ลบ</a>
                            </td>
                            <td>
                                <?php echo ($row["status"] == 'approve') ? "อนุมัติ" : "รอการอนุมัติ"; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
>>>>>>> 3dab4e7899de71ad468510471273653693a085d4
            </table>

            <a href="/pub_teacher/front-app/user-role-index/teacher/add-public.php">
                <button class="x">เพิ่มบทความ</button>
            </a>
        </div>
    </main>

    <footer>
        <p>@มหาวิทยาลัย สงขลานครินทร์ วิทยาเขตหาดใหญ่. สมาชิก 143 251 253 254 325 378 </p>
    </footer>

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
</body>
</html>
