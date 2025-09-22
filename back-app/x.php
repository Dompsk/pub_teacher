<?php
include 'condb.php';

// เพิ่มข้อมูลใหม่
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id']) && !isset($_POST['update_user_id'])) {
    $newUser = [
        'user_id' => $_POST['user_id'],
        'fname' => $_POST['fname'],
        'lname' => $_POST['lname'],
        'age' => $_POST['age'],
        'tel' => $_POST['tel'],
        'major' => $_POST['major']
    ];
    insertSupabaseData('user', $newUser);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// ลบข้อมูล
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user_id'])) {
    deleteSupabaseData('user', 'user_id', $_POST['delete_user_id']);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// เตรียมข้อมูลสำหรับแก้ไข
$editData = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_user_id'])) {
    $user_id = $_POST['edit_user_id'];
    $users = getSupabaseData('user');
    foreach ($users as $u) {
        if ($u['user_id'] == $user_id) {
            $editData = $u;
            break;
        }
    }
}

// อัปเดตข้อมูล
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user_id'])) {
    $updateData = [
        'fname' => $_POST['fname'],
        'lname' => $_POST['lname'],
        'age' => $_POST['age'],
        'tel' => $_POST['tel'],
        'major' => $_POST['major']
    ];
    updateSupabaseData('user', $updateData, 'user_id', $_POST['update_user_id']);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// ดึงข้อมูลทั้งหมด
$user = getSupabaseData('user');
$user_acc = getSupabaseData('user_acc');

// รวมข้อมูล
$accLookup = array_column($user_acc, null, 'user_id');
$combinedData = [];
if (!empty($user) && is_array($user)) {
    foreach ($user as $u) {
        $uid = $u['user_id'];
        $acc = $accLookup[$uid] ?? null;

        $combinedData[] = [
            'user_id' => $u['user_id'],
            'fname' => $u['fname'],
            'lname' => $u['lname'],
            'age' => $u['age'],
            'tel' => $u['tel'],
            'major' => $u['major'],
            'username' => $acc['username'] ?? '-',
            'role' => $acc['role'] ?? '-'
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ข้อมูลผู้ใช้จาก Supabase</title>
    <style>
        table {
            border-collapse: collapse;
            width: 90%;
            margin: 20px auto;
        }
        th, td {
            border: 1px solid #333;
            padding: 8px;
            text-align: center;
        }
        th {
            background: #f4f4f4;
        }
        form {
            text-align: center;
            margin-top: 30px;
        }
        input {
            margin: 4px;
            padding: 5px;
        }
        button {
            padding: 6px 10px;
        }
    </style>
</head>
<body>

<h2 style="text-align:center;">ข้อมูลผู้ใช้และบัญชีจาก Supabase</h2>

<table>
    <tr>
        <th>User ID</th>
        <th>ชื่อจริง</th>
        <th>นามสกุล</th>
        <th>อายุ</th>
        <th>เบอร์โทร</th>
        <th>สาขา</th>
        <th>ชื่อผู้ใช้</th>
        <th>บทบาท</th>
        <th>จัดการ</th>
    </tr>
    <?php foreach ($combinedData as $row): ?>
        <tr>
            <td><?php echo $row['user_id']; ?></td>
            <td><?php echo $row['fname']; ?></td>
            <td><?php echo $row['lname']; ?></td>
            <td><?php echo $row['age']; ?></td>
            <td><?php echo $row['tel']; ?></td>
            <td><?php echo $row['major']; ?></td>
            <td><?php echo $row['username']; ?></td>
            <td><?php echo $row['role']; ?></td>
            <td>
                <form method="post" style="display:inline;" onsubmit="return confirm('ยืนยันการลบ?');">
                    <input type="hidden" name="delete_user_id" value="<?php echo $row['user_id']; ?>">
                    <button type="submit">ลบ</button>
                </form>

                <form method="post" style="display:inline;">
                    <input type="hidden" name="edit_user_id" value="<?php echo $row['user_id']; ?>">
                    <button type="submit">แก้ไข</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<h3 style="text-align:center;"><?php echo $editData ? 'แก้ไขข้อมูลผู้ใช้' : 'เพิ่มผู้ใช้ใหม่'; ?></h3>
<form action="" method="post">
    <?php if ($editData): ?>
        <input type="hidden" name="update_user_id" value="<?php echo $editData['user_id']; ?>">
    <?php endif; ?>

    <input type="number" name="user_id" value="<?php echo $editData['user_id'] ?? ''; ?>" <?php echo $editData ? 'readonly' : 'required'; ?> placeholder="User ID">
    <input type="text" name="fname" value="<?php echo $editData['fname'] ?? ''; ?>" required placeholder="ชื่อจริง">
    <input type="text" name="lname" value="<?php echo $editData['lname'] ?? ''; ?>" required placeholder="นามสกุล">
    <input type="number" name="age" value="<?php echo $editData['age'] ?? ''; ?>" required placeholder="อายุ">
    <input type="text" name="tel" value="<?php echo $editData['tel'] ?? ''; ?>" required placeholder="เบอร์โทร">
    <input type="text" name="major" value="<?php echo $editData['major'] ?? ''; ?>" required placeholder="สาขา">

    <button type="submit"><?php echo $editData ? 'บันทึกการแก้ไข' : 'เพิ่มผู้ใช้ใหม่'; ?></button>
</form>

</body>
</html>