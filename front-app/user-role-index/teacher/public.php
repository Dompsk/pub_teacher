<?php
// condb.php
$SUPABASE_URL = "https://jibnhzwxuzoccvxhzqri.supabase.co"; 
$SUPABASE_KEY = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImppYm5oend4dXpvY2N2eGh6cXJpIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTgzNzg3MjMsImV4cCI6MjA3Mzk1NDcyM30.5rg489NwkhiVvkXI2Y5wJy56Ads9JjFVX6snArPlrPc"; 

// ฟังก์ชันสำหรับดึงข้อมูล (Read/Select)
function getSupabaseData($table) {
    global $SUPABASE_URL, $SUPABASE_KEY;

    $url = $SUPABASE_URL . "/rest/v1/" . $table . "?select=*";
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

$publication = getSupabaseData('publication');

// รวมข้อมูล
$combinedData = [];
if (!empty($publication) && is_array($publication)) {
    foreach ($publication as $p) {
        $combinedData[] = [
            'pub_id'   => $p['pub_id'],
            'pub_name' => $p['pub_name'],
            'file'     => $p['file'],
            'status'   => $p['status']
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
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo-container">
                <a href="index.html">
                    <img src="/pro_teacher/front-app/Pic/logo1.png" alt="logo">
                </a>
            </div>
            <h1>ระบบจัดเก็บผลงานตีพิมพ์อาจารย์</h1> 
        </div>
    </header>

    <main>
        <button class="btn">ย้อนกลับ</button>
      
        <div style="overflow-x:auto; max-width:100%;">
            <table>
                <thead>
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
            </table>
            <button class="x">เพิ่มบทความ</button>
        </div>
    </main>

    <footer>
        <p>@มหาวิทยาลัย สงขลานครินทร์ วิทยาเขตหาดใหญ่. สมาชิก 143 251 253 254 325 378 </p>
    </footer>
</body>
</html>
