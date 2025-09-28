<?php
session_start();

$SUPABASE_URL = "https://jibnhzwxuzoccvxhzqri.supabase.co";
$SUPABASE_KEY = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImppYm5oend4dXpvY2N2eGh6cXJpIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTgzNzg3MjMsImV4cCI6MjA3Mzk1NDcyM30.5rg489NwkhiVvkXI2Y5wJy56Ads9JjFVX6snArPlrPc";

function getSupabaseData($table, $query = "")
{
    global $SUPABASE_URL, $SUPABASE_KEY;
    $url = $SUPABASE_URL . "/rest/v1/" . $table . "?select=*" . $query;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "apikey: $SUPABASE_KEY",
        "Authorization: Bearer $SUPABASE_KEY",
        "Content-Type: application/json",
        "Accept: application/json"
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

function insertSupabaseData($table, $data)
{
    global $SUPABASE_URL, $SUPABASE_KEY;

    $url = $SUPABASE_URL . "/rest/v1/" . $table;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "apikey: $SUPABASE_KEY",
        "Authorization: Bearer $SUPABASE_KEY",
        "Content-Type: application/json",
        "Prefer: return=representation"
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        error_log("cURL Error: " . curl_error($ch));
        curl_close($ch);
        return false;
    }
    curl_close($ch);

    // ตรวจสอบ HTTP status code
    if ($httpCode >= 400) {
        error_log("Supabase Error (HTTP $httpCode): " . $response);
        error_log("Data sent: " . json_encode($data));
        return false;
    }

    return json_decode($response, true);
}

function updateSupabaseData($table, $id, $data)
{
    global $SUPABASE_URL, $SUPABASE_KEY;

    $url = $SUPABASE_URL . "/rest/v1/$table?pub_id=eq.$id";
    $jsonData = json_encode($data);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "apikey: $SUPABASE_KEY",
        "Authorization: Bearer $SUPABASE_KEY",
        "Content-Type: application/json",
        "Prefer: return=representation"
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        error_log("cURL Error: " . curl_error($ch));
        curl_close($ch);
        return false;
    }
    curl_close($ch);

    if ($httpCode >= 400) {
        error_log("Supabase Update Error (HTTP $httpCode): " . $response);
        return false;
    }

    return json_decode($response, true);
}

$pub_id = $_GET['pub_id'] ?? $_POST['pub_id'] ?? null;
$pub_data = null;

if ($pub_id) {
    $result = getSupabaseData("publication", "&pub_id=eq.$pub_id");
    $pub_data = $result[0] ?? null;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['save'])) {
    $pub_name = $_POST['pub_name'] ?? "";
    $c_id = $_POST['pub_type'] ?? "";
    // เตรียม updateData เริ่มต้นด้วยชื่อบทความ
    $updateData = [
        "pub_name" => $pub_name,
        "c_id" => $c_id
    ];

    // จัดการไฟล์ - อัปเดตเฉพาะเมื่อมีไฟล์ใหม่
    $filename = $_FILES['file']['name'] ?? null;
    if (!empty($filename) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $targetDir = $_SERVER['DOCUMENT_ROOT'] . "/src/file_public/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);

        if (move_uploaded_file($_FILES['file']['tmp_name'], $targetDir . $filename)) {
            $updateData["file"] = $filename;
        }
    }

    // จัดการรูป - อัปเดตเฉพาะเมื่อมีรูปใหม่
    $picname = $_FILES['pic']['name'] ?? null;
    if (!empty($picname) && $_FILES['pic']['error'] === UPLOAD_ERR_OK) {
        $targetDir = $_SERVER['DOCUMENT_ROOT'] . "/src/pic_public/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);

        if (move_uploaded_file($_FILES['pic']['tmp_name'], $targetDir . $picname)) {
            $updateData["pic"] = $picname;
        }
    }

    // อัปเดตข้อมูล
    $updateResult = updateSupabaseData("publication", $pub_id, $updateData);

    // บันทึก history หลังจากอัปเดต
    if ($updateResult !== false) {
        $upload_date = date("Y-m-d H:i:s");
        $updateLog = [
            "edit_time" => $upload_date,
            "pub_id" => intval($pub_id) // แปลงเป็น integer
        ];

        error_log("Attempting to insert history: " . json_encode($updateLog));
        $historyResult = insertSupabaseData("history", $updateLog);

        if ($historyResult === false) {
            error_log("Failed to insert history for pub_id: " . $pub_id);
        } else {
            error_log("Successfully inserted history: " . json_encode($historyResult));
        }
    }

    header("Location: public.php");
    exit;
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <title>ระบบจัดเก็บผลงานตีพิมพ์</title>
    <link rel="stylesheet" href="edit-public.css">
    <link rel="icon" href="/front-app/Pic/logo3.png" type="image/png">

</head>

<body>
    <header>

        <div class="header-container">

            <div class="logo-container">
                <a href="/front-app/user-role-index/teacher/index-role-teacher.php">
                    <img src="Pic/logo1.png" alt="logo">
                </a>
            </div>
            <h1>ระบบจัดเก็บผลงานตีพิมพ์อาจารย์</h1>
        </div>

    </header>


    <main>
        <?php if ($pub_data): ?>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="pub_id" value="<?= htmlspecialchars($pub_id) ?>">

                <div class="box">
                    <label style="font-weight: bold;">ชื่อบทความ :</label>

                    <input type="text" name="pub_name" value="<?= htmlspecialchars($pub_data['pub_name'] ?? '') ?>" required>


                    <label style="font-weight: bold;">ประเภทบทความ :</label>
                    <select name="pub_type">
                        <option value="1" <?= ($pub_data['c_id'] == 1) ? 'selected' : '' ?>>ระดับชาติ</option>
                        <option value="2" <?= ($pub_data['c_id'] == 2) ? 'selected' : '' ?>>ระดับนานาชาติ</option>
                        <option value="3" <?= ($pub_data['c_id'] == 3) ? 'selected' : '' ?>>วารสาร</option>
                        <option value="4" <?= ($pub_data['c_id'] == 4) ? 'selected' : '' ?>>ตำรา</option>
                        <option value="5" <?= ($pub_data['c_id'] == 5) ? 'selected' : '' ?>>อื่นๆ</option>
                    </select>
                    <br>




                    <label style="font-weight: bold;">ไฟล์เดิม:</label>
                    <?php if (!empty($pub_data['file'])): ?>
                        <a href="/src/file_public/<?= htmlspecialchars($pub_data['file']) ?>" target="_blank"><?= htmlspecialchars($pub_data['file']) ?></a>
                    <?php else: ?>
                        <span>ไม่มีไฟล์</span>
                    <?php endif; ?>

                    <label style="font-weight: bold;">รูปเดิม:</label>
                    <?php if (!empty($pub_data['pic'])): ?>
                        <a href="/src/pic_public/<?= htmlspecialchars($pub_data['pic']) ?>" target="_blank"><?= htmlspecialchars($pub_data['pic']) ?></a>
                    <?php else: ?>
                        <span>ไม่มีรูป</span>
                    <?php endif; ?>

                    <br>

                    <label style="font-weight: bold;">อัปโหลดไฟล์ใหม่:</label>
                    <input class="file-input" type="file" name="file" accept=".pdf,.doc,.docx">
                    <label style="font-weight: bold;">อัปโหลดรูปใหม่:</label>
                    <input class="file-input" type="file" name="pic">
                </div>

                <div class="button-group">
                    <button type="button" class="btn btn-cancel" onclick="window.history.back()">ยกเลิก</button>
                    <button type="submit" name="save" class="btn btn-save">ยืนยันการแก้ไข</button>
                </div>
            </form>
        <?php else: ?>
            <p style="text-align:center;">ไม่พบบทความ</p>
        <?php endif; ?>
    </main>

    <footer>
        @มหาวิทยาลัยสงขลานครินทร์ วิทยาเขตหาดใหญ่ สมาคม 143 251 252 253 254 325 378
    </footer>
</body>

</html>