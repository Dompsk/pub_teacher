<?php
session_start();
include("../condb.php"); // เชื่อม DB

if (isset($_SESSION["log_id"])) {
    $log_id = $_SESSION["log_id"];
    $logout_time = date("Y-m-d H:i:s");

    $sql = "UPDATE login_log SET logout_time=? WHERE log_id=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("si", $logout_time, $log_id);
    $stmt->execute();
}

// ล้าง session
session_unset();
session_destroy();

// redirect กลับ login
header("Location: /pro_teacher/front-app/ex-user.php");
exit();
?>