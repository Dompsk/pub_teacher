<?php
include("../condb.php");  // เชื่อม DB
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST["username"];
    $password = $_POST["pass"];

    // ตรวจสอบ username/password
    $stmt = $con->prepare("SELECT acc_id, username, password FROM user_acc WHERE username=? AND password=?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // สร้าง session
        $_SESSION["username"] = $row["username"];
        $_SESSION["pass"] = $row["password"];
        $_SESSION["id"] = $row["acc_id"];

        // บันทึก login_time
        $acc_id = $row["acc_id"];
        $login_time = date("Y-m-d H:i:s");
        $stmt2 = $con->prepare("INSERT INTO login_log (login_time, acc_id) VALUES (?, ?)");
        $stmt2->bind_param("si", $login_time, $acc_id);
        $stmt2->execute();

        // เก็บ log_id ไว้ใน session สำหรับ logout
        $_SESSION["log_id"] = $stmt2->insert_id;

        // redirect ไปหน้า user
        header("Location: /pro_teacher/front-app/user-role-index/teacher/index-role-teacher.php");
        exit();
    } else {
        // login ผิด → redirect หน้า error
        header("Location: ../front-app/ex-user.php");
        exit();
    }
}
?>
