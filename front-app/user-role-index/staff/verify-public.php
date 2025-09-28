<?php

// condb.php
include($_SERVER['DOCUMENT_ROOT'] . "/condb.php");

// กรอง acc_id ของ session
session_start();
$publication = getSupabaseData('publication');

// รวมข้อมูล
$combinedData = [];
if (!empty($publication) && is_array($publication)) {
    foreach ($publication as $p) {
        $combinedData[] = [
            'pub_id'      => $p['pub_id'],
            'pub_name'    => $p['pub_name'],
            'file'        => $p['file'],
            'upload_date' => $p['upload_date'],
            'status'      => $p['status'],
        ];
    }
}

// กรองเฉพาะ status = not approve
$combinedData = array_filter($combinedData, function ($pub) {
    return $pub['status'] === 'not approve';
});

if (isset($_POST['approve_pub_id'])) {
    $approve_pub_id = $_POST['approve_pub_id'];
    if ($approve_pub_id) {
        $data = ["status" => "approve"];
        $result = updateSupabaseData("publication", $data, "pub_id", $approve_pub_id);
        if ($result) {
            echo "<script>alert('อนุมัติผลงานเรียบร้อย'); window.location.href='verify-public.php';</script>";
        } else {
            echo "<script>alert('ผิดพลาด! ไม่สามารถอนุมัติได้');</script>";
        }
    }
}

$no = 0;
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <title>ระบบจัดเก็บผลงานตีพิมพ์</title>
    <link rel="stylesheet" href="verify-public.css">
</head>

<body>
    <header>
        <div class="header-container">
            <div class="logo-container">
                <a href="/front-app/user-role-index/staff/index-role-staff.php">
                    <img src="Pic/logo1.png" alt="logo">
                </a>
            </div>
            <h1>ตรวจสอบผลงานตีพิมพ์</h1>
        </div>
    </header>

    <main>
        <a href="/front-app/user-role-index/staff/index-role-staff.php">
            <button class="btn">ย้อนกลับ</button>
        </a>

        <div style="overflow-x:auto; max-width:100%;">
            <table>
                <thead>
                    <tr style="height: 70px;">
                        <th style="width: 30px;">NO</th>
                        <th style="width: 200px;">Publication Name</th>
                        <th style="width: 120px;">File</th>
                        <th style="width: 120px;">Upload</th>
                        <th style="width: 80px;">Status</th>
                        <th style="width: 50px;">Approve</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($combinedData)): ?>
                        <?php foreach ($combinedData as $row): ?>
                            <tr style="height: 70px;">
                                <td><?php echo ++$no; ?></td>
                                <td><?php echo htmlspecialchars($row['pub_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['file']); ?></td>
                                <td><?php echo htmlspecialchars($row['upload_date']); ?></td>
                                <td class="<?php echo ($row['status'] === 'approve') ? 'status-approve' : 'status-not-approve'; ?>">
                                    <?php echo htmlspecialchars($row['status']); ?>
                                </td>
                                <td>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="approve_pub_id" value="<?php echo $row['pub_id']; ?>">
                                        <button type="submit">Approve</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align:center; padding:20px; color:#888;">
                                ไม่มีข้อมูลที่ไม่ได้รับการอนุมัติ
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <footer>
        <p>@มหาวิทยาลัย สงขลานครินทร์ วิทยาเขตหาดใหญ่. สมาชิก 143 251 253 254 325 378 </p>
    </footer>
</body>
</html>

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
