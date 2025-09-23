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
    curl_close($ch);

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

    $filename = null;
    if (!empty($_FILES['file']['name'])) {
        $filename = time() . "_" . basename($_FILES['file']['name']);
        $targetPath = "uploads/" . $filename;
        move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
    }

    $updateData = [
        "pub_name" => $pub_name
    ];
    if ($filename) {
        $updateData["file"] = $filename;
    }

    updateSupabaseData("publication", $pub_id, $updateData);

    header("Location: public.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขบทความ</title>
    <link rel="stylesheet" href="edit-public.css">
</head>
<body>
<header>
    <div class="header-container">
        <div class="logo-container">
            <img src="psu-logo.png" alt="PSU Logo">
        </div>
        <h1>แก้ไขบทความ</h1>
    </div>
</header>

<main>
    <?php if ($pub_data): ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="pub_id" value="<?= htmlspecialchars($pub_id) ?>">

            <div class="box">
                <label>ชื่อบทความ :</label>
                <input type="text" name="pub_name" value="<?= htmlspecialchars($pub_data['pub_name'] ?? '') ?>" required>

                <label>ประเภทบทความ :</label>
                <select name="pub_type">
                    <option value="ระดับชาติ">ระดับชาติ</option>
                    <option value="ระดับนานาชาติ">ระดับนานาชาติ</option>
                    <option value="วารสาร">วารสาร</option>
                    <option value="ตำรา">ตำรา</option>
                    <option value="อื่นๆ">อื่นๆ</option>
                </select>
                <small>(เลือกได้ แต่ไม่ถูกบันทึกลงฐานข้อมูล)</small>

                <label>ไฟล์เดิม:</label>
                <?php if (!empty($pub_data['file'])): ?>
                    <a href="uploads/<?= htmlspecialchars($pub_data['file']) ?>" target="_blank"><?= htmlspecialchars($pub_data['file']) ?></a>
                <?php else: ?>
                    <span>ไม่มีไฟล์</span>
                <?php endif; ?>

                <label>อัปโหลดไฟล์ใหม่:</label>
                <input class="file-input" type="file" name="file" accept=".pdf,.doc,.docx">
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
