Try AI directly in your favorite apps … Use Gemini to generate drafts and refine content, plus get Gemini Pro with access to Google's next-gen AI
<?php
$con = new mysqli("localhost", "root", "", "public_teacher");
session_start();

$username = $_POST["username"];
$password = $_POST["pass"];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "select acc_id , username , password , type_id 
            from user_acc
            where username= '$username' and password = '$password'";
    $result = mysqli_query($con,$sql);
    $row = mysqli_fetch_assoc($result);

    if($row>0) {
        $_SESSION["username"] = $username;
        $_SESSION["password"] = $password;
        $_SESSION["id"] = $row["acc_id"];
        
        // บันทึก login_time
        $acc_id = $row["acc_id"];
        $login_time = date("Y-m-d H:i:s");
        $stmt2 = $con->prepare("INSERT INTO login_log (login_time, acc_id) VALUES (?, ?)");
        $stmt2->bind_param("si", $login_time, $acc_id);
        $stmt2->execute();
        // เก็บ log_id ไว้ใน session สำหรับ logout
        $_SESSION["log_id"] = $stmt2->insert_id;

        if($row["type_id"] == 1) { //แอดมิน
            echo "<script> alert('เข้าสู่ระบบสำเร็จแอดมิน'); </script>";
            echo "<script> window.location='/pub_teacher/front-app/user-role-index/admin/index-role-admin.php'; </script>";
        }
        else if($row["type_id"] == 2) { //เจ้าหน้าที่
            echo "<script> alert('เข้าสู่ระบบสำเร็จเจ้าหน้าที่'); </script>";
            echo "<script> window.location='/pub_teacher/front-app/user-role-index/staff/index-role-staff.php'; </script>";
        }
        else { //อาจารย์ 
            echo "<script> alert('เข้าสู่ระบบสำเร็จอาจารย์'); </script>";
            echo "<script> window.location='/pub_teacher/front-app/user-role-index/teacher/index-role-teacher.php'; </script>";
        }
    }
    else if($row["username"]!==$username && $row["password"]!==$password) {
        echo "<script> alert('ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง'); </script>";
        echo "<script> window.location='/pub_teacher/front-app/ex-user.php'; </script>";
    }
}
?>