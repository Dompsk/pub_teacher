<?php

$SUPABASE_URL = "https://jibnhzwxuzoccvxhzqri.supabase.co";
$SUPABASE_KEY = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImppYm5oend4dXpvY2N2eGh6cXJpIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTgzNzg3MjMsImV4cCI6MjA3Mzk1NDcyM30.5rg489NwkhiVvkXI2Y5wJy56Ads9JjFVX6snArPlrPc";


function getSupabaseData($table, $query = "")
{
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


session_start();
$acc_id = $_SESSION["id"];
$publication = getSupabaseData('publication', "&acc_id=eq.$acc_id");


$combinedData = [];
if (!empty($publication) && is_array($publication)) {
    foreach ($publication as $p) {
        $combinedData[] = [
            'pub_id'     => $p['pub_id'],
            'pub_name'   => $p['pub_name'],
            'file'       => $p['file'],
            'upload_date'=> $p['upload_date'],
            'status'     => $p['status'],
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
        <a href="/pub_teacher/front-app/user-role-index/teacher/index-role-teacher.php"><button class="btn">ย้อนกลับ</button></a>

        <div style="overflow-x:auto; max-width:100%;">
            <table>
                <thead>
                    <tr style="height: 70px;">
                        <th style="width: 30px;">NO</th>
                        <th style="width: 200px;">Publication Name</th>
                        <th style="width: 120px;">File</th>
                        <th style="width: 120px;">Upload</th>
                        <th style="width: 80px;">Status</th>
                        <th style="width: 50px;">Edit</th>
                        <th style="width: 50px;">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($combinedData as $i => $row): ?>
                        <tr style="height: 70px;">
                            <td><?php echo $i + 1; ?></td>
                            <td><?php echo $row['pub_name']; ?></td>
                            <td><?php echo $row['file']; ?></td>
                            <td><?php echo $row['upload_date']; ?></td>
                            <td class="<?php echo ($row['status'] === 'approve') ? 'status-approve' : 'status-not-approve'; ?>">
                                <?php echo htmlspecialchars($row['status']); ?>
                            </td>
                            <td>
                             <a href="edit-public.php?pub_id=<?php echo $row['pub_id']; ?>">
    <button type="button">แก้ไข</button>
</a>

                            </td>
                            <td>
                                <form method="post" action="delete-publication.php" style="display:inline;" onsubmit="return confirm('ยืนยันการลบ?');">
                                    <input type="hidden" name="delete_pub_id" value="<?php echo $row['pub_id']; ?>">
                                    <button type="submit">ลบ</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <a href="/pub_teacher/front-app/user-role-index/teacher/add-public.php"><button class="x">เพิ่มบทความ</button></a>
        </div>
    </main>

    <footer>
        <p>@มหาวิทยาลัย สงขลานครินทร์ วิทยาเขตหาดใหญ่. สมาชิก 143 251 253 254 325 378 </p>
    </footer>
</body>
</html>
