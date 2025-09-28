<?php

$SUPABASE_URL = "https://jibnhzwxuzoccvxhzqri.supabase.co"; 
$SUPABASE_KEY = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImppYm5oend4dXpvY2N2eGh6cXJpIiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc1ODM3ODcyMywiZXhwIjoyMDczOTU0NzIzfQ.64nPKBZ6I5EkU0hSfq-NSIrNkdfToOwkVXqN7GQRBnE"; 

// ฟังก์ชันสำหรับลบข้อมูล (Delete)
function deleteSupabaseData($table, $filterColumn, $filterValue) {
    global $SUPABASE_URL, $SUPABASE_KEY;

    $url = $SUPABASE_URL . "/rest/v1/" . $table . "?{$filterColumn}=eq.{$filterValue}";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE"); // ใช้ DELETE สำหรับลบข้อมูล
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "apikey: $SUPABASE_KEY",
        "Authorization: Bearer $SUPABASE_KEY",
        "Content-Type: application/json"
    ]);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        die("cURL Error: " . curl_error($ch));
    }
    curl_close($ch);
    // การลบข้อมูลที่สำเร็จจะคืนค่าเป็น JSON ว่างเปล่า []
    return json_decode($response, true);
}

session_start();

$pub_id = $_POST['delete_pub_id'] ?? null;

if ($pub_id) {
    try {
        $deleted = deleteSupabaseData('publication', 'pub_id', $pub_id);
        // redirect ด้วย header
        header("Location: /front-app/user-role-index/teacher/public.php");
        exit;
    } catch (Exception $e) {
        echo "<script>
                alert('เกิดข้อผิดพลาด: ".$e->getMessage()."');
                window.location='/front-app/user-role-index/teacher/public.php';
              </script>";
    }
} else {
    header("Location: /front-app/user-role-index/teacher/public.php");
    exit;
}
?>
