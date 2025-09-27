<?php

$api_url = "https://jibnhzwxuzoccvxhzqri.supabase.co/rest/v1/publication?select=upload_date,c_id";
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


$data = json_decode($response, true);


$categories = [
    1 => "ระดับชาติ",
    2 => "ระดับนานาชาติ",
    3 => "วารสาร",
    4 => "ตำรา"
];


$year_summary = [];

if ($data) {
    foreach ($data as $row) {
        if (!empty($row['upload_date']) && !empty($row['c_id'])) {
            $year = substr($row['upload_date'], 0, 4);
            $cat_id = $row['c_id'];

            if (!isset($year_summary[$year])) {
                
                $year_summary[$year] = [
                    "ระดับชาติ" => 0,
                    "ระดับนานาชาติ" => 0,
                    "วารสาร" => 0,
                    "ตำรา" => 0,
                    "รวม" => 0
                ];
            }

            if (isset($categories[$cat_id])) {
                $label = $categories[$cat_id];
                $year_summary[$year][$label]++;
                $year_summary[$year]["รวม"]++;
            }
        }
    }

    ksort($year_summary); 
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ระบบจัดเก็บผลงานตีพิมพ์</title>
    <link rel="stylesheet" href="teacher-annual.css">
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
        <div style="overflow-x:auto; max-width:100%;">
            <table>
                <thead>
                    <tr>
                        <th>ประจำปี</th>
                        <th>วิชาการระดับชาติ</th>
                        <th>วิชาการระดับนานาชาติ</th>
                        <th>วารสาร</th>
                        <th>ตำรา</th>
                        <th>รวมทั้งหมดประจำปี</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($year_summary)): ?>
                        <?php foreach ($year_summary as $year => $counts): ?>
                            <tr>
                                <td><?= htmlspecialchars($year) ?></td>
                                <td><?= $counts["ระดับชาติ"] ?></td>
                                <td><?= $counts["ระดับนานาชาติ"] ?></td>
                                <td><?= $counts["วารสาร"] ?></td>
                                <td><?= $counts["ตำรา"] ?></td>
                                <td><?= $counts["รวม"] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6">ไม่พบข้อมูล</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <footer>
        <p>@มหาวิทยาลัย สงขลานครินทร์ วิทยาเขตหาดใหญ่. สมาชิก 143 251 252 253 254 325 378 </p>
    </footer>
</body>
</html>
