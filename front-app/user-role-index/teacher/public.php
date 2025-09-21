<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <title>ระบบจัดเก็บผลงานตีพิมพ์</title>
    <link rel="stylesheet" href="public.css">
    <?php
    session_start();
    $con = new mysqli("localhost", "root", "", "public_teacher");
    ?>
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
        <a href="/pub_teacher/front-app/user-role-index/teacher/index-role-teacher.php">
            <button class="btn">ย้อนกลับ</button>
        </a>
        <div style="overflow-x:auto; max-width:100%;">
            <table>
                <thead>
                    <tr style="height: 70px;">
                        <th style="width: 30px;">NO</th>
                        <th style="width: 200px;">Publication Name</th>
                        <th style="width: 120px;">File</th>
                        <th style="width: 50px;">Edit</th>
                        <th style="width: 50px;">Delete</th>
                        <th style="width: 50px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $id = $_SESSION["id"];
                    $sql = "SELECT * FROM publication p where p.acc_id = '$id'";
                    $result = mysqli_query($con, $sql);
                    

                    for ($i = 0; $i < mysqli_num_rows($result); $i++) {
                        $row = mysqli_fetch_assoc($result);
                    ?>

                    
                        <tr style="height: 70px;">
                            <td style="width: 30px;"><?php echo $i + 1; ?></td>
                            <td style="width: 200px;"><?php echo $row['pub_name']; ?></td>
                            <td style="width: 120px;"><a href="/pub_teacher/src/file_public/<?php echo $row['file']; ?>"><?php echo $row['file']; ?></a></td>
                            <?php echo "<td><a href='edit-public.php?pub_id=$row[pub_id]'>แก้ไข</a></td> ";?>
                            <?php echo "<td><a href='/pub_teacher/back-app/delete-publication.php?pub_id=$row[pub_id]' onclick=\"return confirm('Do you want to delete this publication? !!!')\">ลบ</a></td> ";?>
                            <td style="width: 50px;">
                                <?php
                                    if($row["status"] == 'approve')
                                        echo "อนุมติ";
                                    else echo "รอการอนุมติ";
                                ?>
                            </td>
                        </tr>

                    <?php
                    }
                    ?>
                </tbody>
                <a href="/pub_teacher/front-app/user-role-index/teacher/add-public.php">
                    <button class="x">เพิ่มบทความ</button>
                </a>

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