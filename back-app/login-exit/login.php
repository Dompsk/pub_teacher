<?php
// Include your Supabase connection file
include($_SERVER['DOCUMENT_ROOT'] . "/pub_teacher/condb.php");

session_start();

$username = $_POST["username"];
$password = $_POST["pass"];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Fetch data from Supabase using the getSupabaseData function.
    $user_accounts = getSupabaseData('user_acc');
    
    $row = null;
    foreach ($user_accounts as $account) {
        if ($account['username'] === $username && $account['password'] === $password) {
            $row = $account;
            break;
        }
    }
    
    // 2. Check if a matching user was found.
    if ($row) {
        $_SESSION["username"] = $username;
        $_SESSION["password"] = $password;
        $_SESSION["id"] = $row["acc_id"];

        $acc_id = $row["acc_id"];
        $login_time = gmdate('Y-m-d\TH:i:s\Z'); // เช่น 2025-09-21 20:30:00
        
        $log_data = [
            'login_time' => $login_time,
            'acc_id' => $acc_id
        ];

        $login_log_entry = insertSupabaseData('login_log', $log_data);
        
        if (isset($login_log_entry[0]['log_id'])) {
            $_SESSION["log_id"] = $login_log_entry[0]['log_id'];
        }

        // 4. Redirect based on user type.
        if ($row["type_id"] == 1) { // Admin
            echo "<script> alert('เข้าสู่ระบบสำเร็จแอดมิน'); </script>";
            echo "<script> window.location='/pub_teacher/front-app/user-role-index/admin/index-role-admin.php'; </script>";
        } else if ($row["type_id"] == 2) { // Staff
            echo "<script> alert('เข้าสู่ระบบสำเร็จเจ้าหน้าที่'); </script>";
            echo "<script> window.location='/pub_teacher/front-app/user-role-index/staff/index-role-staff.php'; </script>";
        } else { // Teacher
            echo "<script> alert('เข้าสู่ระบบสำเร็จอาจารย์'); </script>";
            echo "<script> window.location='/pub_teacher/front-app/user-role-index/teacher/index-role-teacher.php'; </script>";
        }
    } else {
        // User not found or credentials incorrect.
        echo "<script> alert('ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง'); </script>";
        echo "<script> window.location='/pub_teacher/front-app/ex-user.php'; </script>";
    }
}